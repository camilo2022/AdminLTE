<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderSeller\OrderSellerApproveRequest;
use App\Http\Requests\OrderSeller\OrderSellerCancelRequest;
use App\Http\Requests\OrderSeller\OrderSellerCreateRequest;
use App\Http\Requests\OrderSeller\OrderSellerEditRequest;
use App\Http\Requests\OrderSeller\OrderSellerIndexQueryRequest;
use App\Http\Requests\OrderSeller\OrderSellerPendingRequest;
use App\Http\Requests\OrderSeller\OrderSellerStoreRequest;
use App\Http\Requests\OrderSeller\OrderSellerUpdateRequest;
use App\Http\Resources\OrderSeller\OrderSellerIndexQueryCollection;
use App\Models\Client;
use App\Models\ClientBranch;
use App\Models\Inventory;
use App\Models\Order;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class OrderSellerController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.OrderSellers.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(OrderSellerIndexQueryRequest $request)
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
                ->when(!Auth::user()->hasRole('OrderWallet'),
                    function ($query) {
                        $query->where('seller_user_id', Auth::user()->id);
                    }
                )
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new OrderSellerIndexQueryCollection($orders),
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

    public function create(OrderSellerCreateRequest $request)
    {
        try {
            if($request->filled('client_id')) {
                return $this->successResponse(
                    ClientBranch::where('client_id', '=', $request->input('client_id'))->get(),
                    'Sucursales encontradas con exito.',
                    200
                );
            }

            return $this->successResponse(
                Client::all(),
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

    public function store(OrderSellerStoreRequest $request)
    {
        try {
            $order = new Order();
            $order->client_id = $request->input('client_id');
            $order->client_branch_id = $request->input('client_branch_id');
            $order->sale_channel_id = $request->input('sale_channel_id');
            $order->dispatch = $request->input('dispatch');
            $order->dispatch_date = Carbon::parse($request->input('dispatch_date'))->format('Y-m-d');
            $order->seller_user_id = Auth::user()->id;
            $order->seller_date = Carbon::now()->format('Y-m-d H:i:s');
            $order->seller_observation = $request->input('seller_observation');
            $order->correria_id = $request->input('correria_id');
            $order->save();

            return $this->successResponse(
                [
                    'url' => URL::route('Dashboard.Orders.Seller.Details.Index', ['id' => $order->id]),
                    'order' => $order
                ],
                'El pedido fue registrado por el asesor exitosamente.',
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

    public function edit(OrderSellerEditRequest $request, $id)
    {
        try {
            if($request->filled('client_id')) {
                return $this->successResponse(
                    ClientBranch::where('client_id', '=', $request->input('client_id'))->get(),
                    'Sucursales encontradas con exito.',
                    200
                );
            }

            return $this->successResponse(
                [
                    'clients' => Client::all(),
                    'order' => Order::findOrFail($id)
                ],
                'El pedido fue encontrado exitosamente.',
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

    public function update(OrderSellerUpdateRequest $request, $id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->client_id = $request->input('client_id');
            $order->client_branch_id = $request->input('client_branch_id');
            $order->sale_channel_id = $request->input('sale_channel_id');
            $order->dispatch = $request->input('dispatch');
            $order->dispatch_date = Carbon::parse($request->input('dispatch_date'))->format('Y-m-d');
            $order->seller_observation = $request->input('seller_observation');
            $order->save();

            return $this->successResponse(
                $order,
                'El pedido fue actualizado por el asesor exitosamente.',
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

    public function approve(OrderSellerApproveRequest $request)
    {
        try {
            $order = Order::with('details.quantities')->findOrFail($request->input('id'));

            foreach($order->details as $detail) {
                if($detail->status == 'Pendiente') {
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

                        $detail->status = 'Revision';
                    } else {
                        $detail->status = 'Agotado';
                    }

                    $detail->save();
                }
            }

            $order->seller_status = 'Aprobado';
            $order->save();

            return $this->successResponse(
                $order,
                'El pedido fue aprobado por el asesor exitosamente.',
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

    public function pending(OrderSellerPendingRequest $request)
    {
        try {
            $order = Order::findOrFail($request->input('id'));
            $order->selleR_status = 'Pendiente';
            $order->save();

            return $this->successResponse(
                $order,
                'El pedido fue pendiente por el asesor exitosamente.',
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

    public function cancel(OrderSellerCancelRequest $request)
    {
        try {
            $order = Order::with('details')->findOrFail($request->input('id'));

            foreach($order->details as $detail) {
                if($detail->status == 'Revision') {
                    foreach($detail->quantities as $quantity) {
                        $inventory = Inventory::with('warehouse')
                            ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                            ->where('product_id', $detail->product_id)
                            ->where('size_id', $quantity->size_id)
                            ->where('color_id', $detail->color_id)
                            ->where('tone_id', $detail->tone_id)
                            ->first();

                        $inventory->quantity += $quantity->quantity;
                        $inventory->save();
                    }
                }

                $detail->status = 'Cancelado';
                $detail->save();
            }

            $order->seller_status = 'Cancelado';
            $order->wallet_status = 'Cancelado';
            $order->dispatched_status = 'Cancelado';
            $order->save();

            return $this->successResponse(
                $order,
                'El pedido fue cancelado por el asesor exitosamente.',
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
