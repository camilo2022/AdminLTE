<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderDispatch\OrderDispatchFilterQueryDetailsRequest;
use App\Http\Requests\OrderDispatch\OrderDispatchFilterQueryInventoriesRequest;
use App\Http\Requests\OrderDispatch\OrderDispatchIndexQueryRequest;
use App\Http\Resources\OrderDispatch\OrderDispatchIndexQueryCollection;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderDetailQuantity;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class OrderDispatchController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.OrderDispatches.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(OrderDispatchIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $orders = Order::with(['details', 'order_dispatches',
                    'client' => fn($query) => $query->withTrashed(),
                    'client.country', 'client.departament', 'client.city',
                    'client_branch' => fn($query) => $query->withTrashed(),
                    'client_branch.country', 'client_branch.departament', 'client_branch.city',
                    'seller_user' => fn($query) => $query->withTrashed(),
                    'wallet_user' => fn($query) => $query->withTrashed(),
                    'correria' => fn($query) => $query->withTrashed()
                ])
                ->when($request->filled('search'),
                    function ($query) use ($request) {
                        $query->search($request->input('search'));
                    }
                )
                ->when($request->filled('start_date') && $request->filled('end_date'),
                    function ($query) use ($start_date, $end_date) {
                        $query->filterByDate($start_date, $end_date);
                    }
                )
                ->whereIn('seller_status', ['Aprobado', 'Parcialmente Aprobado'])
                ->whereHas('details', function ($query) {
                    $query->where('status', 'Aprobado');
                })
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new OrderDispatchIndexQueryCollection($orders),
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

    public function filter($id)
    {
        try {
            $order = Order::with('sale_channel', 'seller_user', 'client.document_type', 'client_branch.country', 'client_branch.departament', 'client_branch.city')->findOrFail($id);
            return view('Dashboard.OrderDispatches.Filter', compact('order'));
        } catch (ModelNotFoundException $e) {
            return back()->with('danger', 'Ocurrió un error al cargar el pedido: ' . $this->getMessage('ModelNotFoundException'));
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function filterQueryDetails(OrderDispatchFilterQueryDetailsRequest $request)
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
                ->where('status', 'Aprobado')
                ->get();

            $orderDetailQuantitySizes = OrderDetailQuantity::with('order_detail', 'size')
                ->whereHas('order_detail', fn($subQuery) => $subQuery->where('order_id', $request->input('order_id')))
                ->get();

            $orderDetails = $orderDetails->map(function ($orderDetail) use ($orderDetailQuantitySizes) {

                $orderDetailSizes = $orderDetail->quantities->pluck('size_id')->unique();
                $missingSizes = $orderDetailQuantitySizes->pluck('size_id')->unique()->values()->diff($orderDetailSizes)->values();

                $quantities = collect($orderDetail->quantities)->mapWithKeys(function ($quantity) {
                    return [$quantity['size']->id => [
                        'id' => $quantity['id'],
                        'order_detail_id' => $quantity['order_detail_id'],
                        'quantity' => $quantity['quantity'],
                    ]];
                });

                $missingSizes->each(function ($missingSize) use ($quantities, $orderDetail) {
                    $quantities[$missingSize] = [
                        'id' => null,
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

    public function filterQueryInventories(OrderDispatchFilterQueryInventoriesRequest $request)
    {
        try {
            $inventories = Inventory::with('warehouse')
                ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                ->where('product_id', $request->input('product_id'))
                ->where('color_id', $request->input('color_id'))
                ->where('tone_id', $request->input('tone_id'))
                ->whereIn('size_id', collect($request->input('size_ids'))->pluck('id'))
                ->get();

            $existingSizes = $inventories->pluck('size_id')->unique();
            $missingSizes = collect($request->input('size_ids'))->pluck('id')->diff($existingSizes)->values();

            $inventories = $inventories->mapWithKeys(function ($inventory) {
                return [$inventory->size_id => [
                    'quantity' => $inventory->quantity
                ]];
            });

            $missingSizes->each(function ($missingSize) use ($inventories) {
                $inventories[$missingSize] = [
                    'quantity' => 0,
                ];
            });

            return $this->successResponse(
                $inventories,
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
