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
use App\Models\OrderPurchaseDetailReceivedQuantity;
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
use Illuminate\Support\Facades\Auth;

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

                $orderPurchaseDetailSizes = $orderPurchaseDetail->order_purchase_detail_request_quantities->pluck('size_id')->unique();
                $missingSizes = $orderPurchaseDetailQuantitySizes->pluck('size_id')->unique()->values()->diff($orderPurchaseDetailSizes)->values();

                $quantities = collect($orderPurchaseDetail->order_purchase_detail_request_quantities)->mapWithKeys(function ($quantity) {
                    return [$quantity['size']->id => [
                        'order_purchase_detail_id' => $quantity['order_purchase_detail_id'],
                        'quantity' => $quantity['quantity'],
                    ]];
                });

                $missingSizes->each(function ($missingSize) use ($quantities, $orderPurchaseDetail) {
                    $quantities[$missingSize] = [
                        'order_purchase_detail_id' => $orderPurchaseDetail->id,
                        'quantity' => 0,
                    ];
                });

                return [
                    'id' => $orderPurchaseDetail->id,
                    'orderPurchase' => $orderPurchaseDetail->order,
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
            $orderPurchaseDetails = OrderPurchaseDetail::with([
                    'order_purchase', 'order_purchase_detail_request_quantities.size',
                    'order_purchase_detail_request_quantities.order_purchase_detail_received_quantities',
                    'warehouse' => fn($query) => $query->withTrashed(),
                    'product' => fn($query) => $query->withTrashed(),
                    'color' => fn($query) => $query->withTrashed(),
                    'tone' => fn($query) => $query->withTrashed(),
                    'user' => fn($query) => $query->withTrashed()
                ])
                ->where('order_purchase_id', $request->input('order_purchase_id'))
                ->get();

            $orderPurchaseDetailQuantitySizes = OrderPurchaseDetailReceivedQuantity::with('order_purchase_detail', 'order_purchase_detail_request_quantity.size')
                ->whereHas('order_purchase_detail_request_quantity.order_purchase_detail', fn($subQuery) => $subQuery->where('order_purchase_id', $request->input('order_purchase_id')))
                ->get();

            $orderPurchaseDetails = $orderPurchaseDetails->map(function ($orderPurchaseDetail) use ($orderPurchaseDetailQuantitySizes) {
                $orderPurchaseDetailSizes = $orderPurchaseDetail->order_purchase_detail_request_quantities->pluck('size_id')->unique();
                $missingSizes = $orderPurchaseDetailQuantitySizes->pluck('order_purchase_detail_request_quantity')->pluck('size_id')->unique()->values()->diff($orderPurchaseDetailSizes)->values();
                $quantities = collect($orderPurchaseDetail->order_purchase_detail_received_quantities)->mapWithKeys(function ($quantity) {
                    return [$quantity['size']->id => [
                        'order_detail_id' => $quantity['order_detail_id'],
                        'quantity' => $quantity['quantity'],
                    ]];
                });

                $missingSizes->each(function ($missingSize) use ($quantities, $orderPurchaseDetail) {
                    $quantities[$missingSize] = [
                        'order_purchase_detail_id' => $orderPurchaseDetail->id,
                        'quantity' => 0,
                    ];
                });

                return [
                    'id' => $orderPurchaseDetail->id,
                    'orderPurchase' => $orderPurchaseDetail->order,
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
            $orderPurchaseDetail = new OrderPurchaseDetail();
            $orderPurchaseDetail->order_purchase_id = $request->input('order_purchase_id');
            $orderPurchaseDetail->warehouse_id = $request->input('warehouse_id');
            $orderPurchaseDetail->product_id = $request->input('product_id');
            $orderPurchaseDetail->color_id = $request->input('color_id');
            $orderPurchaseDetail->tone_id = $request->input('tone_id');
            $orderPurchaseDetail->price = $request->input('price');
            $orderPurchaseDetail->date = Carbon::now()->format('Y-m-d H:i:s');
            $orderPurchaseDetail->user_id = Auth::user()->id;
            $orderPurchaseDetail->observation = $request->input('observation');
            $orderPurchaseDetail->save();

            collect($request->order_purchase_detail_request_quantities)->map(function ($orderPurchaseDetailRequestQuantity) use ($orderPurchaseDetail) {
                $orderPurchaseDetailRequestQuantity = (object) $orderPurchaseDetailRequestQuantity;
                $orderPurchaseDetailRequestQuantityNew = new OrderPurchaseDetailRequestQuantity();
                $orderPurchaseDetailRequestQuantityNew->order_purchase_detail_id = $orderPurchaseDetail->id;
                $orderPurchaseDetailRequestQuantityNew->size_id = $orderPurchaseDetailRequestQuantity->size_id;
                $orderPurchaseDetailRequestQuantityNew->quantity = $orderPurchaseDetailRequestQuantity->quantity;
                $orderPurchaseDetailRequestQuantityNew->save();
            });

            return $this->successResponse(
                $orderPurchaseDetail,
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
            if($request->filled('product_id')) {
                return $this->successResponse(
                    Product::with('colors_tones.color', 'colors_tones.tone')->findOrFail($request->input('product_id')),
                    'Colores, tonos y tallas del producto encontrados con exito.',
                    200
                );
            }

            return $this->successResponse(
                [
                    'orderPurchaseDetail' => OrderPurchaseDetail::with('order_purchase_detail_request_quantities')->findOrFail($id),
                    'products' => Product::all(),
                    'warehouses' => Warehouse::all()
                ],
                'El detalle de la orden de compra fue encontrado exitosamente.',
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
            $orderPurchaseDetail = OrderPurchaseDetail::with('order_purchase_detail_request_quantities')->findOrFail($id);
            $orderPurchaseDetail->order_purchase_id = $request->input('order_purchase_id');
            $orderPurchaseDetail->warehouse_id = $request->input('warehouse_id');
            $orderPurchaseDetail->product_id = $request->input('product_id');
            $orderPurchaseDetail->color_id = $request->input('color_id');
            $orderPurchaseDetail->tone_id = $request->input('tone_id');
            $orderPurchaseDetail->price = $request->input('price');
            $orderPurchaseDetail->observation = $request->input('observation');
            $orderPurchaseDetail->save();

            /* $orderDetail->order_purchase_detail_request_quantities()->delete(); */

            collect($request->order_purchase_detail_request_quantities)->map(function ($orderPurchaseDetailRequestQuantity) use ($orderPurchaseDetail) {
                $orderPurchaseDetailRequestQuantity = (object) $orderPurchaseDetailRequestQuantity;
                $orderPurchaseDetailRequestQuantityNew = $orderPurchaseDetail->order_purchase_detail_request_quantities->where('order_purchase_detail_id', $orderPurchaseDetail->id)->where('size_id', $orderPurchaseDetailRequestQuantity->size_id)->first();
                if(!$orderPurchaseDetailRequestQuantityNew) {
                    $orderPurchaseDetailRequestQuantityNew = new OrderPurchaseDetailRequestQuantity();
                }
                $orderPurchaseDetailRequestQuantityNew->order_detail_id = $orderPurchaseDetail->id;
                $orderPurchaseDetailRequestQuantityNew->size_id = $orderPurchaseDetailRequestQuantity->size_id;
                $orderPurchaseDetailRequestQuantityNew->quantity = $orderPurchaseDetailRequestQuantity->quantity;
                $orderPurchaseDetailRequestQuantityNew->save();
            });

            return $this->successResponse(
                $orderPurchaseDetail,
                'El detalle de la orden de compra fue actualizado exitosamente.',
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
            $orderPurchaseDetail = OrderPurchaseDetail::findOrFail($request->input('id'));
            $orderPurchaseDetail->status = 'Pendiente';
            $orderPurchaseDetail->save();

            return $this->successResponse(
                $orderPurchaseDetail,
                'El detalle de la orden de compra fue pendiente exitosamente.',
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
            $orderPurchaseDetail = OrderPurchaseDetail::findOrFail($request->input('id'));
            $orderPurchaseDetail->status = 'Cancelado';
            $orderPurchaseDetail->save();

            return $this->successResponse(
                $orderPurchaseDetail,
                'El detalle de la orden de compra fue cancelado exitosamente.',
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
