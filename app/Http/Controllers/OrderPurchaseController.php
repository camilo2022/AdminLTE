<?php

namespace App\Http\Controllers;

use App\Models\OrderPurchase;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderPurchase\OrderPurchaseIndexQueryRequest;
use App\Http\Requests\OrderPurchase\OrderPurchaseStoreRequest;
use App\Http\Requests\OrderPurchase\OrderPurchaseUpdateRequest;
use App\Http\Resources\OrderPurchase\OrderPurchaseIndexQueryCollection;
use App\Models\ModelPaymentType;
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
                    'workshops' => Workshop::all()
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
            $orderPurchase->purchase_date = Carbon::parse($request->input('purchase_date'))->format('Y-m-d');
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
                'El pedido de compra fue registrado por el asesor exitosamente.',
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

    public function update(OrderPurchaseUpdateRequest $request, $id)
    {
        try {
            $orderPurchase = OrderPurchase::findOrFail($id);
            $orderPurchase->workshop_id = $request->input('workshop_id');
            $orderPurchase->purchase_date = Carbon::parse($request->input('purchase_date'))->format('Y-m-d');
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
}
