<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Business\BusinessCreateRequest;
use App\Http\Requests\Business\BusinessDeleteRequest;
use App\Http\Requests\Business\BusinessEditRequest;
use App\Http\Requests\Business\BusinessIndexQueryRequest;
use App\Http\Requests\Business\BusinessRestoreRequest;
use App\Http\Requests\Business\BusinessStoreRequest;
use App\Http\Requests\Business\BusinessUpdateRequest;
use App\Http\Resources\Business\BusinessIndexQueryCollection;
use App\Models\Business;
use App\Models\City;
use App\Models\Country;
use App\Models\Departament;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class BusinessController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.Businesses.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(BusinessIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $collections = Business::with('country', 'departament', 'city')
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
                ->withTrashed() //Trae los registros 'eliminados'
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new BusinessIndexQueryCollection($collections),
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

    public function create(BusinessCreateRequest $request)
    {
        try {
            if($request->filled('country_id')) {
                return $this->successResponse(
                    Departament::where('country_id', '=', $request->input('country_id'))->get(),
                    'Departamentos encontrados con exito.',
                    200
                );
            }

            if($request->filled('departament_id')) {
                return $this->successResponse(
                    City::where('departament_id', '=', $request->input('departament_id'))->get(),
                    'Ciudades encontradas con exito.',
                    200
                );
            }
            return $this->successResponse(
                Country::all(),
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

    public function store(BusinessStoreRequest $request)
    {
        try {
            $business = new Business();
            $business->name = $request->input('name');
            $business->document_number = $request->input('document_number');
            $business->telephone_number = $request->input('telephone_number');
            $business->email = $request->input('email');
            $business->country_id = $request->input('country_id');
            $business->departament_id = $request->input('departament_id');
            $business->city_id = $request->input('city_id');
            $business->address = $request->input('address');
            $business->neighbourhood = $request->input('neighbourhood');
            $business->description = $request->input('description');
            $business->save();

            return $this->successResponse(
                $business,
                'La empresa fue registrada exitosamente.',
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

    public function edit(BusinessEditRequest $request, $id)
    {
        try {
            if($request->filled('country_id')) {
                return $this->successResponse(
                    Departament::where('country_id', '=', $request->input('country_id'))->get(),
                    'Departamentos encontrados con exito.',
                    200
                );
            }

            if($request->filled('departament_id')) {
                return $this->successResponse(
                    City::where('departament_id', '=', $request->input('departament_id'))->get(),
                    'Ciudades encontradas con exito.',
                    200
                );
            }

            return $this->successResponse(
                (object) [
                    'business' => Business::withTrashed()->findOrFail($id),
                    'countries' => Country::all()
                ],
                'La empresa fue encontrada exitosamente.',
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

    public function update(BusinessUpdateRequest $request, $id)
    {
        try {
            $business = Business::withTrashed()->findOrFail($id);
            $business->name = $request->input('name');
            $business->document_number = $request->input('document_number');
            $business->telephone_number = $request->input('telephone_number');
            $business->email = $request->input('email');
            $business->country_id = $request->input('country_id');
            $business->departament_id = $request->input('departament_id');
            $business->city_id = $request->input('city_id');
            $business->address = $request->input('address');
            $business->neighbourhood = $request->input('neighbourhood');
            $business->description = $request->input('description');
            $business->save();

            return $this->successResponse(
                $business,
                'La empresa fue actualizada exitosamente.',
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

    public function delete(BusinessDeleteRequest $request)
    {
        try {
            $business = Business::withTrashed()->findOrFail($request->input('id'))->delete();

            return $this->successResponse(
                $business,
                'La empresa fue eliminada exitosamente.',
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

    public function restore(BusinessRestoreRequest $request)
    {
        try {
            $business = Business::withTrashed()->findOrFail($request->input('id'))->restore();
            return $this->successResponse(
                $business,
                'La empresa fue restaurada exitosamente.',
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
