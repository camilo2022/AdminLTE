<?php

namespace App\Http\Controllers;

use App\Models\OrderPurchase;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderPurchase\OrderPurchaseApproveRequest;
use App\Http\Requests\OrderPurchase\OrderPurchaseAssignPaymentQueryRequest;
use App\Http\Requests\OrderPurchase\OrderPurchaseAssignPaymentRequest;
use App\Http\Requests\OrderPurchase\OrderPurchaseCancelRequest;
use App\Http\Requests\OrderPurchase\OrderPurchaseIndexQueryRequest;
use App\Http\Requests\OrderPurchase\OrderPurchasePaymentIndexQueryRequest;
use App\Http\Requests\OrderPurchase\OrderPurchasePendingRequest;
use App\Http\Requests\OrderPurchase\OrderPurchaseReceiveRequest;
use App\Http\Requests\OrderPurchase\OrderPurchaseRemovePaymentRequest;
use App\Http\Requests\OrderPurchase\OrderPurchaseStoreRequest;
use App\Http\Requests\OrderPurchase\OrderPurchaseUpdateRequest;
use App\Http\Resources\OrderPurchase\OrderPurchaseIndexQueryCollection;
use App\Http\Resources\OrderPurchase\OrderPurchasePaymentIndexQueryCollection;
use App\Models\Bank;
use App\Models\File;
use App\Models\ModelPaymentType;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\Workshop;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class OrderPurchaseController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.OrderPurchases.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(OrderPurchaseIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $orderPurchases = OrderPurchase::with([
                    'invoices', 'payments',
                    'workshop' => fn($query) => $query->withTrashed(),
                    'workshop.country', 'workshop.departament', 'workshop.city',
                    'purchase_user' => fn($query) => $query->withTrashed()
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
                new OrderPurchaseIndexQueryCollection($orderPurchases),
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

    public function create()
    {
        try {
            return $this->successResponse(
                [
                    'workshops' => Workshop::all(),
                    'paymentTypes' => PaymentType::all()
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

    public function store(OrderPurchaseStoreRequest $request)
    {
        try {
            $orderPurchase = new OrderPurchase();
            $orderPurchase->workshop_id = $request->input('workshop_id');
            $orderPurchase->purchase_user_id = Auth::user()->id;
            $orderPurchase->purchase_date = Carbon::now()->format('Y-m-d H:i:s');
            $orderPurchase->purchase_observation = $request->input('purchase_observation');
            $orderPurchase->save();

            foreach($request->input('payment_type_ids') as $payment_type_id) {
                $orderPaymentType = new ModelPaymentType();
                $orderPaymentType->model_type = OrderPurchase::class;
                $orderPaymentType->model_id = $orderPurchase->id;
                $orderPaymentType->payment_type_id = $payment_type_id;
                $orderPaymentType->save();
            }

            return $this->successResponse(
                [
                    'url' => URL::route('Dashboard.Orders.Purchase.Details.Index', ['id' => $orderPurchase->id]),
                    'orderPurchase' => $orderPurchase
                ],
                'La orden de compra fue registrado por el asesor exitosamente.',
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
            return $this->successResponse(
                [
                    'workshops' => Workshop::all(),
                    'orderPurchase' => OrderPurchase::with('payment_types')->findOrFail($id)
                ],
                'La orden de compra fue encontrado exitosamente.',
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

    public function update(OrderPurchaseUpdateRequest $request, $id)
    {
        try {
            $orderPurchase = OrderPurchase::findOrFail($id);
            $orderPurchase->workshop_id = $request->input('workshop_id');
            $orderPurchase->purchase_date = Carbon::now()->format('Y-m-d H:i:s');
            $orderPurchase->purchase_observation = $request->input('purchase_observation');
            $orderPurchase->save();

            ModelPaymentType::whereHasMorph('model', [OrderPurchase::class], function ($query) use ($orderPurchase) {
                $query->where('model_id', $orderPurchase->id);
            })->whereNotIn('payment_type_id', $request->input('payment_type_ids'))->delete();

            $orderPurchase->load('payment_types');

            $payment_type_ids = array_values(array_diff($request->input('payment_type_ids'), $orderPurchase->payment_types->pluck('id')->toArray()));

            foreach($payment_type_ids as $payment_type_id) {
                $orderPaymentType = new ModelPaymentType();
                $orderPaymentType->model_type = OrderPurchase::class;
                $orderPaymentType->model_id = $orderPurchase->id;
                $orderPaymentType->payment_type_id = $payment_type_id;

                $orderPaymentType->save();
            }

            return $this->successResponse(
                $orderPurchase,
                'La orden de compra fue actualizado por el asesor exitosamente.',
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

    public function approve(OrderPurchaseApproveRequest $request)
    {
        try {
            $orderPurchase = OrderPurchase::with('order_purchase_details')->findOrFail($request->input('id'));
            $orderPurchase->order_purchase_details()->whereIn('status', ['Pendiente'])->update(['status' => 'Aprobado', 'date' => Carbon::now()->format('Y-m-d')]);
            $orderPurchase->purchase_status = 'Aprobado';
            $orderPurchase->purchase_date = Carbon::now()->format('Y-m-d');
            $orderPurchase->save();

            return $this->successResponse(
                [
                    'orderPurchase' => $orderPurchase,
                    'urlDownload' => $request->input('download') ? URL::route('Dashboard.Orders.Purchase.Download', ['id' => $orderPurchase->id]) : null
                ],
                'La orden de compra fue aprobado exitosamente.',
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

    public function pending(OrderPurchasePendingRequest $request)
    {
        try {
            $orderPurchase = OrderPurchase::with('order_purchase_details')->findOrFail($request->input('id'));
            $orderPurchase->order_purchase_details()->whereIn('status', ['Cancelado'])->update(['status' => 'Aprobado', 'date' => Carbon::now()->format('Y-m-d')]);
            $orderPurchase->purchase_status = 'Pendiente';
            $orderPurchase->purchase_date = Carbon::now()->format('Y-m-d');
            $orderPurchase->save();

            return $this->successResponse(
                $orderPurchase,
                'La orden de compra fue pendiente exitosamente.',
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

    public function cancel(OrderPurchaseCancelRequest $request)
    {
        try {
            $orderPurchase = OrderPurchase::with('order_purchase_details')->findOrFail($request->input('id'));
            $orderPurchase->order_purchase_details()->whereIn('status', ['Pendiente', 'Aprobado'])->update(['status' => 'Cancelado', 'date' => Carbon::now()->format('Y-m-d')]);
            $orderPurchase->purchase_status = 'Cancelado';
            $orderPurchase->purchase_date = Carbon::now()->format('Y-m-d');
            $orderPurchase->save();

            return $this->successResponse(
                $orderPurchase,
                'La orden de compra fue cancelado exitosamente.',
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

    public function receive(OrderPurchaseReceiveRequest $request)
    {

    }

    public function paymentQuery(OrderPurchasePaymentIndexQueryRequest $request)
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
                ->whereHasMorph('model', [OrderPurchase::class], function ($query) use ($request) {
                    $query->where('model_id', $request->input('order_purchase_id'));
                })
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new OrderPurchasePaymentIndexQueryCollection($payments),
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

    public function assignPaymentQuery(OrderPurchaseAssignPaymentQueryRequest $request)
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
                    'paymentTypes' => OrderPurchase::with('payment_types')->findOrFail($request->input('order_purchase_id'))->payment_types
                ],
                'La orden de compra fue encontrado exitosamente.',
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

    public function assignPayment(OrderPurchaseAssignPaymentRequest $request)
    {
        try {
            $payment = new Payment();
            $payment->model_id = $request->input('OrderPurchase');
            $payment->model_type = OrderPurchase::class;
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

            return $this->successResponse(
                $payment,
                'El pago con los soportes fueron anexados a la orden de compra.',
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

    public function removePayment(OrderPurchaseRemovePaymentRequest $request)
    {
        try {
            $payment = Payment::findOrFail($request->input('id'))->delete();
            return $this->successResponse(
                $payment,
                'El pago con los soportes fueron eliminados de la orden de compra.',
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

    public function download($id)
    {
        try {
            $orderPurchase = OrderPurchase::with([
                    'workshop' => fn($query) => $query->withTrashed(),
                    'workshop.country', 'workshop.departament', 'workshop.city',
                    'purchase_user' => fn($query) => $query->withTrashed(),
                    'order_purchase_details.order_purchase_detail_request_quantities.size',
                    'order_purchase_details.order_purchase_detail_request_quantities.order_purchase_detail_received_quantities'
                ])
                ->findOrFail($id);

            $sizes = $orderPurchase->order_purchase_details->pluck('order_purchase_detail_request_quantities')->flatten()->where('quantity', '>', 0)->pluck('size')->unique()->sortBy('id')->values();

            $pdf = \PDF::loadView('Dashboard.OrderPurchases.PDF', compact('orderPurchase', 'sizes'))->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            /* $pdf = \PDF::loadView('Browser_public.pdfdocument', compact('queryic'))->output();
            return $pdf->download('pdfdocument.pdf'); */
            return $pdf->stream("{$orderPurchase->id}-ORDEN-COMPRA.pdf");
        } catch (ModelNotFoundException $e) {
            return back()->with('danger', 'Ocurrió un error al cargar el pdf de la orden de compra: ' . $this->getMessage('ModelNotFoundException'));
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }
}
