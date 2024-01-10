<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderWallet\OrderWalletIndexQueryRequest;
use App\Models\Order;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
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
                ->when(!Auth::user()->hasRole('OrderWallet'),
                    function ($query) {
                        $query->where('seller_user_id', Auth::user()->id);
                    }
                )
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

    public function approve(OrderWalletApproveRequest $request)
    {
        try {
            $order = Order::with('details.quantities')->findOrFail($request->input('id'));

            foreach($order->details as $detail) {
                if($detail->status == 'Pendiente') {
                    $boolean = true;
                    foreach($detail->quantities as $quantity) {
                        $inventory = Inventory::with('product', 'size', 'warehouse', 'color', 'tone')
                            ->whereHas('product', fn($subQuery) => $subQuery->where('id', $detail->product_id))
                            ->whereHas('size', fn($subQuery) => $subQuery->where('id', $quantity->size_id))
                            ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                            ->whereHas('color', fn($subQuery) => $subQuery->where('id', $detail->color_id))
                            ->whereHas('tone', fn($subQuery) => $subQuery->where('id', $detail->tone_id))
                            ->first();

                        if($inventory->quantity < $quantity->quantity) {
                            $boolean = false;
                            $detail->status = 'Agotado';
                            $detail->save();
                            break;
                        }
                    }

                    if($boolean){
                        foreach($detail->quantities as $quantity) {
                            $inventory = Inventory::with('product', 'size', 'warehouse', 'color', 'tone')
                                ->whereHas('product', fn($subQuery) => $subQuery->where('id', $detail->product_id))
                                ->whereHas('size', fn($subQuery) => $subQuery->where('id', $quantity->size_id))
                                ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                                ->whereHas('color', fn($subQuery) => $subQuery->where('id', $detail->color_id))
                                ->whereHas('tone', fn($subQuery) => $subQuery->where('id', $detail->tone_id))
                                ->first();
    
                            $inventory->quantity -= $quantity->quantity;
                            $inventory->save();

                            $detail->status = 'Aprobado';
                            $detail->save();
                        }
                    }
                }
            }

            $order->seller_status = 'Aprobado';
            $order->save();

            return $this->successResponse(
                $order,
                'El pedido fue aprobado exitosamente.',
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
            $order = Order::with('details')->findOrFail($request->input('id'));

            foreach ($order->details as $detail) {
                $detail->status = 'Cancelado';
                $detail->save();
            }

            $order->seller_status = 'Cancelado';
            $order->wallet_status = 'Cancelado';
            $order->dispatched_status = 'Cancelado';
            $order->save();

            return $this->successResponse(
                $order,
                'El pedido fue cancelado exitosamente.',
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
