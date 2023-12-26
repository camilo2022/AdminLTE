<?php

namespace App\Http\Controllers;

use App\Http\Requests\Warehouse\WarehouseAssignGestorRequest;
use App\Http\Requests\Warehouse\WarehouseDeleteRequest;
use App\Http\Requests\Warehouse\WarehouseIndexQueryRequest;
use App\Http\Requests\Warehouse\WarehouseRemoveGestorRequest;
use App\Http\Requests\Warehouse\WarehouseRestoreRequest;
use App\Http\Requests\Warehouse\WarehouseStoreRequest;
use App\Http\Requests\Warehouse\WarehouseUpdateRequest;
use App\Http\Resources\Warehose\WarehouseIndexQueryCollection;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseUser;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class WarehouseController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.Warehouses.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(WarehouseIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $warehouses = Warehouse::when($request->filled('search'),
                    function ($query) use ($request) {
                        $query->search($request->input('search'));
                    }
                )
                ->when($request->filled('start_date') && $request->filled('end_date'),
                    function ($query) use ($start_date, $end_date) {
                        $query->filterByDate($start_date, $end_date);
                    }
                )
                ->withTrashed() //Trae los registros 'eliminados'
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new WarehouseIndexQueryCollection($warehouses),
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
                '',
                'Ingrese los datos para hacer la validacion y registro.',
                200
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

    public function store(WarehouseStoreRequest $request)
    {
        try {
            $warehouse = new Warehouse();
            $warehouse->name = $request->input('name');
            $warehouse->code = $request->input('code');
            $warehouse->description = $request->input('description');
            $warehouse->save();

            return $this->successResponse(
                $warehouse,
                'La bodega fue registrada exitosamente.',
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
            $warehouse = Warehouse::withTrashed()->findOrFail($id);

            return $this->successResponse(
                $warehouse,
                'La bodega fue encontrada exitosamente.',
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

    public function update(WarehouseUpdateRequest $request, $id)
    {
        try {
            $warehouse = Warehouse::withTrashed()->findOrFail($id);
            $warehouse->name = $request->input('name');
            $warehouse->code = $request->input('code');
            $warehouse->description = $request->input('description');
            $warehouse->save();

            return $this->successResponse(
                $warehouse,
                'La bodega fue actualizada exitosamente.',
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

    public function show($id)
    {
        try {
            $users = User::with('warehouses')->get();
            $warehouse = Warehouse::findOrFail($id);

            foreach ($users as $user) {
                $warehousesId = $user->warehouses->pluck('id')->all();
                $user->merge([
                    'admin' => in_array($id, $warehousesId)
                ]);
            }

            return $this->successResponse(
                (object) [
                    'warehouse' => $warehouse,
                    'admins' => $users
                ],
                'La bodega fue encontrada exitosamente.',
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

    public function assignGestor(WarehouseAssignGestorRequest $request)
    {
        try {
            $warehouse_has_users = new WarehouseUser();
            $warehouse_has_users->user_id = $request->input('user_id');
            $warehouse_has_users->warehouse_id = $request->input('warehouse_id');
            $warehouse_has_users->save();

            return $this->successResponse(
                $warehouse_has_users,
                'Gestor de bodega asignado exitosamente.',
                200
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

    public function removeGestor(WarehouseRemoveGestorRequest $request)
    {
        try {
            $warehouse_has_users = WarehouseUser::where('user_id', '=', $request->input('user_id'))
            ->where('warehouse_id', '=', $request->input('warehouse_id'))->delete();

            return $this->successResponse(
                $warehouse_has_users,
                'Gestor de bodega removido exitosamente.',
                200
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

    public function delete(WarehouseDeleteRequest $request)
    {
        try {
            $warehouse = Warehouse::withTrashed()->findOrFail($request->input('id'))->delete();
            return $this->successResponse(
                $warehouse,
                'La bodega fue eliminada exitosamente.',
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

    public function restore(WarehouseRestoreRequest $request)
    {
        try {
            $warehouse = Warehouse::withTrashed()->findOrFail($request->input('id'))->restore();
            return $this->successResponse(
                $warehouse,
                'La bodega fue restaurada exitosamente.',
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
