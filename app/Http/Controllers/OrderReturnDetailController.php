<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderReturnDetail\OrderReturnDetailIndexQueryRequest;
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
}
