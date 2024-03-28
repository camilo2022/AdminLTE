<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\WalletAssignPaymentQueryRequest;
use App\Http\Requests\Wallet\WalletAssignPaymentRequest;
use App\Http\Requests\Wallet\WalletIndexQueryRequest;
use App\Http\Resources\Wallet\WalletIndexQueryCollection;
use App\Models\Bank;
use App\Models\ClientBranch;
use App\Models\File;
use App\Models\OrderDispatch;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
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
                    'client.client_type',
                    'client.client_orders.order_dispatches' => fn($query) => $query->where('dispatch_status', 'Despachado')->whereIn('payment_status', ['Pendiente de Pago', 'Parcialmente Pagado']),
                    'client.client_orders.order_dispatches.invoices',
                    'client.client_orders.order_dispatches.payments',
                    'client_branch_orders.order_dispatches' => fn($query) => $query->where('dispatch_status', 'Despachado')->whereIn('payment_status', ['Pendiente de Pago', 'Parcialmente Pagado']),
                    'client_branch_orders.order_dispatches.invoices',
                    'client_branch_orders.order_dispatches.payments',
                    'client_branch_orders.order_dispatches.invoice_user',
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
                ->whereHas('client.client_type', function ($query) {
                    $query->where('require_quota', true);
                })
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

    public function assignPaymentQuery(WalletAssignPaymentQueryRequest $request)
    {
        try {
            if($request->filled('payment_type_id')) {
                return $this->successResponse(
                    PaymentType::findOrFail($request->input('payment_type_id'))->require_banks ? Bank::all() : [],
                    'Bancos encontradas con exito.',
                    200
                );
            }

            return $this->successResponse(
                [
                    'paymentTypes' => OrderDispatch::with('order.payment_types')->findOrFail($request->input('order_dispatch_id'))->order->payment_types
                ],
                'La orden de despacho fue encontrada exitosamente.',
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

    public function assignPayment(WalletAssignPaymentRequest $request)
    {
        try {
            $payment = new Payment();
            $payment->model_id = $request->input('order_dispatch_id');
            $payment->model_type = OrderDispatch::class;
            $payment->value = $request->input('value');
            $payment->reference = $request->input('reference');
            $payment->date = $request->input('date');
            $payment->payment_type_id = $request->input('payment_type_id');
            $payment->bank_id = $request->input('bank_id');
            $payment->save();

            if ($request->hasFile('supports')) {
                foreach($request->file('supports') as $support) {
                    $file = new File();
                    $file->model_type = Payment::class;
                    $file->model_id = $payment->id;
                    $file->name = $support->getClientOriginalName();
                    $file->path = $support->store('Payments/' . $payment->id, 'public');
                    $file->mime = $support->getMimeType();
                    $file->extension = $support->getClientOriginalExtension();
                    $file->size = $support->getSize();
                    $file->user_id = Auth::user()->id;
                    $file->metadata = json_encode((array) stat($support));
                    $file->save();
                }
            }

            $orderDispatch = OrderDispatch::with('order.client.client_type', 'payments', 'invoices')->findOrFail($request->input('order_dispatch_id'));
            if($orderDispatch->order->client->client_type->require_quota) {
                $orderDispatch->order->client->debt -= $request->input('value');
                $orderDispatch->order->client->save();
            }

            $payment_value = $orderDispatch->payments->pluck('value')->sum();
            $invoice_value = $orderDispatch->invoices->pluck('value')->sum();

            if($payment_value == $invoice_value) {
                $orderDispatch->payment_status = 'Pagado';
            } elseif ($payment_value < $invoice_value) {
                $orderDispatch->payment_status = 'Parcialmente Pagado';
            }

            $orderDispatch->save();

            return $this->successResponse(
                $payment,
                'El pago con los soportes fueron anexados a la orden de despacho.',
                201
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
