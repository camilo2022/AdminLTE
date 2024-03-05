<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderDispatchDetail\OrderDispatchDetailCancelRequest;
use App\Http\Requests\OrderDispatchDetail\OrderDispatchDetailDeclineRequest;
use App\Http\Requests\OrderDispatchDetail\OrderDispatchDetailIndexQueryRequest;
use App\Http\Requests\OrderDispatchDetail\OrderDispatchDetailPendingRequest;
use App\Models\Inventory;
use App\Models\OrderDispatch;
use App\Models\OrderDispatchDetail;
use App\Models\OrderDispatchDetailQuantity;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class OrderDispatchDetailController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index($id)
    {
        try {
            $orderDispatch = OrderDispatch::with('order.sale_channel', 'order.seller_user', 'order.client.document_type', 'order.client_branch.country', 'order.client_branch.departament', 'order.client_branch.city')->findOrFail($id);
            return view('Dashboard.OrderDispatchDetails.Index', compact('orderDispatch'));
        } catch (ModelNotFoundException $e) {
            return back()->with('danger', 'Ocurrió un error al cargar el pedido: ' . $this->getMessage('ModelNotFoundException'));
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(OrderDispatchDetailIndexQueryRequest $request)
    {
        try {
            $orderDispatchDetails = OrderDispatchDetail::with([
                    'quantities',
                    'order_dispatch.order',
                    'order_detail.quantities.size',
                    'order_detail.product' => fn($query) => $query->withTrashed(),
                    'order_detail.color' => fn($query) => $query->withTrashed(),
                    'order_detail.tone' => fn($query) => $query->withTrashed(),
                    'order_detail.wallet_user' => fn($query) => $query->withTrashed(),
                    'order_detail.dispatched_user' => fn($query) => $query->withTrashed(),
                ])
                ->where('order_dispatch_id', $request->input('order_dispatch_id'))
                ->get();

            $orderDetailQuantitySizes = OrderDispatchDetailQuantity::with('order_dispatch_detail', 'order_detail_quantity.size')
                ->whereHas('order_dispatch_detail', fn($subQuery) => $subQuery->where('order_dispatch_id', $request->input('order_dispatch_id')))
                ->get();

            $orderDispatchDetails = $orderDispatchDetails->map(function ($orderDispatchDetail) use ($orderDetailQuantitySizes) {

                $orderDetailSizes = $orderDispatchDetail->order_detail->quantities->pluck('size_id')->unique();
                $missingSizes = $orderDetailQuantitySizes->pluck('order_detail_quantity')->pluck('size_id')->unique()->values()->diff($orderDetailSizes)->values();

                $quantities = collect($orderDispatchDetail->quantities)->mapWithKeys(function ($quantity) {
                    return [$quantity['size']->id => [
                        'order_dispatch_detail_id' => $quantity['order_dispatch_detail_id'],
                        'quantity' => $quantity['quantity'],
                    ]];
                });

                $missingSizes->each(function ($missingSize) use ($quantities, $orderDispatchDetail) {
                    $quantities[$missingSize] = [
                        'order_dispatch_detail_id' => $orderDispatchDetail->id,
                        'quantity' => 0,
                    ];
                });

                return [
                    'id' => $orderDispatchDetail->id,
                    'order_dispatch' => $orderDispatchDetail->order_dispatch,
                    'product' => $orderDispatchDetail->order_detail->product,
                    'color' => $orderDispatchDetail->order_detail->color,
                    'tone' => $orderDispatchDetail->order_detail->tone,
                    'price' => $orderDispatchDetail->order_detail->price,
                    'seller_date' => $orderDispatchDetail->order_detail->seller_date,
                    'seller_observation' => $orderDispatchDetail->order_detail->seller_observation,
                    'status' => $orderDispatchDetail->status,
                    'quantities' => $quantities,
                ];
            });

            return $this->successResponse(
                [
                    'orderDispatch' => OrderDispatch::findOrFail($request->input('order_dispatch_id')),
                    'orderDispatchDetails' => $orderDispatchDetails,
                    'sizes' => $orderDetailQuantitySizes->pluck('order_detail_quantity')->pluck('size')->unique()->values()
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

    public function pending(OrderDispatchDetailPendingRequest $request)
    {
        try {
            $orderDispatchDetail = OrderDispatchDetail::with('order_dispatch.order', 'quantities.order_detail_quantity', 'order_detail.quantities')->findOrFail($request->input('id'));

            $boolean = true;
            foreach($orderDispatchDetail->quantities as $quantity) {
                $inventory = Inventory::with('warehouse')
                    ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                    ->where('product_id', $orderDispatchDetail->order_detail->product_id)
                    ->where('size_id', $quantity->order_detail_quantity->size_id)
                    ->where('color_id', $orderDispatchDetail->order_detail->color_id)
                    ->where('tone_id', $orderDispatchDetail->order_detail->tone_id)
                    ->first();

                if(($inventory->quantity + $quantity->order_detail_quantity->quantity) < $quantity->quantity) {
                    $boolean = false;
                    break;
                }
            }

            foreach($orderDispatchDetail->quantities as $quantity) {
                if($boolean){
                    $inventory = Inventory::with('warehouse')
                        ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                        ->where('product_id', $orderDispatchDetail->order_detail->product_id)
                        ->where('size_id', $quantity->order_detail_quantity->size_id)
                        ->where('color_id', $orderDispatchDetail->order_detail->color_id)
                        ->where('tone_id', $orderDispatchDetail->order_detail->tone_id)
                        ->first();

                    $inventory->quantity -= ($quantity->order_detail_quantity->quantity + $quantity->quantity);
                    $inventory->save();
                } else {
                    $quantity->order_detail_quantity->quantity = $quantity->quantity;
                    $quantity->order_detail_quantity->save();
                }
            }

            $orderDispatchDetail->status = 'Pendiente';
            $orderDispatchDetail->save();

            DB::statement('CALL order_dispatched_status(?)', [$orderDispatchDetail->order_dispatch->order->id]);

            return $this->successResponse(
                $orderDispatchDetail,
                'La orden de despacho fue aprobada exitosamente.',
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

    public function cancel(OrderDispatchDetailCancelRequest $request)
    {
        try {
            $orderDispatchDetail = OrderDispatchDetail::with('order_dispatch.order', 'order_detail', 'quantities.order_detail_quantity')->findOrFail($request->input('id'));

            $orderDispatchDetail->status = 'Cancelado';
            $orderDispatchDetail->save();

            foreach($orderDispatchDetail->quantities as $quantity) {
                $inventory = Inventory::with('warehouse')
                    ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                    ->where('product_id', $orderDispatchDetail->order_detail->product_id)
                    ->where('size_id', $quantity->order_detail_quantity->size_id)
                    ->where('color_id', $orderDispatchDetail->order_detail->color_id)
                    ->where('tone_id', $orderDispatchDetail->order_detail->tone_id)
                    ->first();

                $inventory->quantity += $quantity->quantity;
                $inventory->save();
            }

            $boolean = true;
            foreach($orderDispatchDetail->quantities as $quantity) {
                $inventory = Inventory::with('warehouse')
                    ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                    ->where('product_id', $orderDispatchDetail->order_detail->product_id)
                    ->where('size_id', $quantity->order_detail_quantity->size_id)
                    ->where('color_id', $orderDispatchDetail->order_detail->color_id)
                    ->where('tone_id', $orderDispatchDetail->order_detail->tone_id)
                    ->first();

                if(($inventory->quantity + $quantity->order_detail_quantity->quantity) < $quantity->quantity) {
                    $boolean = false;
                    break;
                }
            }

            if($boolean){
                foreach($orderDispatchDetail->quantities as $quantity) {
                    $inventory = Inventory::with('warehouse')
                        ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                        ->where('product_id', $orderDispatchDetail->order_detail->product_id)
                        ->where('size_id', $quantity->order_detail_quantity->size_id)
                        ->where('color_id', $orderDispatchDetail->order_detail->color_id)
                        ->where('tone_id', $orderDispatchDetail->order_detail->tone_id)
                        ->first();

                    $inventory->quantity -= ($quantity->order_detail_quantity->quantity + $quantity->quantity);
                    $inventory->save();
                }

                $orderDispatchDetail->order_detail->status = 'Aprobado';
            } else{
                $orderDispatchDetail->order_detail->status = 'Agotado';
            }

            $orderDispatchDetail->order_detail->save();

            DB::statement('CALL order_dispatched_status(?)', [$orderDispatchDetail->order_dispatch->order->id]);

            return $this->successResponse(
                $orderDispatchDetail,
                'La orden de despacho fue cancelada exitosamente.',
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

    public function decline(OrderDispatchDetailDeclineRequest $request)
    {
        try {
            $orderDispatchDetail = OrderDispatchDetail::with('order_dispatch.order', 'order_detail', 'quantities.order_detail_quantity')->findOrFail($request->input('id'));

            $orderDispatchDetail->status = 'Rechazado';
            $orderDispatchDetail->save();

            foreach($orderDispatchDetail->quantities as $quantity) {
                $inventory = Inventory::with('warehouse')
                    ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                    ->where('product_id', $orderDispatchDetail->order_detail->product_id)
                    ->where('size_id', $quantity->order_detail_quantity->size_id)
                    ->where('color_id', $orderDispatchDetail->order_detail->color_id)
                    ->where('tone_id', $orderDispatchDetail->order_detail->tone_id)
                    ->first();

                $inventory->quantity += $quantity->quantity;
                $inventory->save();
            }

            $orderDispatchDetail->order_detail->status = 'Rechazado';
            $orderDispatchDetail->order_detail->save();

            DB::statement('CALL order_dispatched_status(?)', [$orderDispatchDetail->order_dispatch->order->id]);

            return $this->successResponse(
                $orderDispatchDetail,
                'La orden de despacho fue cancelada exitosamente.',
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
