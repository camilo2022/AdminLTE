<?php

namespace App\Http\Controllers;

use App\Models\OrderPurchaseDetail;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderPurchaseDetail\OrderPurchaseDetailCancelRequest;
use App\Http\Requests\OrderPurchaseDetail\OrderPurchaseDetailCreateRequest;
use App\Http\Requests\OrderPurchaseDetail\OrderPurchaseDetailIndexReceiveQueryRequest;
use App\Http\Requests\OrderPurchaseDetail\OrderPurchaseDetailIndexRequestQueryRequest;
use App\Http\Requests\OrderPurchaseDetail\OrderPurchaseDetailPendingRequest;
use App\Http\Requests\OrderPurchaseDetail\OrderPurchaseDetailStoreRequest;
use App\Http\Requests\OrderPurchaseDetail\OrderPurchaseDetailUpdateRequest;
use App\Models\OrderPurchase;
use App\Models\OrderPurchaseDetailRequestQuantity;
use App\Models\Product;
use App\Models\Warehouse;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class OrderPurchaseDetailController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index($id)
    {
        try {
            $orderPurchase = OrderPurchase::with([ 'workshop' => fn($query) => $query->withTrashed(), 'workshop.country', 'workshop.departament', 'workshop.city', 'purchase_user' => fn($query) => $query->withTrashed() ])->findOrFail($id);
            return view('Dashboard.OrderPurchaseDetails.Index', compact('orderPurchase'));
        } catch (ModelNotFoundException $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la orden de compra: ' . $this->getMessage('ModelNotFoundException'));
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexRequestQuery(OrderPurchaseDetailIndexRequestQueryRequest $request)
    {
        try {
            $orderPurchaseDetails = OrderPurchaseDetail::with([
                    'order_purchase', 'order_purchase_detail_request_quantities.size',
                    'warehouse' => fn($query) => $query->withTrashed(),
                    'product' => fn($query) => $query->withTrashed(),
                    'color' => fn($query) => $query->withTrashed(),
                    'tone' => fn($query) => $query->withTrashed(),
                    'user' => fn($query) => $query->withTrashed()
                ])
                ->where('order_purchase_id', $request->input('order_purchase_id'))
                ->get();

            $orderPurchaseDetailQuantitySizes = OrderPurchaseDetailRequestQuantity::with('order_purchase_detail', 'size')
                ->whereHas('order_purchase_detail', fn($subQuery) => $subQuery->where('order_purchase_id', $request->input('order_purchase_id')))
                ->get();

            $orderPurchaseDetails = $orderPurchaseDetails->map(function ($orderPurchaseDetail) use ($orderPurchaseDetailQuantitySizes) {

                $orderPurchaseDetailSizes = $orderPurchaseDetail->order_detail_quantities->pluck('size_id')->unique();
                $missingSizes = $orderPurchaseDetailQuantitySizes->pluck('size_id')->unique()->values()->diff($orderPurchaseDetailSizes)->values();

                $quantities = collect($orderPurchaseDetail->order_detail_quantities)->mapWithKeys(function ($quantity) {
                    return [$quantity['size']->id => [
                        'order_detail_id' => $quantity['order_detail_id'],
                        'quantity' => $quantity['quantity'],
                    ]];
                });

                $missingSizes->each(function ($missingSize) use ($quantities, $orderPurchaseDetail) {
                    $quantities[$missingSize] = [
                        'order_detail_id' => $orderPurchaseDetail->id,
                        'quantity' => 0,
                    ];
                });

                return [
                    'id' => $orderPurchaseDetail->id,
                    'order' => $orderPurchaseDetail->order,
                    'product' => $orderPurchaseDetail->product,
                    'color' => $orderPurchaseDetail->color,
                    'tone' => $orderPurchaseDetail->tone,
                    'price' => $orderPurchaseDetail->price,
                    'seller_date' => $orderPurchaseDetail->seller_date,
                    'seller_observation' => $orderPurchaseDetail->seller_observation,
                    'status' => $orderPurchaseDetail->status,
                    'quantities' => $quantities,
                ];
            });

            return $this->successResponse(
                [
                    'orderPurchase' => OrderPurchase::findOrFail($request->input('order_purchase_id')),
                    'orderPurchaseDetails' => $orderPurchaseDetails,
                    'sizes' => $orderPurchaseDetailQuantitySizes->pluck('size')->unique()->values()
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

    public function indexReceiveQuery(OrderPurchaseDetailIndexReceiveQueryRequest $request)
    {
        try {
            $orderDetails = OrderDetail::with([
                    'order', 'order_detail_quantities.size',
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
                    'order' => OrderPurchase::findOrFail($request->input('order_id')),
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

    public function create(OrderPurchaseDetailCreateRequest $request)
    {
        try {
            if($request->filled('product_id')) {
                return $this->successResponse(
                    Product::with('colors_tones.color', 'colors_tones.tone')->findOrFail($request->input('product_id')),
                    'Colores, tonos y tallas del producto encontrados con exito.',
                    200
                );
            }

            return $this->successResponse(
                [
                    'products' => Product::all(),
                    'warehouses' => Warehouse::all()
                ],
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

    public function store(OrderPurchaseDetailStoreRequest $request)
    {
        try {
            $orderDetail = new OrderPurchaseDetail();
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
                $orderDetailQuantityNew = new OrderPurchaseDetailRequestQuantity();
                $orderDetailQuantityNew->order_detail_id = $orderDetail->id;
                $orderDetailQuantityNew->size_id = $orderDetailQuantity->size_id;
                $orderDetailQuantityNew->quantity = $orderDetailQuantity->quantity;
                $orderDetailQuantityNew->save();
            });

            return $this->successResponse(
                $orderDetail,
                'El detalle de la orden de compra fue registrado exitosamente.',
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

    public function edit(OrderPurchaseDetailCreateRequest $request, $id)
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

    public function update(OrderPurchaseDetailUpdateRequest $request, $id)
    {
        try {
            $orderDetail = OrderDetail::with('order_detail_quantities')->findOrFail($id);
            $orderDetail->order_id = $request->input('order_id');
            $orderDetail->product_id = $request->input('product_id');
            $orderDetail->color_id = $request->input('color_id');
            $orderDetail->tone_id = $request->input('tone_id');
            $orderDetail->seller_observation = $request->input('seller_observation');
            $orderDetail->save();

            /* $orderDetail->order_detail_quantities()->delete(); */

            collect($request->order_detail_quantities)->map(function ($orderDetailQuantity) use ($orderDetail) {
                $orderDetailQuantity = (object) $orderDetailQuantity;
                $orderDetailQuantityNew = $orderDetail->order_detail_quantities->where('order_detail_id', $orderDetail->id)->where('size_id', $orderDetailQuantity->size_id)->first();
                if(!$orderDetailQuantityNew) {
                    $orderDetailQuantityNew = new OrderDetailQuantity();
                }
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

    public function pending(OrderPurchaseDetailPendingRequest $request)
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

    public function cancel(OrderPurchaseDetailCancelRequest $request)
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
