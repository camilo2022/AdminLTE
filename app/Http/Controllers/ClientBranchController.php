<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientBranch\ClientBranchCreateRequest;
use App\Http\Requests\ClientBranch\ClientBranchDeleteRequest;
use App\Http\Requests\ClientBranch\ClientBranchEditRequest;
use App\Http\Requests\ClientBranch\ClientBranchIndexQueryRequest;
use App\Http\Requests\ClientBranch\ClientBranchIndexRequest;
use App\Http\Requests\ClientBranch\ClientBranchRestoreRequest;
use App\Http\Requests\ClientBranch\ClientBranchStoreRequest;
use App\Http\Requests\ClientBranch\ClientBranchUpdateRequest;
use App\Http\Resources\ClientBranch\ClientBranchIndexQueryCollection;
use App\Models\City;
use App\Models\Client;
use App\Models\ClientBranch;
use App\Models\Country;
use App\Models\Departament;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class ClientBranchController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index(ClientBranchIndexRequest $request)
    {
        try {
            return $this->successResponse(
                Client::withTrashed()->findOrFail($request->input('client_id')),
                'Cargando registros de las sucursales del cliente.',
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

    public function indexQuery(ClientBranchIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $clientBranchBranches = ClientBranch::with(['country', 'departament', 'city',
                    'client' => function ($query) { $query->withTrashed(); },
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
                ->where('client_id', '=', $request->input('client_id'))
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new ClientBranchIndexQueryCollection($clientBranchBranches),
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

    public function create(ClientBranchCreateRequest $request)
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

    public function store(ClientBranchStoreRequest $request)
    {
        try {
            $clientBranch = new ClientBranch();
            $clientBranch->client_id = $request->input('client_id');
            $clientBranch->code = $request->input('code');
            $clientBranch->country_id = $request->input('country_id');
            $clientBranch->departament_id = $request->input('departament_id');
            $clientBranch->city_id = $request->input('city_id');
            $clientBranch->address = $request->input('address');
            $clientBranch->neighbourhood = $request->input('neighbourhood');
            $clientBranch->description = $request->input('description');
            $clientBranch->email = $request->input('email');
            $clientBranch->telephone_number_first = $request->input('telephone_number_first');
            $clientBranch->telephone_number_second = $request->input('telephone_number_second');
            $clientBranch->save();

            return $this->successResponse(
                $clientBranch,
                'La sucursal del cliente fue registrada exitosamente.',
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

    public function edit(ClientBranchEditRequest $request, $id)
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
                    'clientBranch' => ClientBranch::withTrashed()->findOrFail($id),
                    'countries' => Country::all(),
                ],
                'La sucursal del cliente fue encontrada exitosamente.',
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

    public function update(ClientBranchUpdateRequest $request, $id)
    {
        try {
            $clientBranch = ClientBranch::withTrashed()->findOrFail($id);
            $clientBranch->code = $request->input('code');
            $clientBranch->country_id = $request->input('country_id');
            $clientBranch->departament_id = $request->input('departament_id');
            $clientBranch->city_id = $request->input('city_id');
            $clientBranch->address = $request->input('address');
            $clientBranch->neighbourhood = $request->input('neighbourhood');
            $clientBranch->description = $request->input('description');
            $clientBranch->email = $request->input('email');
            $clientBranch->telephone_number_first = $request->input('telephone_number_first');
            $clientBranch->telephone_number_second = $request->input('telephone_number_second');
            $clientBranch->save();

            return $this->successResponse(
                $clientBranch,
                'La sucursal del cliente fue actualizada exitosamente.',
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

    public function delete(ClientBranchDeleteRequest $request)
    {
        try {
            $clientBranch = ClientBranch::withTrashed()->findOrFail($request->input('id'))->delete();
            return $this->successResponse(
                $clientBranch,
                'La sucursal del cliente fue eliminada exitosamente.',
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

    public function restore(ClientBranchRestoreRequest $request)
    {
        try {
            $clientBranch = ClientBranch::withTrashed()->findOrFail($request->input('id'))->restore();
            return $this->successResponse(
                $clientBranch,
                'La sucursal del cliente fue restaurada exitosamente.',
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
