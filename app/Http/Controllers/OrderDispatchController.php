<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderDispatch\OrderDispatchApproveRequest;
use App\Http\Requests\OrderDispatch\OrderDispatchCancelRequest;
use App\Http\Requests\OrderDispatch\OrderDispatchDeclineRequest;
use App\Http\Requests\OrderDispatch\OrderDispatchFilterQueryDetailsRequest;
use App\Http\Requests\OrderDispatch\OrderDispatchFilterQueryInventoriesRequest;
use App\Http\Requests\OrderDispatch\OrderDispatchIndexQueryRequest;
use App\Http\Requests\OrderDispatch\OrderDispatchStoreRequest;
use App\Http\Resources\OrderDispatch\OrderDispatchIndexQueryCollection;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderDetailQuantity;
use App\Models\OrderDispatch;
use App\Models\OrderDispatchDetail;
use App\Models\OrderDispatchDetailQuantity;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                ->get();

            $existingSizes = $inventories->pluck('size_id')->unique();
            $missingSizes = collect($request->input('size_ids'))->diff($existingSizes)->values();

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

    public function store(OrderDispatchStoreRequest $request)
    {
        try {
            $order_dispatch = OrderDispatch::where('order_id', $request->input('order_id'))->where('dispatch_status', 'Pendiente')->first();

            if(!$order_dispatch) {
                $order_dispatch = new OrderDispatch();
                $order_dispatch->order_id = $request->input('order_id');
                $order_dispatch->dispatch_user_id = Auth::user()->id;
                $order_dispatch->consecutive = DB::selectOne('CALL order_dispatches()')->consecutive;;
                $order_dispatch->save();
            }

            foreach($request->input('details') as $detail) {
                $order_dispatch_detail = new OrderDispatchDetail();
                $order_dispatch_detail->order_dispatch_id = $order_dispatch->id;
                $order_dispatch_detail->order_detail_id = $detail->id;
                $order_dispatch_detail->save();

                $orderDetail = OrderDetail::with('quantities')->findOrFail($detail->id);
                $orderDetail->status = 'Filtrado';
                $orderDetail->save();

                foreach($detail->quantities as $quantity) {
                    if(!is_null($quantity->id)) {
                        $orderDetailQuantity = $orderDetail->quantities()->findOrFail($quantity->id);

                        $inventory = Inventory::with('warehouse')
                            ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                            ->where('product_id', $orderDetail->product_id)
                            ->where('color_id', $orderDetail->color_id)
                            ->where('tone_id', $orderDetail->tone_id)
                            ->where('size_id', $orderDetailQuantity->size_id)
                            ->firstOrFail();

                        $inventory->quantity -= ($quantity->quantity - $orderDetailQuantity->quantity);

                        $order_dispatch_detail_quantity = new OrderDispatchDetailQuantity();
                        $order_dispatch_detail_quantity->order_dispatch_detail_id = $order_dispatch_detail->id;
                        $order_dispatch_detail_quantity->order_detail_quantity = $quantity->id;
                        $order_dispatch_detail_quantity->quantity = $quantity->quantity;
                        $order_dispatch_detail_quantity->save();
                    }
                }
            }

            DB::statement('CALL order_dispatch_status(?,?)', [$order_dispatch->id, $request->input('order_id')]);

            return $this->successResponse(
                '',
                'Los detalles del pedido fueron filtrados exitosamente.',
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

    public function approve(OrderDispatchApproveRequest $request)
    {
        try {
            $OrderDispatch = OrderDispatch::with('order', 'details.order_detail')->findOrFail($request->input('id'));

            foreach($OrderDispatch->details as $detail) {
                $detail->status = 'Aprobado';
                $detail->save();
            }

            $OrderDispatch->dispatch_status = 'Aprobado';
            $OrderDispatch->save();

            DB::statement('CALL order_dispatched_status(?)', [$OrderDispatch->order->id]);

            return $this->successResponse(
                $OrderDispatch,
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

    public function cancel(OrderDispatchCancelRequest $request)
    {
        try {
            $OrderDispatch = OrderDispatch::with('order.details.quantities', 'details.order_detail', 'details.quantities.order_detail_quantity')->findOrFail($request->input('id'));

            foreach($OrderDispatch->details as $detail) {
                $detail->status = 'Cancelado';
                $detail->save();
                foreach($detail->quantities as $quantity) {
                    $inventory = Inventory::with('warehouse')
                        ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                        ->where('product_id', $detail->order_detail->product_id)
                        ->where('size_id', $quantity->order_detail_quantity->size_id)
                        ->where('color_id', $detail->order_detail->color_id)
                        ->where('tone_id', $detail->order_detail->tone_id)
                        ->first();

                    $inventory->quantity += $quantity->quantity;
                    $inventory->save();
                }
            }

            foreach($OrderDispatch->order->details as $detail) {
                if($OrderDispatch->details->pluck('id')->contains($detail->id)) {
                    $boolean = true;
                    foreach($detail->quantities as $quantity) {
                        $inventory = Inventory::with('warehouse')
                            ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                            ->where('product_id', $detail->product_id)
                            ->where('size_id', $quantity->size_id)
                            ->where('color_id', $detail->color_id)
                            ->where('tone_id', $detail->tone_id)
                            ->first();

                        if($inventory->quantity < $quantity->quantity) {
                            $boolean = false;
                            break;
                        }
                    }

                    if($boolean){
                        foreach($detail->quantities as $quantity) {
                            $inventory = Inventory::with('warehouse')
                                ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                                ->where('product_id', $detail->product_id)
                                ->where('size_id', $quantity->size_id)
                                ->where('color_id', $detail->color_id)
                                ->where('tone_id', $detail->tone_id)
                                ->first();

                            $inventory->quantity -= $quantity->quantity;
                            $inventory->save();
                        }

                        $detail->status = 'Aprobado';
                    } else {
                        $detail->status = 'Agotado';
                    }

                    $detail->save();
                }
            }

            $OrderDispatch->dispatch_status = 'Cancelado';
            $OrderDispatch->save();

            DB::statement('CALL order_dispatched_status(?)', [$OrderDispatch->order->id]);

            return $this->successResponse(
                $OrderDispatch,
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

    public function decline(OrderDispatchDeclineRequest $request)
    {
        try {
            $OrderDispatch = OrderDispatch::with('order', 'details.order_detail', 'details.quantities.order_detail_quantity')->findOrFail($request->input('id'));

            foreach($OrderDispatch->details as $detail) {
                $detail->status = 'Rechazado';
                $detail->save();

                foreach($detail->quantities as $quantity) {
                    $inventory = Inventory::with('warehouse')
                        ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                        ->where('product_id', $detail->order_detail->product_id)
                        ->where('size_id', $quantity->order_detail_quantity->size_id)
                        ->where('color_id', $detail->order_detail->color_id)
                        ->where('tone_id', $detail->order_detail->tone_id)
                        ->first();

                    $inventory->quantity += $quantity->quantity;
                    $inventory->save();
                }

                $detail->order_detail->status = 'Rechazado';
                $detail->order_detail->save();
            }

            $OrderDispatch->dispatch_status = 'Rechazado';
            $OrderDispatch->save();

            DB::statement('CALL order_dispatched_status(?)', [$OrderDispatch->order->id]);

            return $this->successResponse(
                $OrderDispatch,
                'La orden de despacho fue aprobada para empacarse exitosamente.',
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
