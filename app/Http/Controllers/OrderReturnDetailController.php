<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderReturnDetail\OrderReturnDetailCancelRequest;
use App\Http\Requests\OrderReturnDetail\OrderReturnDetailCreateRequest;
use App\Http\Requests\OrderReturnDetail\OrderReturnDetailIndexQueryRequest;
use App\Http\Requests\OrderReturnDetail\OrderReturnDetailStoreRequest;
use App\Http\Requests\OrderReturnDetail\OrderReturnDetailUpdateRequest;
use App\Models\OrderReturn;
use App\Models\OrderReturnDetail;
use App\Models\OrderReturnDetailQuantity;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
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
            $orderReturn = OrderReturn::with('order.sale_channel', 'order.seller_user', 'order.client.document_type', 'order.client_branch.country', 'order.client_branch.departament', 'order.client_branch.city', 'order.correria')->findOrFail($id);
            return view('Dashboard.OrderReturnDetails.Index', compact('orderReturn'));
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

            $orderReturnDetails = $orderReturnDetails->map(function ($orderReturnDetail) use ($orderReturnDetailQuantitySizes) {

                $orderDetailSizes = $orderReturnDetail->order_return_detail_quantities->pluck('order_detail_quantity')->pluck('size_id')->unique();
                $missingSizes = $orderReturnDetailQuantitySizes->pluck('order_detail_quantity')->pluck('size_id')->unique()->values()->diff($orderDetailSizes)->values();

                $quantities = collect($orderReturnDetail->order_return_detail_quantities)->mapWithKeys(function ($quantity) {
                    return [$quantity['order_detail_quantity']['size']->id => [
                        'order_detail_id' => $quantity['order_detail_quantity']['order_detail_id'],
                        'quantity' => $quantity['quantity'],
                    ]];
                });

                $missingSizes->each(function ($missingSize) use ($quantities, $orderReturnDetail) {
                    $quantities[$missingSize] = [
                        'order_detail_id' => $orderReturnDetail->id,
                        'quantity' => 0,
                    ];
                });

                return [
                    'id' => $orderReturnDetail->id,
                    'order_return' => $orderReturnDetail->order_return,
                    'product' => $orderReturnDetail->order_detail->product,
                    'color' => $orderReturnDetail->order_detail->color,
                    'tone' => $orderReturnDetail->order_detail->tone,
                    'observation' => $orderReturnDetail->observation,
                    'status' => $orderReturnDetail->status,
                    'quantities' => $quantities,
                ];
            });

            return $this->successResponse(
                [
                    'orderReturn' => OrderReturn::findOrFail($request->input('order_return_id')),
                    'orderReturnDetails' => $orderReturnDetails,
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
            if($request->filled('order_return_id') && $request->filled('product_id') && $request->filled('color_id') && $request->filled('tone_id')) {

                $orderDetail = OrderReturn::with('order.order_details.order_detail_quantities.size')
                    ->findOrFail($request->input('order_return_id'))->order->order_details
                    ->where('product_id', $request->input('product_id'))
                    ->where('color_id', $request->input('color_id'))
                    ->where('tone_id', $request->input('tone_id'))
                    ->whereIn('status', ['Despachado', 'Parcialmente Devuelto'])
                    ->first();

                $orderReturnDetailQuantities = OrderReturnDetail::with('order_return_detail_quantities.order_detail_quantity')
                    ->where('order_detail_id', $orderDetail->id)
                    ->whereIn('status', ['Pendiente', 'Aprobado'])
                    ->get()->pluck('order_return_detail_quantities');

                $orderDetailQuantities = OrderReturn::with('order.order_details.order_detail_quantities.size')
                    ->findOrFail($request->input('order_return_id'))->order->order_details
                    ->where('product_id', $request->input('product_id'))
                    ->where('color_id', $request->input('color_id'))
                    ->where('tone_id', $request->input('tone_id'))
                    ->whereIn('status', ['Despachado', 'Parcialmente Devuelto'])
                    ->first()->order_detail_quantities;
                
                foreach($orderDetailQuantities as $orderDetailQuantity) {
                    $orderDetailQuantity->quantity -= $orderReturnDetailQuantities->flatten()->where('order_detail_quantity.size_id', $orderDetailQuantity->size_id)->pluck('quantity')->sum();
                }

                return $this->successResponse(
                    $orderDetailQuantities,
                    'Unidades del detalle del pedido encontrado con exito.',
                    200
                );
            }

            if($request->filled('order_return_id') && $request->filled('product_id')) {
                return $this->successResponse(
                    OrderReturn::with('order.order_details.color', 'order.order_details.tone')
                        ->findOrFail($request->input('order_return_id'))->order->order_details
                        ->where('product_id', $request->input('product_id'))
                        ->whereIn('status', ['Despachado', 'Parcialmente Devuelto'])->values(),
                    'Colores y tonos del producto encontrados con exito.',
                    200
                );
            }

            return $this->successResponse(
                OrderReturn::with('order.order_details.product')
                    ->findOrFail($request->input('order_return_id'))
                    ->order->order_details->whereIn('status', ['Despachado', 'Parcialmente Devuelto'])->pluck('product')->unique()->values(),
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
            $orderReturnDetail->observation = $request->input('observation');
            $orderReturnDetail->save();

            collect($request->order_return_detail_quantities)->map(function ($orderReturnDetailQuantity) use ($orderReturnDetail) {
                $orderReturnDetailQuantity = (object) $orderReturnDetailQuantity;
                $orderReturnDetailQuantityNew = new OrderReturnDetailQuantity();
                $orderReturnDetailQuantityNew->order_return_detail_id = $orderReturnDetail->id;
                $orderReturnDetailQuantityNew->order_detail_quantity_id = $orderReturnDetailQuantity->order_detail_quantity_id;
                $orderReturnDetailQuantityNew->quantity = $orderReturnDetailQuantity->quantity;
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
            if($request->filled('order_return_id') && $request->filled('product_id') && $request->filled('color_id') && $request->filled('tone_id')) {

                $orderDetail = OrderReturn::with('order.order_details.order_detail_quantities.size')
                    ->findOrFail($request->input('order_return_id'))->order->order_details
                    ->where('product_id', $request->input('product_id'))
                    ->where('color_id', $request->input('color_id'))
                    ->where('tone_id', $request->input('tone_id'))
                    ->whereIn('status', ['Despachado', 'Parcialmente Devuelto'])
                    ->first();

                $orderReturnDetailQuantities = OrderReturnDetail::with('order_return_detail_quantities.order_detail_quantity')
                    ->where('order_detail_id', $orderDetail->id)
                    ->whereIn('status', ['Pendiente', 'Aprobado'])
                    ->get()->pluck('order_return_detail_quantities');

                $orderDetailQuantities = OrderReturn::with('order.order_details.order_detail_quantities.size')
                    ->findOrFail($request->input('order_return_id'))->order->order_details
                    ->where('product_id', $request->input('product_id'))
                    ->where('color_id', $request->input('color_id'))
                    ->where('tone_id', $request->input('tone_id'))
                    ->whereIn('status', ['Despachado', 'Parcialmente Devuelto'])
                    ->first()->order_detail_quantities;

                $orderReturnDetail = OrderReturnDetail::with('order_return_detail_quantities.order_detail_quantity')->findOrFail($id);
                
                foreach($orderDetailQuantities as $orderDetailQuantity) {
                    $orderDetailQuantity->quantity -= $orderReturnDetailQuantities->flatten()->where('order_detail_quantity.size_id', $orderDetailQuantity->size_id)->pluck('quantity')->sum() + $orderReturnDetail->order_detail_id == $orderDetail->id ? $orderReturnDetail->order_return_detail_quantities->where('order_detail_quantity.size_id', $orderDetailQuantity->size_id)->pluck('quantity')->sum() : 0;
                }

                return $this->successResponse(
                    $orderDetailQuantities,
                    'Unidades del detalle del pedido encontrado con exito.',
                    200
                );
            }

            if($request->filled('order_return_id') && $request->filled('product_id')) {
                return $this->successResponse(
                    OrderReturn::with('order.order_details.color', 'order.order_details.tone')
                        ->findOrFail($request->input('order_return_id'))->order->order_details
                        ->where('product_id', $request->input('product_id'))
                        ->whereIn('status', ['Despachado', 'Parcialmente Devuelto'])->values(),
                    'Colores y tonos del producto encontrados con exito.',
                    200
                );
            }

            return $this->successResponse(
                [
                    'products' => OrderReturn::with('order.order_details.product')
                        ->whereHas('order.order_details', fn($query) => $query->whereIn('status', ['Despachado', 'Parcialmente Devuelto']))
                        ->findOrFail($request->input('order_return_id'))->order->order_details->pluck('product')->unique()->values(),
                    'orderReturnDetail' => OrderReturnDetail::with('order_detail', 'order_return_detail_quantities.order_detail_quantity')->findOrFail($id)
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
            $orderReturnDetail->order_detail_id = $request->input('order_detail_id');
            $orderReturnDetail->observation = $request->input('observation');
            $orderReturnDetail->save();

            collect($request->order_return_detail_quantities)->map(function ($orderReturnDetailQuantity) use ($orderReturnDetail) {
                $orderReturnDetailQuantity = (object) $orderReturnDetailQuantity;
                $orderReturnDetailQuantityNew = $orderReturnDetail->order_return_detail_quantities->where('order_detail_quantity_id', $orderReturnDetail->order_detail_quantity_id)->first();
                if(!$orderReturnDetailQuantityNew) {
                    $orderReturnDetailQuantityNew = new OrderReturnDetailQuantity();
                }
                $orderReturnDetailQuantityNew->order_return_detail_id = $orderReturnDetail->id;
                $orderReturnDetailQuantityNew->order_detail_quantity_id = $orderReturnDetailQuantity->order_detail_quantity_id;
                $orderReturnDetailQuantityNew->quantity = $orderReturnDetailQuantity->quantity;
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
