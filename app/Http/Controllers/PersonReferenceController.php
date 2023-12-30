<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonReference\PersonReferenceCreateRequest;
use App\Http\Requests\PersonReference\PersonReferenceDeleteRequest;
use App\Http\Requests\PersonReference\PersonReferenceEditRequest;
use App\Http\Requests\PersonReference\PersonReferenceIndexQueryRequest;
use App\Http\Requests\PersonReference\PersonReferenceIndexRequest;
use App\Http\Requests\PersonReference\PersonReferenceRestoreRequest;
use App\Http\Requests\PersonReference\PersonReferenceStoreRequest;
use App\Http\Requests\PersonReference\PersonReferenceUpdateRequest;
use App\Http\Resources\PersonReference\PersonReferenceIndexQueryCollection;
use App\Models\City;
use App\Models\Country;
use App\Models\Departament;
use App\Models\DocumentType;
use App\Models\Person;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class PersonReferenceController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index(PersonReferenceIndexRequest $request)
    {
        try {
            return $this->successResponse(
                Person::withTrashed()->findOrFail($request->input('person_id')),
                'Cargando registros de las referencias personales.',
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

    public function indexQuery(PersonReferenceIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $peopleReferences = Person::with(['model', 'country', 'departament', 'city',
                    'document_type' => function ($query) { $query->withTrashed(); }
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
                ->whereHasMorph('model', [Person::class], function ($query) use ($request) {
                    $query->where('model_id', $request->input('person_id'));
                })
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new PersonReferenceIndexQueryCollection($peopleReferences),
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

    public function create(PersonReferenceCreateRequest $request)
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
                [
                    'countries' => Country::all(),
                    'documentTypes' => DocumentType::all()
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

    public function store(PersonReferenceStoreRequest $request)
    {
        try {
            $personReference = new Person();
            $personReference->model_type = Person::class;
            $personReference->model_id = $request->input('person_id');
            $personReference->name = $request->input('name');
            $personReference->last_name = $request->input('last_name');
            $personReference->document_type_id = $request->input('document_type_id');
            $personReference->document_number = $request->input('document_number');
            $personReference->country_id = $request->input('country_id');
            $personReference->departament_id = $request->input('departament_id');
            $personReference->city_id = $request->input('city_id');
            $personReference->address = $request->input('address');
            $personReference->neighborhood = $request->input('neighborhood');
            $personReference->email = $request->input('email');
            $personReference->telephone_number_first = $request->input('telephone_number_first');
            $personReference->telephone_number_second = $request->input('telephone_number_second');
            $personReference->save();

            return $this->successResponse(
                $personReference,
                'La referencia personal fue registrada exitosamente.',
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

    public function edit(PersonReferenceEditRequest $request, $id)
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
                [
                    'personReference' => Person::withTrashed()->findOrFail($id),
                    'documentTypes' => DocumentType::all(),
                    'countries' => Country::all()
                ],
                'La referencia personal fue encontrada exitosamente.',
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

    public function update(PersonReferenceUpdateRequest $request, $id)
    {
        try {
            $personReference = Person::withTrashed()->findOrFail($id);
            $personReference->name = $request->input('name');
            $personReference->last_name = $request->input('last_name');
            $personReference->document_type_id = $request->input('document_type_id');
            $personReference->document_number = $request->input('document_number');
            $personReference->country_id = $request->input('country_id');
            $personReference->departament_id = $request->input('departament_id');
            $personReference->city_id = $request->input('city_id');
            $personReference->address = $request->input('address');
            $personReference->neighborhood = $request->input('neighborhood');
            $personReference->email = $request->input('email');
            $personReference->telephone_number_first = $request->input('telephone_number_first');
            $personReference->telephone_number_second = $request->input('telephone_number_second');
            $personReference->save();

            return $this->successResponse(
                $personReference,
                'La referencia personal fue actualizada exitosamente.',
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

    public function delete(PersonReferenceDeleteRequest $request)
    {
        try {
            $personReference = Person::withTrashed()->findOrFail($request->input('id'))->delete();
            return $this->successResponse(
                $personReference,
                'La referencia personal fue eliminada exitosamente.',
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

    public function restore(PersonReferenceRestoreRequest $request)
    {
        try {
            $personReference = Person::withTrashed()->findOrFail($request->input('id'))->restore();
            return $this->successResponse(
                $personReference,
                'La referencia personal fue restaurado exitosamente.',
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
