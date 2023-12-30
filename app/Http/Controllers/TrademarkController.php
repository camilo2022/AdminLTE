<?php

namespace App\Http\Controllers;

use App\Http\Requests\Trademark\TrademarkDeleteRequest;
use App\Http\Requests\Trademark\TrademarkIndexQueryRequest;
use App\Http\Requests\Trademark\TrademarkRestoreRequest;
use App\Http\Requests\Trademark\TrademarkStoreRequest;
use App\Http\Requests\Trademark\TrademarkUpdateRequest;
use App\Http\Resources\Trademark\TrademarkIndexQueryCollection;
use App\Models\Trademark;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

class TrademarkController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.Trademarks.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(TrademarkIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $trademarks = Trademark::when($request->filled('search'),
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
                new TrademarkIndexQueryCollection($trademarks),
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

    public function store(TrademarkStoreRequest $request)
    {
        try {
            $trademark = new Trademark();
            $trademark->name = $request->input('name');
            $trademark->code = $request->input('code');
            $trademark->description = $request->input('description');
            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('Trademarks','public');
                $trademark->logo = $path;
            }
            $trademark->save();

            return $this->successResponse(
                $trademark,
                'La marca de producto fue registrada exitosamente.',
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
            $trademark = Trademark::withTrashed()->findOrFail($id);
            $trademark->path = asset('storage/' . $trademark->logo);
            return $this->successResponse(
                $trademark,
                'La marca de producto fue encontrada exitosamente.',
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

    public function update(TrademarkUpdateRequest $request, $id)
    {
        try {
            $trademark = Trademark::withTrashed()->findOrFail($id);
            $trademark->name = $request->input('name');
            $trademark->code = $request->input('code');
            $trademark->description = $request->input('description');
            if ($request->hasFile('logo')) {
                if (Storage::disk('public')->exists($trademark->logo)) {
                    Storage::disk('public')->delete($trademark->logo);
                }
                $path = $request->file('logo')->store('Trademarks','public');
                $trademark->logo = $path;
            }
            $trademark->save();

            return $this->successResponse(
                $trademark,
                'La marca de prodcuto fue actualizada exitosamente.',
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

    public function delete(TrademarkDeleteRequest $request)
    {
        try {
            $trademark = Trademark::withTrashed()->findOrFail($request->input('id'))->delete();
            return $this->successResponse(
                $trademark,
                'La marca de producto fue eliminado exitosamente.',
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

    public function restore(TrademarkRestoreRequest $request)
    {
        try {
            $trademark = Trademark::withTrashed()->findOrFail($request->input('id'))->restore();
            return $this->successResponse(
                $trademark,
                'La marca de producto fue restaurada exitosamente.',
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
