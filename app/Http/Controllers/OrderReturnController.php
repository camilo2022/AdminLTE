<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderReturn\OrderReturnApproveRequest;
use App\Http\Requests\OrderReturn\OrderReturnCancelRequest;
use App\Http\Requests\OrderReturn\OrderReturnCreateRequest;
use App\Http\Requests\OrderReturn\OrderReturnIndexQueryRequest;
use App\Http\Requests\OrderReturn\OrderReturnPendingRequest;
use App\Http\Requests\OrderReturn\OrderReturnStoreRequest;
use App\Http\Requests\OrderReturn\OrderReturnUpdateRequest;
use App\Http\Resources\OrderReturn\OrderReturnIndexQueryCollection;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\ReturnType;
use App\Models\Warehouse;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class OrderReturnController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.OrderReturns.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(OrderReturnIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $orders = Order::with([
                    'client' => fn($query) => $query->withTrashed(),
                    'client.country', 'client.departament', 'client.city',
                    'client_branch' => fn($query) => $query->withTrashed(),
                    'client_branch.country', 'client_branch.departament', 'client_branch.city',
                    'sale_channel' => fn($query) => $query->withTrashed(),
                    'seller_user' => fn($query) => $query->withTrashed(),
                    'wallet_user' => fn($query) => $query->withTrashed(),
                    'correria' => fn($query) => $query->withTrashed(),
                    'order_returns.return_user' => fn($query) => $query->withTrashed(),
                    'order_returns.return_type', 'order_details'
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
                ->whereIn('dispatched_status', ['Parcialmente Despachado', 'Despachado', 'Parcialmente Devuelto', 'Devuelto'])
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new OrderReturnIndexQueryCollection($orders),
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

    public function create(OrderReturnCreateRequest $request)
    {
        try {
            $order = Order::with([
                'client' => fn($query) => $query->withTrashed(),
                'client.document_type',
                'client.country', 'client.departament', 'client.city',
                'client_branch' => fn($query) => $query->withTrashed(),
                'client_branch.country', 'client_branch.departament', 'client_branch.city',
                'sale_channel' => fn($query) => $query->withTrashed(),
                'seller_user' => fn($query) => $query->withTrashed(),
                'wallet_user' => fn($query) => $query->withTrashed(),
                'correria' => fn($query) => $query->withTrashed()
            ])
            ->findOrFail($request->input('order_id'));

            return $this->successResponse(
                [
                    'order' => $order,
                    'returnTypes' => ReturnType::whereHas('sale_channels', fn($query) => $query->where('sale_channels.id', $order->sale_channel_id))->get()
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

    public function store(OrderReturnStoreRequest $request)
    {
        try {
            $orderReturn = new OrderReturn();
            $orderReturn->order_id = $request->input('order_id');
            $orderReturn->return_user_id = Auth::user()->id;
            $orderReturn->return_type_id = $request->input('return_type_id');
            $orderReturn->return_date = Carbon::parse($request->input('return_date'))->format('Y-m-d H:i:s');
            $orderReturn->return_observation = $request->input('return_observation');
            $orderReturn->save();

            return $this->successResponse(
                [
                    'url' => URL::route('Dashboard.Orders.Return.Details.Index', ['id' => $orderReturn->id]),
                    'orderReturn' => $orderReturn
                ],
                'La orden de devolucion del pedido fue registrado exitosamente.',
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

    public function edit($id)
    {
        try {
            $orderReturn = OrderReturn::with([
                'order.client' => fn($query) => $query->withTrashed(),
                'order.client.document_type',
                'order.client.country', 'order.client.departament', 'order.client.city',
                'order.client_branch' => fn($query) => $query->withTrashed(),
                'order.client_branch.country', 'order.client_branch.departament', 'order.client_branch.city',
                'order.sale_channel' => fn($query) => $query->withTrashed(),
                'order.seller_user' => fn($query) => $query->withTrashed(),
                'order.wallet_user' => fn($query) => $query->withTrashed(),
                'order.correria' => fn($query) => $query->withTrashed()
            ])
            ->findOrFail($id);

            return $this->successResponse(
                [
                    'orderReturn' => $orderReturn,
                    'returnTypes' => ReturnType::whereHas('sale_channels', fn($query) => $query->where('sale_channels.id', $orderReturn->order->sale_channel_id))->get()
                ],
                'La orden de devolucion del pedido fue encontrado exitosamente.',
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

    public function update(OrderReturnUpdateRequest $request, $id)
    {
        try {
            $orderReturn = OrderReturn::findOrFail($id);
            $orderReturn->return_type_id = $request->input('return_type_id');
            $orderReturn->return_date = Carbon::parse($request->input('return_date'))->format('Y-m-d H:i:s');
            $orderReturn->return_observation = $request->input('return_observation');
            $orderReturn->save();

            return $this->successResponse(
                $orderReturn,
                'La orden de devolucion del pedido fue actualizado exitosamente.',
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

    public function approve(OrderReturnApproveRequest $request)
    {
        try {
            $orderReturn = OrderReturn::with([
                    'order_return_details.order_return_detail_quantities',
                    'order_return_details.order_detail.order_detail_quantities',
                    'order_return_detail_quantities.order_detail_quantity'
                ])
                ->findOrFail($request->input('id'));

            foreach($orderReturn->order_return_details->whereIn('status', ['Pendiente']) as $detail) {
                foreach($detail->order_detail->order_detail_quantities as $quantity) {
                    $inventory = Inventory::with('warehouse')
                        ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                        ->where('product_id', $detail->order_detail->product_id)
                        ->where('size_id', $quantity->order_detail_quantity->size_id)
                        ->where('color_id', $detail->order_detail->color_id)
                        ->where('tone_id', $detail->order_detail->tone_id)
                        ->first();

                    if(!$inventory) {
                        $warehouse = Warehouse::where('to_discount', true)->first();
                        if($warehouse) {
                            $inventory = new Inventory();
                            $inventory->product_id = $detail->order_detail->product_id;
                            $inventory->size_id = $quantity->order_detail_quantity->size_id;
                            $inventory->warehouse_id = $warehouse->id;
                            $inventory->color_id = $detail->order_detail->color_id;
                            $inventory->tone_id = $detail->order_detail->tone_id;
                            $inventory->save();
                        }
                    }

                    $inventory->quantity += $quantity->quantity;
                    $inventory->save();
                }

                $detail->order_detail->status = $detail->order_detail_quantities->pluck('quantity')->sum() == $detail->order_detail->order_detail_quantities->pluck('quantity')->sum() ? 'Devuelto' : 'Parcialmente Devuelto' ;
                $detail->order_detail->save();

                $detail->status = 'Aprobado';
                $detail->save();
            }

            $orderReturn->return_status = 'Aprobado';
            $orderReturn->save();

            DB::statement('CALL order_dispatched_status(?)', [$orderReturn->order->id]);

            return $this->successResponse(
                $orderReturn,
                'La orden de devolucion del pedido fue aprobado exitosamente.',
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

    public function pending(OrderReturnPendingRequest $request)
    {
        try {
            $orderReturn = OrderReturn::with([
                    'order_return_details.order_return_detail_quantities',
                    'order_return_details.order_detail.order_detail_quantities',
                    'order_return_detail_quantities.order_detail_quantity'
                ])
                ->findOrFail($request->input('id'));

            foreach($orderReturn->order_return_details->whereIn('status', ['Cancelado']) as $detail) {
                $detail->status = 'Pendiente';
                $detail->save();
            }

            $orderReturn->return_status = 'Aprobado';
            $orderReturn->save();

            return $this->successResponse(
                $orderReturn,
                'La orden de devolucion del pedido fue pendiente exitosamente.',
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

    public function cancel(OrderReturnCancelRequest $request)
    {
        try {
            $orderReturn = OrderReturn::with([
                    'order_return_details.order_return_detail_quantities',
                    'order_return_details.order_detail.order_detail_quantities',
                    'order_return_detail_quantities.order_detail_quantity'
                ])
                ->findOrFail($request->input('id'));

            foreach($orderReturn->order_return_details as $detail) {
                $detail->status = 'Cancelado';
                $detail->save();
            }

            $orderReturn->return_status = 'Cancelado';
            $orderReturn->save();

            return $this->successResponse(
                $orderReturn,
                'La orden de devolucion del pedido fue cacelado exitosamente.',
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
