<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderWalletDetail\OrderWalletDetailApproveRequest;
use App\Http\Requests\OrderWalletDetail\OrderWalletDetailCancelRequest;
use App\Http\Requests\OrderWalletDetail\OrderWalletDetailCreateRequest;
use App\Http\Requests\OrderWalletDetail\OrderWalletDetailDeclineRequest;
use App\Http\Requests\OrderWalletDetail\OrderWalletDetailIndexQueryRequest;
use App\Http\Requests\OrderWalletDetail\OrderWalletDetailPendingRequest;
use App\Http\Requests\OrderWalletDetail\OrderWalletDetailReviewRequest;
use App\Http\Requests\OrderWalletDetail\OrderWalletDetailStoreRequest;
use App\Http\Requests\OrderWalletDetail\OrderWalletDetailUpdateRequest;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderDetailQuantity;
use App\Models\Product;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class OrderWalletDetailController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index($id)
    {
        try {
            $order = Order::with('sale_channel', 'seller_user', 'client.document_type', 'client_branch.country', 'client_branch.departament', 'client_branch.city')->findOrFail($id);
            return view('Dashboard.OrderWalletDetails.Index', compact('order'));
        } catch (ModelNotFoundException $e) {
            return back()->with('danger', 'Ocurrió un error al cargar el pedido: ' . $this->getMessage('ModelNotFoundException'));
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(OrderWalletDetailIndexQueryRequest $request)
    {
        try {
            $orderDetails = OrderDetail::with([
                    'order',
                    'order_detail_quantities.size',
                    'product' => fn($query) => $query->withTrashed(),
                    'color' => fn($query) => $query->withTrashed(),
                    'tone' => fn($query) => $query->withTrashed(),
                    'wallet_user' => fn($query) => $query->withTrashed(),
                    'dispatched_user' => fn($query) => $query->withTrashed(),
                ])
                ->where('order_id', $request->input('order_id'))
                ->get();

            $orderDetailQuantitySizes = OrderDetailQuantity::with('order_detail', 'size')
                ->whereHas('order_detail', fn($subQuery) => $subQuery->where('order_id', $request->input('order_id')))
                ->get();

            $orderDetails = $orderDetails->map(function ($orderDetail) use ($orderDetailQuantitySizes) {

                $orderDetailSizes = $orderDetail->order_detail_quantities->pluck('size_id')->unique();
                $missingSizes = $orderDetailQuantitySizes->pluck('size_id')->unique()->values()->diff($orderDetailSizes)->values();

                $quantities = collect($orderDetail->order_detail_quantities)->mapWithKeys(function ($quantity) {
                    return [$quantity['size']->id => [
                        'order_detail_id' => $quantity['order_detail_id'],
                        'quantity' => $quantity['quantity'],
                    ]];
                });

                $missingSizes->each(function ($missingSize) use ($quantities, $orderDetail) {
                    $quantities[$missingSize] = [
                        'order_detail_id' => $orderDetail->id,
                        'quantity' => 0,
                    ];
                });

                return [
                    'id' => $orderDetail->id,
                    'order' => $orderDetail->order,
                    'product' => $orderDetail->product,
                    'color' => $orderDetail->color,
                    'tone' => $orderDetail->tone,
                    'price' => $orderDetail->price,
                    'seller_date' => $orderDetail->seller_date,
                    'seller_observation' => $orderDetail->seller_observation,
                    'status' => $orderDetail->status,
                    'quantities' => $quantities,
                ];
            });

            return $this->successResponse(
                [
                    'order' => Order::findOrFail($request->input('order_id')),
                    'orderDetails' => $orderDetails,
                    'sizes' => $orderDetailQuantitySizes->pluck('size')->unique()->values()
                ],
                $this->getMessage('Success'),
                200
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function create(OrderWalletDetailCreateRequest $request)
    {
        try {
            if($request->filled('product_id') && $request->filled('color_id') && $request->filled('tone_id')) {
                return $this->successResponse(
                    Inventory::with('product', 'warehouse', 'color', 'tone', 'size')
                        ->whereHas('product', fn($subQuery) => $subQuery->where('id', $request->input('product_id')))
                        ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                        ->whereHas('color', fn($subQuery) => $subQuery->where('id', $request->input('color_id')))
                        ->whereHas('tone', fn($subQuery) => $subQuery->where('id', $request->input('tone_id')))
                        ->get(),
                    'Inventario encontrado con exito.',
                    200
                );
            }

            if($request->filled('product_id')) {
                return $this->successResponse(
                    Product::with('colors_tones.color', 'colors_tones.tone')->findOrFail($request->input('product_id')),
                    'Colores, tonos y tallas del producto encontrados con exito.',
                    200
                );
            }

            return $this->successResponse(
                Product::with('inventories.warehouse')
                    ->whereHas('inventories.warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                    ->get(),
                'Ingrese los datos para hacer la validacion y registro.',
                204
            );
        } catch (Exception $e) {
            // Devolver una respuesta de error en caso de excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function store(OrderWalletDetailStoreRequest $request)
    {
        try {
            $orderDetail = new OrderDetail();
            $orderDetail->order_id = $request->input('order_id');
            $orderDetail->product_id = $request->input('product_id');
            $orderDetail->color_id = $request->input('color_id');
            $orderDetail->tone_id = $request->input('tone_id');
            $orderDetail->price = $request->input('price');
            $orderDetail->seller_date = Carbon::now()->format('Y-m-d H:i:s');
            $orderDetail->seller_observation = $request->input('seller_observation');
            $orderDetail->save();

            collect($request->order_detail_quantities)->map(function ($orderDetailQuantity) use ($orderDetail) {
                $orderDetailQuantity = (object) $orderDetailQuantity;
                $orderDetailQuantityNew = new OrderDetailQuantity();
                $orderDetailQuantityNew->order_detail_id = $orderDetail->id;
                $orderDetailQuantityNew->size_id = $orderDetailQuantity->size_id;
                $orderDetailQuantityNew->quantity = $orderDetailQuantity->quantity;
                $orderDetailQuantityNew->save();
            });

            return $this->successResponse(
                $orderDetail,
                'El detalle del pedido fue registrado por el asesor exitosamente.',
                201
            );
        } catch (ModelNotFoundException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            // Devolver una respuesta de error en caso de excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function edit(OrderWalletDetailCreateRequest $request, $id)
    {
        try {
            if($request->filled('product_id') && $request->filled('color_id') && $request->filled('tone_id')) {
                return $this->successResponse(
                    Inventory::with('product', 'warehouse', 'color', 'tone', 'size')
                        ->whereHas('product', fn($subQuery) => $subQuery->where('id', $request->input('product_id')))
                        ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                        ->whereHas('color', fn($subQuery) => $subQuery->where('id', $request->input('color_id')))
                        ->whereHas('tone', fn($subQuery) => $subQuery->where('id', $request->input('tone_id')))
                        ->get(),
                    'Inventario encontrado con exito.',
                    200
                );
            }

            if($request->filled('product_id')) {
                return $this->successResponse(
                    Product::with('colors_tones.color', 'colors_tones.tone')->findOrFail($request->input('product_id')),
                    'Colores, tonos y tallas del producto encontrados con exito.',
                    200
                );
            }

            return $this->successResponse(
                [
                    'products' => Product::with('inventories.warehouse')
                    ->whereHas('inventories.warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                    ->get(),
                    'orderDetail' => OrderDetail::with('order_detail_quantities')->findOrFail($id)
                ],
                'El detalle del pedido fue encontrado exitosamente.',
                204
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function update(OrderWalletDetailUpdateRequest $request, $id)
    {
        try {
            $orderDetail = OrderDetail::with('order_detail_quantities')->findOrFail($id);
            $orderDetail->order_id = $request->input('order_id');
            $orderDetail->product_id = $request->input('product_id');
            $orderDetail->color_id = $request->input('color_id');
            $orderDetail->tone_id = $request->input('tone_id');
            $orderDetail->seller_observation = $request->input('seller_observation');
            $orderDetail->save();

            $orderDetail->order_detail_quantities()->delete();

            collect($request->order_detail_quantities)->map(function ($orderDetailQuantity) use ($orderDetail) {
                $orderDetailQuantity = (object) $orderDetailQuantity;
                $orderDetailQuantityNew = new OrderDetailQuantity();
                $orderDetailQuantityNew->order_detail_id = $orderDetail->id;
                $orderDetailQuantityNew->size_id = $orderDetailQuantity->size_id;
                $orderDetailQuantityNew->quantity = $orderDetailQuantity->quantity;
                $orderDetailQuantityNew->save();
            });

            $orderDetail->load('order_detail_quantities');

            if($orderDetail->status == 'Agotado') {
                $boolean = true;
                foreach($orderDetail->order_detail_quantities as $quantity) {
                    $inventory = Inventory::with('warehouse')
                        ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                        ->where('product_id', $orderDetail->product_id)
                        ->where('size_id', $quantity->size_id)
                        ->where('color_id', $orderDetail->color_id)
                        ->where('tone_id', $orderDetail->tone_id)
                        ->first();

                    if($inventory->quantity < $quantity->quantity) {
                        $boolean = false;
                        break;
                    }
                }

                if($boolean){
                    foreach($orderDetail->order_detail_quantities as $quantity) {
                        $inventory = Inventory::with('warehouse')
                            ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                            ->where('product_id', $orderDetail->product_id)
                            ->where('size_id', $quantity->size_id)
                            ->where('color_id', $orderDetail->color_id)
                            ->where('tone_id', $orderDetail->tone_id)
                            ->first();

                        $inventory->quantity -= $quantity->quantity;
                        $inventory->save();
                    }

                    $orderDetail->status = 'Aprobado';
                } else {
                    $orderDetail->status = 'Agotado';
                }

                $orderDetail->save();
            }

            return $this->successResponse(
                $orderDetail,
                'El detalle del pedido fue actualizado por el asesor exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function approve(OrderWalletDetailApproveRequest $request)
    {
        try {
            $orderDetail = OrderDetail::with('order')->findOrFail($request->input('id'));
            $orderDetail->status = 'Aprobado';
            $orderDetail->save();

            DB::statement('CALL order_wallet_status(?)', [$orderDetail->order->id]);

            return $this->successResponse(
                $orderDetail,
                'El detalle del pedido fue aprobado por cartera exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function pending(OrderWalletDetailPendingRequest $request)
    {
        try {
            $orderDetail = OrderDetail::findOrFail($request->input('id'));
            $orderDetail->status = 'Pendiente';
            $orderDetail->save();

            DB::statement('CALL order_wallet_status(?)', [$orderDetail->order->id]);

            return $this->successResponse(
                $orderDetail,
                'El detalle del pedido fue pendiente por cartera exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function review(OrderWalletDetailReviewRequest $request)
    {
        try {
            $orderDetail = OrderDetail::with('order_detail_quantities')->findOrFail($request->input('id'));

            $boolean = true;
            foreach($orderDetail->order_detail_quantities as $quantity) {
                $inventory = Inventory::with('warehouse')
                    ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                    ->where('product_id', $orderDetail->product_id)
                    ->where('size_id', $quantity->size_id)
                    ->where('color_id', $orderDetail->color_id)
                    ->where('tone_id', $orderDetail->tone_id)
                    ->first();

                if($inventory->quantity < $quantity->quantity) {
                    $boolean = false;
                    break;
                }
            }

            if($boolean){
                foreach($orderDetail->order_detail_quantities as $quantity) {
                    $inventory = Inventory::with('warehouse')
                        ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                        ->where('product_id', $orderDetail->product_id)
                        ->where('size_id', $quantity->size_id)
                        ->where('color_id', $orderDetail->color_id)
                        ->where('tone_id', $orderDetail->tone_id)
                        ->first();

                    $inventory->quantity -= $quantity->quantity;
                    $inventory->save();
                }

                $orderDetail->status = 'Revision';
            } else {
                $orderDetail->status = 'Agotado';
            }

            $orderDetail->save();

            return $this->successResponse(
                $orderDetail,
                'El detalle del pedido fue pendiente por cartera exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function cancel(OrderWalletDetailCancelRequest $request)
    {
        try {
            $orderDetail = OrderDetail::findOrFail($request->input('id'));
            $orderDetail->status = 'Cancelado';
            $orderDetail->save();

            DB::statement('CALL order_wallet_status(?)', [$orderDetail->order->id]);

            return $this->successResponse(
                $orderDetail,
                'El detalle del pedido fue cancelado por cartera exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function decline(OrderWalletDetailDeclineRequest $request)
    {
        try {
            $orderDetail = OrderDetail::with('order_detail_quantities')->findOrFail($request->input('id'));

            foreach($orderDetail->order_detail_quantities as $quantity) {
                $inventory = Inventory::with('warehouse')
                    ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                    ->where('product_id', $orderDetail->product_id)
                    ->where('size_id', $quantity->size_id)
                    ->where('color_id', $orderDetail->color_id)
                    ->where('tone_id', $orderDetail->tone_id)
                    ->first();

                $inventory->quantity += $quantity->quantity;
                $inventory->save();
            }
            $orderDetail->status = 'Rechazado';
            $orderDetail->save();

            DB::statement('CALL order_wallet_status(?)', [$orderDetail->order->id]);

            return $this->successResponse(
                $orderDetail,
                'El detalle del pedido fue rechazado por cartera exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
