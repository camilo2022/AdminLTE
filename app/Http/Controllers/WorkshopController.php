<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Workshop\WorkshopCreateRequest;
use App\Http\Requests\Workshop\WorkshopDeleteRequest;
use App\Http\Requests\Workshop\WorkshopEditRequest;
use App\Http\Requests\Workshop\WorkshopIndexQueryRequest;
use App\Http\Requests\Workshop\WorkshopRestoreRequest;
use App\Http\Requests\Workshop\WorkshopStoreRequest;
use App\Http\Requests\Workshop\WorkshopUpdateRequest;
use App\Http\Resources\Workshop\WorkshopIndexQueryCollection;
use App\Models\City;
use App\Models\Country;
use App\Models\Departament;
use App\Models\DocumentType;
use App\Models\PersonType;
use App\Models\Workshop;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class WorkshopController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.Workshops.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(WorkshopIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $workshops = Workshop::with([
                    'country', 'departament', 'city',
                    'document_type' => fn($query) => $query->withTrashed()
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

            return $this->successResponse(
                new WorkshopIndexQueryCollection($workshops),
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

    public function create(WorkshopCreateRequest $request)
    {
        try {
            if($request->filled('person_type_id')) {
                return $this->successResponse(
                    DocumentType::with('person_types')->whereHas('person_types', fn($subQuery) => $subQuery->where('person_type_id', $request->input('person_type_id')))->get(),
                    'Tipos de documento encontrados con exito.',
                    200
                );
            }

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
                [
                    'countries' => Country::all(),
                    'personTypes' => PersonType::all(),
                ],
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

    public function store(WorkshopStoreRequest $request)
    {
        try {
            $workshop = new Workshop();
            $workshop->name = $request->input('name');
            $workshop->person_type_id = $request->input('person_type_id');
            $workshop->document_type_id = $request->input('document_type_id');
            $workshop->document_number = $request->input('document_number');
            $workshop->country_id = $request->input('country_id');
            $workshop->departament_id = $request->input('departament_id');
            $workshop->city_id = $request->input('city_id');
            $workshop->address = $request->input('address');
            $workshop->neighborhood = $request->input('neighborhood');
            $workshop->email = $request->input('email');
            $workshop->telephone_number_first = $request->input('telephone_number_first');
            $workshop->telephone_number_second = $request->input('telephone_number_second');
            $workshop->save();

            return $this->successResponse(
                $workshop,
                'El Taller fue registrado exitosamente.',
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

    public function edit(WorkshopEditRequest $request, $id)
    {
        try {
            if($request->filled('person_type_id')) {
                return $this->successResponse(
                    DocumentType::with('person_types')->whereHas('person_types', fn($subQuery) => $subQuery->where('person_type_id', $request->input('person_type_id')))->get(),
                    'Tipos de documento encontrados con exito.',
                    200
                );
            }

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
                [
                    'workshop' => Workshop::withTrashed()->findOrFail($id),
                    'countries' => Country::all(),
                    'personTypes' => PersonType::all()
                ],
                'El Taller fue encontrado exitosamente.',
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

    public function update(WorkshopUpdateRequest $request, $id)
    {
        try {
            $workshop = Workshop::withTrashed()->findOrFail($id);
            $workshop->name = $request->input('name');
            $workshop->person_type_id = $request->input('person_type_id');
            $workshop->document_type_id = $request->input('document_type_id');
            $workshop->document_number = $request->input('document_number');
            $workshop->country_id = $request->input('country_id');
            $workshop->departament_id = $request->input('departament_id');
            $workshop->city_id = $request->input('city_id');
            $workshop->address = $request->input('address');
            $workshop->neighborhood = $request->input('neighborhood');
            $workshop->email = $request->input('email');
            $workshop->telephone_number_first = $request->input('telephone_number_first');
            $workshop->telephone_number_second = $request->input('telephone_number_second');
            $workshop->save();

            return $this->successResponse(
                $workshop,
                'El Taller fue actualizado exitosamente.',
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

    public function delete(WorkshopDeleteRequest $request)
    {
        try {
            $workshop = Workshop::withTrashed()->findOrFail($request->input('id'))->delete();
            return $this->successResponse(
                $workshop,
                'El Taller fue eliminado exitosamente.',
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

    public function restore(WorkshopRestoreRequest $request)
    {
        try {
            $workshop = Workshop::withTrashed()->findOrFail($request->input('id'))->restore();
            return $this->successResponse(
                $workshop,
                'El Taller fue restaurado exitosamente.',
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
