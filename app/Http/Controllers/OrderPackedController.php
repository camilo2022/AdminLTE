<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderPacked\OrderPackedDeleteRequest;
use App\Http\Requests\OrderPacked\OrderPackedIndexQueryRequest;
use App\Http\Requests\OrderPacked\OrderPackedStoreRequest;
use App\Http\Resources\OrderPacked\OrderPackedIndexQueryCollection;
use App\Models\OrderDispatch;
use App\Models\OrderPacking;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\URL;

class OrderPackedController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.OrderPackings.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(OrderPackedIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $orderDispatches = OrderDispatch::with(['order_packing.packing_user',
                    'order_dispatch_details' => fn($query) => $query->where('status', 'Aprobado'),
                    'order_dispatch_details.order_dispatch_detail_quantities',
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
                ->where('dispatch_status', 'Aprobado')
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new OrderPackedIndexQueryCollection($orderDispatches),
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

    public function store(OrderPackedStoreRequest $request)
    {
        try {
            $order_packing = new OrderPacking();
            $order_packing->order_dispatch_id = $request->input('order_dispatch_id');
            $order_packing->packing_user_id = $request->input('packing_user_id');
            $order_packing->packing_status = $request->input('packing_status');
            $order_packing->sale_channel_id = $request->input('sale_channel_id');
            $order_packing->save();

            return $this->successResponse(
                [
                    'url' => URL::route('Dashboard.Orders.Packed.Details.Index', ['id' => $order_packing->id])
                ],
                'La orden de empacado fue creada exitosamente.',
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

    public function delete(OrderPackedDeleteRequest $request)
    {
        try {
            $order_packed = OrderPacking::findOrFail($request->input('id'))->delete();
            return $this->successResponse(
                $order_packed,
                'La orden de empacado fue eliminada exitosamente.',
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
}
