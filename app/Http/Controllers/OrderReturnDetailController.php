<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderReturnDetail\OrderReturnDetailApproveRequest;
use App\Http\Requests\OrderReturnDetail\OrderReturnDetailCancelRequest;
use App\Http\Requests\OrderReturnDetail\OrderReturnDetailCreateRequest;
use App\Http\Requests\OrderReturnDetail\OrderReturnDetailIndexQueryRequest;
use App\Http\Requests\OrderReturnDetail\OrderReturnDetailPendingRequest;
use App\Http\Requests\OrderReturnDetail\OrderReturnDetailStoreRequest;
use App\Http\Requests\OrderReturnDetail\OrderReturnDetailUpdateRequest;
use App\Models\Inventory;
use App\Models\OrderDetail;
use App\Models\OrderReturn;
use App\Models\OrderReturnDetail;
use App\Models\OrderReturnDetailQuantity;
use App\Models\Warehouse;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class OrderReturnDetailController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index($id)
    {
        try {
            $order = OrderReturn::with('order.sale_channel', 'order.seller_user', 'order.client.document_type', 'order.client_branch.country', 'order.client_branch.departament', 'order.client_branch.city')->findOrFail($id);
            return view('Dashboard.OrderReturnDetails.Index', compact('order'));
        } catch (ModelNotFoundException $e) {
            return back()->with('danger', 'Ocurrió un error al cargar el pedido: ' . $this->getMessage('ModelNotFoundException'));
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(OrderReturnDetailIndexQueryRequest $request)
    {
        try {
            $orderReturnDetails = OrderReturnDetail::with([
                    'order_return.order', 'order_return_detail_quantities.order_detail_quantity.size',
                    'order_detail.product' => fn($query) => $query->withTrashed(),
                    'order_detail.color' => fn($query) => $query->withTrashed(),
                    'order_detail.tone' => fn($query) => $query->withTrashed(),
                    'order_detail.wallet_user' => fn($query) => $query->withTrashed(),
                    'order_detail.dispatched_user' => fn($query) => $query->withTrashed(),
                ])
                ->where('order_return_id', $request->input('order_return_id'))
                ->get();

            $orderReturnDetailQuantitySizes = OrderReturnDetailQuantity::with('order_return_detail.order_detail', 'order_detail_quantity.size')
                ->whereHas('order_return_detail', fn($subQuery) => $subQuery->where('order_return_id', $request->input('order_return_id')))
                ->get();

            $orderReturnDetails = $orderReturnDetails->map(function ($orderDetail) use ($orderReturnDetailQuantitySizes) {

                $orderDetailSizes = $orderDetail->order_detail_quantities->pluck('size_id')->unique();
                $missingSizes = $orderReturnDetailQuantitySizes->pluck('order_detail_quantity')->pluck('size_id')->unique()->values()->diff($orderDetailSizes)->values();

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
                    'order_return' => $orderDetail->order_return,
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
                    'orderReturn' => OrderReturn::findOrFail($request->input('order_id')),
                    'orderDetails' => $orderReturnDetails,
                    'sizes' => $orderReturnDetailQuantitySizes->pluck('order_detail_quantity')->pluck('size')->unique()->values()
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

    public function create(OrderReturnDetailCreateRequest $request)
    {
        try {
            if($request->filled('order_detail_id')) {
                return $this->successResponse(
                    OrderDetail::with('order_detail_quantities.size')->findOrFail($request->input('order_detail_id')),
                    'Unidades del detalle del pedido encontrado con exito.',
                    200
                );
            }

            if($request->filled('product_id') && $request->filled('order_return_id')) {
                return $this->successResponse(
                    OrderReturnDetail::with('order_detail.product', 'order_detail.color', 'order_detail.tone')
                        ->where('order_return_id', $request->input('order_return_id'))
                        ->whereHas('order_detail', function($query) use ($request) {
                            $query->where('product_id', $request->input('product_id'))
                                ->whereIn('status', ['Despachado', 'Parcialmente Devuelto']);
                        })
                        ->get(),
                    'Colores y tonos del producto encontrados con exito.',
                    200
                );
            }

            return $this->successResponse(
                OrderReturnDetail::with('order_detail.product')
                    ->where('order_return_id', $request->input('order_return_id'))
                    ->whereHas('order_detail', fn($query) => $query->whereIn('status', ['Despachado', 'Parcialmente Devuelto']))
                    ->get()->pluck('order_detail')->pluck('product')->unique()->all(),
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

    public function store(OrderReturnDetailStoreRequest $request)
    {
        try {
            $orderReturnDetail = new OrderReturnDetail();
            $orderReturnDetail->order_return_id = $request->input('order_return_id');
            $orderReturnDetail->order_detail_id = $request->input('order_detail_id');
            $orderReturnDetail->return_observation = $request->input('return_observation');
            $orderReturnDetail->save();

            collect($request->order_return_detail_quantities)->map(function ($orderReturnDetailQuantity) use ($orderReturnDetail) {
                $orderReturnDetailQuantity = (object) $orderReturnDetailQuantity;
                $orderReturnDetailQuantityNew = new OrderReturnDetailQuantity();
                $orderReturnDetailQuantityNew->order_return_detail = $orderReturnDetail->id;
                $orderReturnDetailQuantityNew->order_detail_quantity_id = $orderReturnDetail->order_detail_quantity_id;
                $orderReturnDetailQuantityNew->quantity = $orderReturnDetail->quantity;
                $orderReturnDetailQuantityNew->save();
            });

            return $this->successResponse(
                $orderReturnDetail,
                'El detalle de la orden de devolucion del pedido fue registrado exitosamente.',
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

    public function edit(OrderReturnDetailCreateRequest $request, $id)
    {
        try {
            if($request->filled('order_detail_id')) {
                return $this->successResponse(
                    OrderDetail::with('order_detail_quantities.size')->findOrFail($request->input('order_detail_id')),
                    'Unidades del detalle del pedido encontrado con exito.',
                    200
                );
            }

            if($request->filled('product_id') && $request->filled('order_return_id')) {
                return $this->successResponse(
                    OrderReturnDetail::with('order_detail.product', 'order_detail.color', 'order_detail.tone')
                        ->where('order_return_id', $request->input('order_return_id'))
                        ->whereHas('order_detail', function($query) use ($request) {
                            $query->where('product_id', $request->input('product_id'))
                                ->whereIn('status', ['Despachado', 'Parcialmente Devuelto']);
                        })
                        ->get(),
                    'Colores y tonos del producto encontrados con exito.',
                    200
                );
            }

            return $this->successResponse(
                [
                    'products' => OrderReturnDetail::with('order_detail.product')
                        ->where('order_return_id', $request->input('order_return_id'))
                        ->whereHas('order_detail', fn($query) => $query->whereIn('status', ['Despachado', 'Parcialmente Devuelto']))
                        ->get()->pluck('order_detail')->pluck('product')->unique()->all(),
                    'orderReturnDetail' => OrderReturnDetail::with('order_return_detail_quantities.order_detail_quantity')->findOrFail($id)
                ],
                'El detalle de la orden de devolucion del pedido fue encontrado exitosamente.',
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

    public function update(OrderReturnDetailUpdateRequest $request, $id)
    {
        try {
            $orderReturnDetail = OrderReturnDetail::with('order_return_detail_quantities')->findOrFail($id);
            $orderReturnDetail->order_return_id = $request->input('order_return_id');
            $orderReturnDetail->order_detail_id = $request->input('order_detail_id');
            $orderReturnDetail->return_observation = $request->input('return_observation');
            $orderReturnDetail->save();

            collect($request->order_return_detail_quantities)->map(function ($orderReturnDetailQuantity) use ($orderReturnDetail) {
                $orderReturnDetailQuantity = (object) $orderReturnDetailQuantity;
                $orderReturnDetailQuantityNew = OrderReturnDetailQuantity::where('order_detail_quantity_id', $orderReturnDetail->order_detail_quantity_id)->first();
                if(!$orderReturnDetailQuantityNew) {
                    $orderReturnDetailQuantityNew = new OrderReturnDetailQuantity();
                }
                $orderReturnDetailQuantityNew->order_return_detail = $orderReturnDetail->id;
                $orderReturnDetailQuantityNew->order_detail_quantity_id = $orderReturnDetail->order_detail_quantity_id;
                $orderReturnDetailQuantityNew->quantity = $orderReturnDetail->quantity;
                $orderReturnDetailQuantityNew->save();
            });

            return $this->successResponse(
                $orderReturnDetail,
                'El detalle de la orden de devolucion del pedido fue actualizado exitosamente.',
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

    public function approve(OrderReturnDetailApproveRequest $request)
    {
        try {
            $orderReturnDetail = OrderReturnDetail::with([
                'order_return_detail_quantities',
                'order_detail.order_detail_quantities',
                'order_return_detail_quantities.order_detail_quantity'
            ])
            ->findOrFail($request->input('id'));

            foreach($orderReturnDetail->order_detail_quantities as $quantity) {
                $inventory = Inventory::with('warehouse')
                    ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                    ->where('product_id', $orderReturnDetail->order_detail->product_id)
                    ->where('size_id', $quantity->order_detail_quantity->size_id)
                    ->where('color_id', $orderReturnDetail->order_detail->color_id)
                    ->where('tone_id', $orderReturnDetail->order_detail->tone_id)
                    ->first();

                if(!$inventory) {
                    $warehouse = Warehouse::where('to_discount', true)->first();
                    if($warehouse) {
                        $inventory = new Inventory();
                        $inventory->product_id = $orderReturnDetail->order_detail->product_id;
                        $inventory->size_id = $quantity->order_detail_quantity->size_id;
                        $inventory->warehouse_id = $warehouse->id;
                        $inventory->color_id = $orderReturnDetail->order_detail->color_id;
                        $inventory->tone_id = $orderReturnDetail->order_detail->tone_id;
                        $inventory->save();
                    }
                }

                $inventory->quantity += $quantity->quantity;
                $inventory->save();
            }

            $orderReturnDetail->order_detail->status = $orderReturnDetail->order_return_detail_quantities->pluck('quantity')->sum() == $orderReturnDetail->order_detail->order_detail_quantities->pluck('quantity')->sum() ? 'Devuelto' : 'Parcialmente Devuelto' ;
            $orderReturnDetail->order_detail->save();

            $orderReturnDetail->status = 'Aprobado';
            $orderReturnDetail->save();

            return $this->successResponse(
                $orderReturnDetail,
                'El detalle de la orden de devolucion del pedido fue aprobado exitosamente.',
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

    public function pending(OrderReturnDetailPendingRequest $request)
    {
        try {
            $orderReturnDetail = OrderReturnDetail::findOrFail($request->input('id'));
            $orderReturnDetail->status = 'Pendiente';
            $orderReturnDetail->save();

            return $this->successResponse(
                $orderReturnDetail,
                'El detalle de la orden de devolucion del pedido fue pendiente exitosamente.',
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

    public function cancel(OrderReturnDetailCancelRequest $request)
    {
        try {
            $orderReturnDetail = OrderReturnDetail::findOrFail($request->input('id'));
            $orderReturnDetail->status = 'Cancelado';
            $orderReturnDetail->save();

            return $this->successResponse(
                $orderReturnDetail,
                'El detalle de la orden de devolucion del pedido fue cancelado exitosamente.',
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
