<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderWallet\OrderWalletApproveRequest;
use App\Http\Requests\OrderWallet\OrderWalletCancelRequest;
use App\Http\Requests\OrderWallet\OrderWalletIndexQueryRequest;
use App\Http\Requests\OrderWallet\OrderWalletObservationRequest;
use App\Http\Requests\OrderWallet\OrderWalletPartiallyApproveRequest;
use App\Http\Requests\OrderWallet\OrderWalletPaymentIndexQueryRequest;
use App\Http\Requests\OrderWallet\OrderWalletPendingRequest;
use App\Http\Resources\OrderWallet\OrderWalletIndexQueryCollection;
use App\Http\Resources\OrderWallet\OrderWalletPaymentIndexQueryCollection;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\Payment;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class OrderWalletController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.OrderWallets.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(OrderWalletIndexQueryRequest $request)
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
                ->where('seller_status', 'Aprobado')
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new OrderWalletIndexQueryCollection($orders),
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

    public function observation(OrderWalletObservationRequest $request)
    {
        try {
            $order = Order::findOrFail($request->input('id'));
            $order->wallet_observation = $request->input('wallet_observation');
            $order->save();

            return $this->successResponse(
                $order,
                'La observacion de cartera fue actualizada exitosamente.',
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

    public function approve(OrderWalletApproveRequest $request)
    {
        try {
            $order = Order::with('client.client_type', 'order_details.order_detail_quantities')->findOrFail($request->input('id'));
            $order_value = 0;

            foreach($order->order_details->whereIn('status', ['Revision']) as $detail) {
                $order_value += $detail->order_detail_quantities->pluck('quantity')->sum() * $detail->price;
                $detail->status = 'Aprobado';
                $detail->save();
            }

            $order->wallet_status = 'Aprobado';
            $order->wallet_date = Carbon::now()->format('Y-m-d H:i:s');
            $order->wallet_user_id = Auth::user()->id;
            $order->save();

            if($order->client->client_type->require_quota) {
                $order->client->debt += $order_value;
                $order->client->save();
            }

            return $this->successResponse(
                $order,
                'El pedido fue aprobado por cartera exitosamente.',
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

    public function partiallyApprove(OrderWalletPartiallyApproveRequest $request)
    {
        try {
            $order = Order::findOrFail($request->input('id'));
            $order->wallet_status = 'Parcialmente Aprobado';
            $order->wallet_date = Carbon::now()->format('Y-m-d H:i:s');
            $order->wallet_user_id = Auth::user()->id;
            $order->save();

            return $this->successResponse(
                $order,
                'El pedido fue aprobado parcialmente por cartera exitosamente.',
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

    public function pending(OrderWalletPendingRequest $request)
    {
        try {
            $order = Order::with('order_details')->findOrFail($request->input('id'));

            foreach($order->order_details->whereIn('status', ['Agotado', 'Rechazado']) as $detail) {
                $detail->status = 'Pendiente';
                $detail->save();
            }

            $order->wallet_status = 'Pendiente';
            $order->wallet_date = Carbon::now()->format('Y-m-d H:i:s');
            $order->wallet_user_id = Auth::user()->id;
            $order->dispatched_status = 'Pendiente';
            $order->save();

            return $this->successResponse(
                $order,
                'El pedido fue pendiente por cartera exitosamente.',
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

    public function cancel(OrderWalletCancelRequest $request)
    {
        try {
            $order = Order::with('client.client_type', 'order_details.order_detail_quantities')->findOrFail($request->input('id'));
            $order_value = 0;

            foreach($order->order_details->whereIn('status', ['Pendiente', 'Revision', 'Aprobado']) as $detail) {
                if(in_array($detail->status, ['Revision', 'Aprobado'])) {
                    $order_value += $detail->status == 'Aprobado' ? $detail->order_detail_quantities->pluck('quantity')->sum() * $detail->price : 0 ;
                    foreach($detail->order_detail_quantities as $quantity) {
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
                    if($detail->status == 'Aprobado' && $order->client->client_type->require_quota) {
                        $order->client->debt -= $order->client->debt - $order_value < 0 ? 0 : $order_value;
                        $order->client->save();
                    }
                }

                $detail->status = 'Rechazado';
                $detail->save();
            }

            $order->wallet_status = 'Cancelado';
            $order->wallet_date = Carbon::now()->format('Y-m-d H:i:s');
            $order->wallet_user_id = Auth::user()->id;
            $order->dispatched_status = 'Cancelado';
            $order->dispatched_date = Carbon::now()->format('Y-m-d H:i:s');
            $order->save();

            return $this->successResponse(
                $order,
                'El pedido fue cancelado por cartera exitosamente.',
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

    public function paymentQuery(OrderWalletPaymentIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $payments = Payment::with('model', 'files.user', 'payment_type', 'bank')
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
                ->whereHasMorph('model', [Order::class], function ($query) use ($request) {
                    $query->where('model_id', $request->input('order_id'));
                })
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new OrderWalletPaymentIndexQueryCollection($payments),
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
