<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderPacked\OrderPackedDeleteRequest;
use App\Http\Requests\OrderPacked\OrderPackedFinishRequest;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class OrderPackedController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            $orderPacking = OrderPacking::where('packing_user_id', Auth::user()->id)->where('packing_status', 'Empacando')->first();
            if($orderPacking) {
                return Redirect::route('Dashboard.Orders.Packed.Package.Index', ['id' => $orderPacking->id]);
            }
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
            $orderPackings = OrderDispatch::with(['order', 'dispatch_user',
                    'order.client' => fn($query) => $query->withTrashed(),
                    'order.client.country', 'order.client.departament', 'order.client.city',
                    'order.client_branch' => fn($query) => $query->withTrashed(),
                    'order.client_branch.country', 'order.client_branch.departament', 'order.client_branch.city',
                    'order.seller_user' => fn($query) => $query->withTrashed(),
                    'order.wallet_user' => fn($query) => $query->withTrashed(),
                    'order.correria' => fn($query) => $query->withTrashed(),
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
                ->whereDoesntHave('order_packing')
                ->where('dispatch_status', 'Aprobado')
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new OrderPackedIndexQueryCollection($orderPackings),
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
            $orderPacked = new OrderPacking();
            $orderPacked->order_dispatch_id = $request->input('order_dispatch_id');
            $orderPacked->packing_user_id = Auth::user()->id;
            $orderPacked->packing_date = Carbon::now()->format('Y-m-d H:i:s');
            $orderPacked->save();

            return $this->successResponse(
                [
                    'url' => URL::route('Dashboard.Orders.Packed.Package.Index', ['id' => $orderPacked->id])
                ],
                'La orden de empacado fue creada exitosamente.',
                201
            );
        }  catch (QueryException $e) {
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

    public function finish(OrderPackedFinishRequest $request)
    {
        try {
            $orderPacked = OrderPacking::with('order_packages.order_package_details.order_dispatch_detail.order_detail')->findOrFail($request->input('id'));
            $orderPacked->packing_status = 'Finalizado';
            $orderPacked->save();

            return $this->successResponse(
                [
                    'url' => URL::route('Dashboard.Orders.Packed.Index'),
                    'orderPacked' => $orderPacked
                ],
                'La orden de empacado fue finalizada exitosamente.',
                200
            );
        }  catch (QueryException $e) {
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
            $orderPacked = OrderPacking::findOrFail($request->input('id'))->delete();

            return $this->successResponse(
                [
                    'url' => URL::route('Dashboard.Orders.Packed.Index'),
                    'orderPacked' => $orderPacked
                ],
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
