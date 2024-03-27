<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\WalletIndexQueryRequest;
use App\Http\Resources\Wallet\WalletIndexQueryCollection;
use App\Models\ClientBranch;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            /* return $clientBranches = ClientBranch::with([
                'client.orders.order_dispatches' => fn($query) => $query->where('dispatch_status', 'Despachado')->whereIn('payment_status', ['Pendiente de Pago', 'Parcialmente Pagado']),
                'client.orders.order_dispatches.invoices',
                'client.orders.order_dispatches.payments',
                'orders.order_dispatches' => fn($query) => $query->where('dispatch_status', 'Despachado')->whereIn('payment_status', ['Pendiente de Pago', 'Parcialmente Pagado']),
                'orders.order_dispatches.invoices',
                'orders.order_dispatches.payments',
            ])->get(); */
            return view('Dashboard.Wallets.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(WalletIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $clientBranches = ClientBranch::with([
                    'client.orders.order_dispatches' => fn($query) => $query->where('dispatch_status', 'Despachado')->whereIn('payment_status', ['Pendiente de Pago', 'Parcialmente Pagado']),
                    'client.orders.order_dispatches.invoices',
                    'client.orders.order_dispatches.payments',
                    'orders.order_dispatches' => fn($query) => $query->where('dispatch_status', 'Despachado')->whereIn('payment_status', ['Pendiente de Pago', 'Parcialmente Pagado']),
                    'orders.order_dispatches.invoices',
                    'orders.order_dispatches.payments',
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
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new WalletIndexQueryCollection($clientBranches),
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
