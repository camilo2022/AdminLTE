<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ClientCreateRequest;
use App\Http\Requests\Client\ClientDeleteRequest;
use App\Http\Requests\Client\ClientEditRequest;
use App\Http\Requests\Client\ClientIndexQueryRequest;
use App\Http\Requests\Client\ClientQuotaRequest;
use App\Http\Requests\Client\ClientRestoreRequest;
use App\Http\Requests\Client\ClientStoreRequest;
use App\Http\Requests\Client\ClientUpdateRequest;
use App\Http\Resources\Client\ClientIndexQueryCollection;
use App\Models\City;
use App\Models\Client;
use App\Models\ClientType;
use App\Models\Country;
use App\Models\Departament;
use App\Models\DocumentType;
use App\Models\PersonType;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class ClientController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.Clients.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(ClientIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $clients = Client::with(['country', 'departament', 'city',
                'person_type' => function ($query) { $query->withTrashed(); },
                'client_type' => function ($query) { $query->withTrashed(); },
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
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new ClientIndexQueryCollection($clients),
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

    public function create(ClientCreateRequest $request)
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
                    'person_types' => PersonType::all(),
                    'client_types' => ClientType::all(),
                    'document_types' => DocumentType::all()
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

    public function store(ClientStoreRequest $request)
    {
        try {
            $client = new Client();
            $client->name = $request->input('name');
            $client->person_type_id = $request->input('person_type_id');
            $client->client_type_id = $request->input('client_type_id');
            $client->document_type_id = $request->input('document_type_id');
            $client->document_number = $request->input('document_number');
            $client->country_id = $request->input('country_id');
            $client->departament_id = $request->input('departament_id');
            $client->city_id = $request->input('city_id');
            $client->address = $request->input('address');
            $client->neighbourhood = $request->input('neighbourhood');
            $client->email = $request->input('email');
            $client->telephone_number_first = $request->input('telephone_number_first');
            $client->telephone_number_second = $request->input('telephone_number_second');
            $client->save();

            return $this->successResponse(
                $client,
                'El cliente fue registrado exitosamente.',
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

    public function edit(ClientEditRequest $request, $id)
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
                    'client' => Client::withTrashed()->findOrFail($id),
                    'countries' => Country::all(),
                    'person_types' => PersonType::all(),
                    'client_types' => ClientType::all(),
                    'document_types' => DocumentType::all()
                ],
                'El cliente fue encontrado exitosamente.',
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

    public function update(ClientUpdateRequest $request, $id)
    {
        try {
            $client = Client::withTrashed()->findOrFail($id);
            $client->name = $request->input('name');
            $client->person_type_id = $request->input('person_type_id');
            $client->client_type_id = $request->input('client_type_id');
            $client->document_type_id = $request->input('document_type_id');
            $client->document_number = $request->input('document_number');
            $client->country_id = $request->input('country_id');
            $client->departament_id = $request->input('departament_id');
            $client->city_id = $request->input('city_id');
            $client->address = $request->input('address');
            $client->neighbourhood = $request->input('neighbourhood');
            $client->email = $request->input('email');
            $client->telephone_number_first = $request->input('telephone_number_first');
            $client->telephone_number_second = $request->input('telephone_number_second');
            $client->save();

            return $this->successResponse(
                $client,
                'El cliente fue actualizado exitosamente.',
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
            return $this->successResponse(
                Client::withTrashed()->findOrFail($id),
                'El cliente fue encontrado exitosamente.',
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

    public function quota(ClientQuotaRequest $request, $id)
    {
        try {
            $client = Client::withTrashed()->findOrFail($id);
            $client->quota = $request->input('quota');
            $client->save();

            return $this->successResponse(
                $client,
                'El cupo disponible del cliente fue actualizado exitosamente.',
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

    public function delete(ClientDeleteRequest $request)
    {
        try {
            $client = Client::withTrashed()->findOrFail($request->input('id'))->delete();
            return $this->successResponse(
                $client,
                'El cliente fue eliminado exitosamente.',
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

    public function restore(ClientRestoreRequest $request)
    {
        try {
            $client = Client::withTrashed()->findOrFail($request->input('id'))->restore();
            return $this->successResponse(
                $client,
                'El cliente fue restaurado exitosamente.',
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
