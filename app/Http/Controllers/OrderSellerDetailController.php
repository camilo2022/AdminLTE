<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderSellerDetail\OrderSellerDetailCancelRequest;
use App\Http\Requests\OrderSellerDetail\OrderSellerDetailCreateRequest;
use App\Http\Requests\OrderSellerDetail\OrderSellerDetailIndexQueryRequest;
use App\Http\Requests\OrderSellerDetail\OrderSellerDetailPendingRequest;
use App\Http\Requests\OrderSellerDetail\OrderSellerDetailStoreRequest;
use App\Http\Requests\OrderSellerDetail\OrderSellerDetailUpdateRequest;
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

class OrderSellerDetailController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index($id)
    {
        try {
            $order = Order::with('seller_user', 'client.document_type', 'client_branch.country', 'client_branch.departament', 'client_branch.city')->findOrFail($id);
            return view('Dashboard.OrderSellerDetails.Index', compact('order'));
        } catch (ModelNotFoundException $e) {
            return back()->with('danger', 'Ocurrió un error al cargar el pedido: ' . $this->getMessage('ModelNotFoundException'));
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(OrderSellerDetailIndexQueryRequest $request)
    {
        try {
            $orderDetails = OrderDetail::with([
                    'order',
                    'quantities.size',
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

                $orderDetailSizes = $orderDetail->quantities->pluck('size_id')->unique();
                $missingSizes = $orderDetailQuantitySizes->pluck('size_id')->unique()->values()->diff($orderDetailSizes)->values();

                $quantities = collect($orderDetail->quantities)->mapWithKeys(function ($quantity) {
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

    public function create(OrderSellerDetailCreateRequest $request)
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

    public function store(OrderSellerDetailStoreRequest $request)
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

    public function edit(OrderSellerDetailCreateRequest $request, $id)
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
                    'orderDetail' => OrderDetail::with('quantities')->findOrFail($id)
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

    public function update(OrderSellerDetailUpdateRequest $request, $id)
    {
        try {
            $orderDetail = OrderDetail::findOrFail($id);
            $orderDetail->seller_observation = $request->input('seller_observation');
            $orderDetail->save();

            collect($request->order_detail_quantities)->map(function ($orderDetailQuantity) use ($orderDetail) {
                $orderDetailQuantity = (object) $orderDetailQuantity;
                $orderDetailQuantityNew = isset($orderDetailQuantity->id) ? OrderDetailQuantity::findOrFail($orderDetailQuantity->id) : new OrderDetailQuantity();
                $orderDetailQuantityNew->order_detail_id = $orderDetail->id;
                $orderDetailQuantityNew->size_id = $orderDetailQuantity->size_id;
                $orderDetailQuantityNew->quantity = $orderDetailQuantity->quantity;
                $orderDetailQuantityNew->save();
            });

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

    public function pending(OrderSellerDetailPendingRequest $request)
    {
        try {
            $orderDetail = OrderDetail::findOrFail($request->input('id'));
            $orderDetail->status = 'Pendiente';
            $orderDetail->save();

            return $this->successResponse(
                $orderDetail,
                'El detalle del pedido fue pendiente por el asesor exitosamente.',
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

    public function cancel(OrderSellerDetailCancelRequest $request)
    {
        try {
            $orderDetail = OrderDetail::findOrFail($request->input('id'));
            $orderDetail->status = 'Cancelado';
            $orderDetail->save();

            DB::statement('CALL order_seller_status(?)', [$orderDetail->order->id]);

            return $this->successResponse(
                $orderDetail,
                'El detalle del pedido fue cancelado por el asesor exitosamente.',
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
