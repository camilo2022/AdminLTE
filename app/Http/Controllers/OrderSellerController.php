<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderSeller\OrderSellerApprovePaymentRequest;
use App\Http\Requests\OrderSeller\OrderSellerApproveRequest;
use App\Http\Requests\OrderSeller\OrderSellerAssignPaymentQueryRequest;
use App\Http\Requests\OrderSeller\OrderSellerAssignPaymentRequest;
use App\Http\Requests\OrderSeller\OrderSellerCancelPaymentRequest;
use App\Http\Requests\OrderSeller\OrderSellerCancelRequest;
use App\Http\Requests\OrderSeller\OrderSellerCreateRequest;
use App\Http\Requests\OrderSeller\OrderSellerEditRequest;
use App\Http\Requests\OrderSeller\OrderSellerIndexQueryRequest;
use App\Http\Requests\OrderSeller\OrderSellerPaymentIndexQueryRequest;
use App\Http\Requests\OrderSeller\OrderSellerPendingRequest;
use App\Http\Requests\OrderSeller\OrderSellerRemovePaymentRequest;
use App\Http\Requests\OrderSeller\OrderSellerStoreRequest;
use App\Http\Requests\OrderSeller\OrderSellerUpdateRequest;
use App\Http\Resources\OrderSeller\OrderSellerIndexQueryCollection;
use App\Http\Resources\OrderSeller\OrderSellerPaymentIndexQueryCollection;
use App\Models\Bank;
use App\Models\Client;
use App\Models\ClientBranch;
use App\Models\File;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderPaymentType;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\SaleChannel;
use App\Models\Transporter;
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
                    'sale_channel' => fn($query) => $query->withTrashed(),
                    'seller_user' => fn($query) => $query->withTrashed(),
                    'wallet_user' => fn($query) => $query->withTrashed(),
                    'sale_channel' => fn($query) => $query->withTrashed(),
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
                [
                    'clients' => Client::all(),
                    'saleChannels' => SaleChannel::all(),
                    'paymentTypes' => PaymentType::all(),
                    'transporters' => Transporter::all()
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

    public function store(OrderSellerStoreRequest $request)
    {
        try {
            $order = new Order();
            $order->client_id = $request->input('client_id');
            $order->client_branch_id = $request->input('client_branch_id');
            $order->transporter_id = $request->input('transporter_id');
            $order->sale_channel_id = $request->input('sale_channel_id');
            $order->dispatch = $request->input('dispatch');
            $order->dispatch_date = Carbon::parse($request->input('dispatch_date'))->format('Y-m-d');
            $order->seller_user_id = Auth::user()->id;
            $order->seller_date = Carbon::now()->format('Y-m-d H:i:s');
            $order->seller_observation = $request->input('seller_observation');
            $order->correria_id = $request->input('correria_id');
            $order->save();

            foreach($request->input('payment_type_ids') as $payment_type_id) {
                $order_payment_type = New OrderPaymentType();
                $order_payment_type->order_id = $order->id;
                $order_payment_type->payment_type_id = $payment_type_id;
                $order_payment_type->save();
            }

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
                    'saleChannels' => SaleChannel::all(),
                    'paymentTypes' => PaymentType::all(),
                    'transporters' => Transporter::all(),
                    'order' => Order::with('payment_types')->findOrFail($id)
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
            $order->transporter_id = $request->input('transporter_id');
            $order->sale_channel_id = $request->input('sale_channel_id');
            $order->dispatch = $request->input('dispatch');
            $order->dispatch_date = Carbon::parse($request->input('dispatch_date'))->format('Y-m-d');
            $order->seller_observation = $request->input('seller_observation');
            $order->save();

            OrderPaymentType::where('order_id', $order->id)->whereNotIn('payment_type_id', $request->input('payment_type_ids'))->delete();
            
            $order->load('payment_types');

            $payment_type_ids = array_values(array_diff($request->input('payment_type_ids'), $order->payment_types->pluck('id')->toArray()));

            foreach($payment_type_ids as $payment_type_id) {
                $order_payment_type = New OrderPaymentType();
                $order_payment_type->order_id = $order->id;
                $order_payment_type->payment_type_id = $payment_type_id;
                $order_payment_type->save();
            }

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
            $order->seller_status = 'Pendiente';
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

    public function paymentQuery(OrderSellerPaymentIndexQueryRequest $request)
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
                new OrderSellerPaymentIndexQueryCollection($payments),
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

    public function assignPaymentQuery(OrderSellerAssignPaymentQueryRequest $request)
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
                    'paymentTypes' => Order::with('payment_types')->findOrFail($request->input('order_id'))->payment_types
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

    public function assignPayment(OrderSellerAssignPaymentRequest $request)
    {
        try {
            $payment = new Payment();
            $payment->model_id = $request->input('order_id');
            $payment->model_type = Order::class;
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
                'El pago con los soportes fueron anexados al pedido.',
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

    public function removePayment(OrderSellerRemovePaymentRequest $request)
    {
        try {
            $payment = Payment::findOrFail($request->input('id'))->delete();
            return $this->successResponse(
                $payment,
                'El pago con los soportes fueron eliminado del pedido.',
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

    public function approvePayment(OrderSellerApprovePaymentRequest $request)
    {
        try {
            $order = Order::with('details')->findOrFail($request->input('id'));

            foreach($order->details as $detail) {
                if($detail->status == 'Revision') {
                    $detail->status = 'Aprobado';
                    $detail->save();
                }
            }

            $order->wallet_status = 'Parcialmente Aprobado';
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

    public function cancelPayment(OrderSellerCancelPaymentRequest $request)
    {
        try {
            $order = Order::with('details.quantities')->findOrFail($request->input('id'));

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

                $detail->status = 'Rechazado';
                $detail->save();
            }

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
