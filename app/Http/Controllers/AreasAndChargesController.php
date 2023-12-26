<?php

namespace App\Http\Controllers;

use App\Http\Requests\AreasAndCharges\AreasAndChargesDeleteRequest;
use App\Http\Requests\AreasAndCharges\AreasAndChargesIndexQueryRequest;
use App\Http\Requests\AreasAndCharges\AreasAndChargesRestoreRequest;
use App\Http\Requests\AreasAndCharges\AreasAndChargesStoreRequest;
use App\Http\Requests\AreasAndCharges\AreasAndChargesUpdateRequest;
use App\Http\Resources\AreasAndCharges\AreasAndChargesIndexQueryCollection;
use App\Models\Area;
use App\Models\Charge;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class AreasAndChargesController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.AreasAndCharges.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(AreasAndChargesIndexQueryRequest $request)
    {
        try{
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            // Consultar roles con relaciones y aplicar filtros
            $areasAndCharges = Area::with([
                    'charges' => function ($query) { $query->withTrashed(); }
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
                ->withTrashed() //Trae los registros 'eliminados'
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));
            // Devolver una respuesta exitosa con los roles y permisos paginados
            return $this->successResponse(
                new AreasAndChargesIndexQueryCollection($areasAndCharges),
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

    public function store(AreasAndChargesStoreRequest $request)
    {
        try {
            $area = new Area();
            $area->name = $request->input('name');
            $area->description = $request->input('description');
            $area->save();

            collect($request->input('charges'))->map(function ($charge) use ($area){
                $charge = (object) $charge;
                $chargeNew = new Charge();
                $chargeNew->area_id = $area->id;
                $chargeNew->name = $area->name;
                $chargeNew->description = $area->description;
                $chargeNew->save();
            });

            return $this->successResponse(
                $area,
                'Area y cargos registrados exitosamente.',
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
            // Deshacer la transacción en caso de excepción y devolver una respuesta de error
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
                Area::with([
                    'charges' => function ($query) { $query->withTrashed(); }
                ])->withTrashed()->findOrFail($id),
                'El area y cargos fueron encontrados exitosamente.',
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

    public function update(AreasAndChargesUpdateRequest $request, $id)
    {
        try {
            $area = Area::withTrashed()->findOrFail($id);
            $area->name = $request->input('name');
            $area->description = $request->input('description');
            $area->save();

            collect($request->input('charges'))->map(function ($charge) use ($area){
                $charge = (object) $charge;
                $chargeNew = isset($charge->id) ? Charge::withTrashed()->find($charge->id) : new Charge();
                $chargeNew->area_id = $area->id;
                $chargeNew->name = $charge->name;
                $chargeNew->description = $charge->description;
                $chargeNew->deleted_at = filter_var($charge->status, FILTER_VALIDATE_BOOLEAN) ? null : Carbon::now()->format('Y-m-d H:i:s');
                $chargeNew->save();
            });

            return $this->successResponse(
                $area,
                'Area y cargos actualizadas exitosamente.',
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
            // Deshacer la transacción en caso de excepción y devolver una respuesta de error
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function delete(AreasAndChargesDeleteRequest $request)
    {
        try {
            // Eliminar categoria y subcategorias
            $area = Area::withTrashed()->findOrFail($request->input('id'));
            // Eliminar la categoría y sus subcategorías
            $area->charges()->delete();
            $area->delete();
            // Devolver una respuesta exitosa
            return $this->successResponse(
                $area,
                'Area y cargos eliminadas exitosamente.',
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
            // Deshacer la transacción en caso de excepción y devolver una respuesta de error
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function restore(AreasAndChargesRestoreRequest $request)
    {
        try {
            // Restaurar categoria y subcategorias
            $area = Area::withTrashed()->findOrFail($request->input('id'));
            // Restaurar la categoría y sus subcategorías
            $area->charges()->restore();
            $area->restore();
            return $this->successResponse(
                $area,
                'Area y cargos fueron restauradas exitosamente.',
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
