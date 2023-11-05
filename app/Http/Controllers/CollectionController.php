<?php

namespace App\Http\Controllers;

use App\Http\Requests\Collection\CollectionDeleteRequest;
use App\Http\Requests\Collection\CollectionIndexQueryRequest;
use App\Http\Requests\Collection\CollectionStoreRequest;
use App\Http\Requests\Collection\CollectionUpdateRequest;
use App\Http\Resources\Collection\CollectionIndexQueryCollection;
use App\Models\Collection;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollectionController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.Collections.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(CollectionIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $collections = Collection::when($request->filled('search'),
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
                ->withTrashed() //Trae los registros 'eliminados'
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new CollectionIndexQueryCollection($collections),
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

    public function store(CollectionStoreRequest $request)
    {
        try {
            $collection = new Collection();
            $collection->name = $request->input('name');
            $collection->code = $request->input('code');
            $collection->start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $collection->end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            $collection->save();

            DB::statement('CALL collections(?)', [Carbon::now()->format('Y-m-d H:i:s')]);

            return $this->successResponse(
                $collection,
                'La correria fue registrada exitosamente.',
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
            $user = Collection::withTrashed()->findOrFail($id);

            return $this->successResponse(
                $user,
                'La correria fue encontrado exitosamente.',
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

    public function update(CollectionUpdateRequest $request, $id)
    {
        try {
            $collection = Collection::withTrashed()->findOrFail($id);
            $collection->name = $request->input('name');
            $collection->code = $request->input('code');
            $collection->start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $collection->end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            $collection->save();

            DB::statement('CALL collections(?)', [Carbon::now()->format('Y-m-d H:i:s')]);

            return $this->successResponse(
                $collection,
                'La correria fue actualizada exitosamente.',
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

    public function delete(CollectionDeleteRequest $request)
    {
        try {
            DB::statement('CALL collections(?)', [Carbon::now()->format('Y-m-d H:i:s')]);

            $deleted_at = Carbon::now()->format('Y-m-d H:i:s');
            $collection = Collection::withTrashed()->findOrFail($request->input('id'));
            $start_date = Carbon::parse($collection->start_date);
            $end_date = Carbon::parse($collection->end_date);

            if ($start_date->lte($deleted_at) && $end_date->gte($deleted_at)) {
                return $this->successResponse(
                    $collection,
                    'La correria no se puede eliminar porque está activa.',
                    422
                );
            }

            $collection->deleted_at = Carbon::now()->format('Y-m-d H:i:s');
            $collection->save();

            return $this->successResponse(
                $collection,
                'La correria fue eliminada exitosamente.',
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
