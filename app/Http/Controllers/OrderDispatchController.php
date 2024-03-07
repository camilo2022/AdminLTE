<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderDispatch\OrderDispatchApproveRequest;
use App\Http\Requests\OrderDispatch\OrderDispatchCancelRequest;
use App\Http\Requests\OrderDispatch\OrderDispatchDeclineRequest;
use App\Http\Requests\OrderDispatch\OrderDispatchFilterQueryDetailsRequest;
use App\Http\Requests\OrderDispatch\OrderDispatchFilterQueryInventoriesRequest;
use App\Http\Requests\OrderDispatch\OrderDispatchIndexQueryRequest;
use App\Http\Requests\OrderDispatch\OrderDispatchPdfRequest;
use App\Http\Requests\OrderDispatch\OrderDispatchPendingRequest;
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
            $orders = Order::with(['order_dispatches.dispatch_user',
                    'details' => fn($query) => $query->where('status', 'Aprobado'),
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
                ->where(function ($query) {
                    $query->whereIn('seller_status', ['Aprobado'])
                        ->orWhereIn('wallet_status', ['Aprobado', 'Parcialmente Aprobado'])
                        ->orWhereIn('dispatched_status', ['Pendiente', 'Parcialmente Aprobado', 'Aprobado', 'Parcialmente Despachado']);
                })
                ->where(function ($query) {
                    $query->whereHas('details', function ($query) {
                        $query->where('status', 'Aprobado');
                    })
                    ->orWhereHas('order_dispatches', function ($query) {
                        $query->whereIn('dispatch_status', ['Pendiente', 'Aprobado', 'Empacado', 'Despachado']);
                    });
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
                $detail = (object) $detail;
                $order_dispatch_detail = new OrderDispatchDetail();
                $order_dispatch_detail->order_dispatch_id = $order_dispatch->id;
                $order_dispatch_detail->order_detail_id = $detail->id;
                $order_dispatch_detail->save();

                $orderDetail = OrderDetail::with('quantities')->findOrFail($detail->id);
                $orderDetail->status = 'Filtrado';
                $orderDetail->save();

                foreach($detail->quantities as $quantity) {
                    $quantity = (object) $quantity;
                    if(!is_null($quantity->id)) {
                        $orderDetailQuantity = $orderDetail->quantities()->findOrFail($quantity->id);

                        $inventory = Inventory::with('warehouse')
                            ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                            ->where('product_id', $orderDetail->product_id)
                            ->where('color_id', $orderDetail->color_id)
                            ->where('tone_id', $orderDetail->tone_id)
                            ->where('size_id', $orderDetailQuantity->size_id)
                            ->firstOrFail();

                        $inventory->quantity = ($inventory->quantity + $orderDetailQuantity->quantity) - $quantity->quantity;
                        $inventory->save();

                        $order_dispatch_detail_quantity = new OrderDispatchDetailQuantity();
                        $order_dispatch_detail_quantity->order_dispatch_detail_id = $order_dispatch_detail->id;
                        $order_dispatch_detail_quantity->order_detail_quantity_id = $quantity->id;
                        $order_dispatch_detail_quantity->quantity = $quantity->quantity;
                        $order_dispatch_detail_quantity->save();
                    }
                }
            }

            DB::statement('CALL order_dispatch_status(?,?)', [$order_dispatch->id, $request->input('order_id')]);

            return $this->successResponse(
                '',
                'Los detalles del pedido fueron filtrados exitosamente.',
                201
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

    public function show($id)
    {
        try {
            $order = Order::with('order_dispatches.details.order_detail', 'order_dispatches.details.quantities.order_detail_quantity')->findOrFail($id);
            return view('Dashboard.OrderDispatches.Show', compact('order'));
        } catch (ModelNotFoundException $e) {
            return back()->with('danger', 'Ocurrió un error al cargar el pedido: ' . $this->getMessage('ModelNotFoundException'));
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function pending(OrderDispatchPendingRequest $request)
    {
        try {
            $orderDispatch = OrderDispatch::with('order', 'order_dispatch_details.order_detail')->findOrFail($request->input('id'));

            foreach($orderDispatch->order_dispatch_details as $detail) {
                $detail->status = 'Pendiente';
                $detail->save();
            }

            $orderDispatch->dispatch_status = 'Pendiente';
            $orderDispatch->save();

            DB::statement('CALL order_dispatched_status(?)', [$orderDispatch->order->id]);
            DB::statement('CALL order_dispatch_status(?,?)', [$orderDispatch->id, $orderDispatch->order->id]);

            return $this->successResponse(
                $orderDispatch,
                'La orden de despacho fue pendiente exitosamente.',
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

    public function approve(OrderDispatchApproveRequest $request)
    {
        try {
            $orderDispatch = OrderDispatch::with('order', 'order_dispatch_details.order_detail')->findOrFail($request->input('id'));

            foreach($orderDispatch->order_dispatch_details as $detail) {
                $detail->status = 'Aprobado';
                $detail->save();
            }

            $orderDispatch->dispatch_status = 'Aprobado';
            $orderDispatch->save();

            DB::statement('CALL order_dispatched_status(?)', [$orderDispatch->order->id]);
            DB::statement('CALL order_dispatch_status(?,?)', [$orderDispatch->id, $orderDispatch->order->id]);

            return $this->successResponse(
                $orderDispatch,
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
            $orderDispatch = OrderDispatch::with('order.details.quantities', 'order_dispatch_details.order_detail', 'order_dispatch_details.order_dispatch_detail_quantities.order_detail_quantity')->findOrFail($request->input('id'));

            foreach($orderDispatch->details as $detail) {
                $detail->status = 'Cancelado';
                $detail->save();
                foreach($detail->order_dispatch_detail_quantities as $quantity) {
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

            foreach($orderDispatch->order->details as $detail) {
                if($orderDispatch->details->pluck('id')->contains($detail->id)) {
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

            $orderDispatch->dispatch_status = 'Cancelado';
            $orderDispatch->save();

            DB::statement('CALL order_dispatched_status(?)', [$orderDispatch->order->id]);
            DB::statement('CALL order_dispatch_status(?,?)', [$orderDispatch->id, $orderDispatch->order->id]);

            return $this->successResponse(
                $orderDispatch,
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
            $orderDispatch = OrderDispatch::with('order', 'order_dispatch_details.order_detail', 'order_dispatch_details.order_dispatch_detail_quantities.order_detail_quantity')->findOrFail($request->input('id'));

            foreach($orderDispatch->order_dispatch_details as $detail) {
                $detail->status = 'Rechazado';
                $detail->save();
                
                foreach($detail->order_dispatch_detail_quantities as $quantity) {
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

            $orderDispatch->dispatch_status = 'Rechazado';
            $orderDispatch->save();

            DB::statement('CALL order_dispatched_status(?)', [$orderDispatch->order->id]);
            DB::statement('CALL order_dispatch_status(?,?)', [$orderDispatch->id, $orderDispatch->order->id]);

            return $this->successResponse(
                $orderDispatch,
                'La orden de despacho fue rechazada exitosamente.',
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

    public function pdf(OrderDispatchPdfRequest $request)
    {
        try {
            $orderDispatch = OrderDispatch::with('order', 'details.quantities')->findOrFail($request->input('id'));
            $pdf = \PDF::loadView('Dashboard.OrderDispatches.PDF', compact('orderDispatch'))->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            /* $pdf = \PDF::loadView('Browser_public.pdfdocument', compact('queryic'))->output();
            return $pdf->download('pdfdocument.pdf'); */
            return $pdf->stream('pdfdocument.pdf');
        } catch (ModelNotFoundException $e) {
            return back()->with('danger', 'Ocurrió un error al cargar el pdf de la orden de despacho del pedido: ' . $this->getMessage('ModelNotFoundException'));
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }
}
