<?php

namespace App\Http\Controllers;

use App\Http\Requests\Person\PersonCreateRequest;
use App\Http\Requests\Person\PersonEditRequest;
use App\Http\Requests\Person\PersonStoreRequest;
use App\Http\Requests\Person\PersonUpdateRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Departament;
use App\Models\Person;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class PersonController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function create(PersonCreateRequest $request)
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

    public function store(PersonStoreRequest $request)
    {
        try {
            $person = new Person();
            $person->client_id = $request->input('client_id');
            $person->name = $request->input('name');
            $person->last_name = $request->input('last_name');
            $person->document_type_id = $request->input('document_type_id');
            $person->document_number = $request->input('document_number');
            $person->country_id = $request->input('country_id');
            $person->departament_id = $request->input('departament_id');
            $person->city_id = $request->input('city_id');
            $person->address = $request->input('address');
            $person->neighbourhood = $request->input('neighbourhood');
            $person->email = $request->input('email');
            $person->telephone_number_first = $request->input('telephone_number_first');
            $person->telephone_number_second = $request->input('telephone_number_second');
            $person->save();

            return $this->successResponse(
                $person,
                'La persona fue registrada exitosamente.',
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

    public function edit(PersonEditRequest $request, $id)
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
                    'person' => Person::withTrashed()->findOrFail($id),
                    'countries' => Country::all()
                ],
                'La persona fue encontrada exitosamente.',
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

    public function update(PersonUpdateRequest $request, $id)
    {
        try {
            $person = Person::withTrashed()->findOrFail($id);
            $person->name = $request->input('name');
            $person->last_name = $request->input('last_name');
            $person->document_type_id = $request->input('document_type_id');
            $person->document_number = $request->input('document_number');
            $person->country_id = $request->input('country_id');
            $person->departament_id = $request->input('departament_id');
            $person->city_id = $request->input('city_id');
            $person->address = $request->input('address');
            $person->neighbourhood = $request->input('neighbourhood');
            $person->email = $request->input('email');
            $person->telephone_number_first = $request->input('telephone_number_first');
            $person->telephone_number_second = $request->input('telephone_number_second');
            $person->save();

            return $this->successResponse(
                $person,
                'La persona fue actualizada exitosamente.',
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
