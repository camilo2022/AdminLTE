<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\SupplierCreateRequest;
use App\Http\Requests\Supplier\SupplierDeleteRequest;
use App\Http\Requests\Supplier\SupplierEditRequest;
use App\Http\Requests\Supplier\SupplierIndexQueryRequest;
use App\Http\Requests\Supplier\SupplierRestoreRequest;
use App\Http\Requests\Supplier\SupplierStoreRequest;
use App\Http\Requests\Supplier\SupplierUpdateRequest;
use App\Http\Resources\Supplier\SupplierIndexQueryCollection;
use App\Models\City;
use App\Models\Country;
use App\Models\Departament;
use App\Models\DocumentType;
use App\Models\PersonType;
use App\Models\Supplier;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class SupplierController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.Suppliers.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(SupplierIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $suppliers = Supplier::with([
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
                new SupplierIndexQueryCollection($suppliers),
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

    public function create(SupplierCreateRequest $request)
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

    public function store(SupplierStoreRequest $request)
    {
        try {
            $supplier = new Supplier();
            $supplier->name = $request->input('name');
            $supplier->person_type_id = $request->input('person_type_id');
            $supplier->document_type_id = $request->input('document_type_id');
            $supplier->document_number = $request->input('document_number');
            $supplier->country_id = $request->input('country_id');
            $supplier->departament_id = $request->input('departament_id');
            $supplier->city_id = $request->input('city_id');
            $supplier->address = $request->input('address');
            $supplier->neighborhood = $request->input('neighborhood');
            $supplier->email = $request->input('email');
            $supplier->telephone_number_first = $request->input('telephone_number_first');
            $supplier->telephone_number_second = $request->input('telephone_number_second');
            $supplier->save();

            return $this->successResponse(
                $supplier,
                'El Proveedor fue registrado exitosamente.',
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

    public function edit(SupplierEditRequest $request, $id)
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
                    'Supplier' => Supplier::withTrashed()->findOrFail($id),
                    'countries' => Country::all(),
                    'personTypes' => PersonType::all()
                ],
                'El Proveedor fue encontrado exitosamente.',
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

    public function update(SupplierUpdateRequest $request, $id)
    {
        try {
            $supplier = Supplier::withTrashed()->findOrFail($id);
            $supplier->name = $request->input('name');
            $supplier->person_type_id = $request->input('person_type_id');
            $supplier->document_type_id = $request->input('document_type_id');
            $supplier->document_number = $request->input('document_number');
            $supplier->country_id = $request->input('country_id');
            $supplier->departament_id = $request->input('departament_id');
            $supplier->city_id = $request->input('city_id');
            $supplier->address = $request->input('address');
            $supplier->neighborhood = $request->input('neighborhood');
            $supplier->email = $request->input('email');
            $supplier->telephone_number_first = $request->input('telephone_number_first');
            $supplier->telephone_number_second = $request->input('telephone_number_second');
            $supplier->save();

            return $this->successResponse(
                $supplier,
                'El Proveedor fue actualizado exitosamente.',
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

    public function delete(SupplierDeleteRequest $request)
    {
        try {
            $supplier = Supplier::withTrashed()->findOrFail($request->input('id'))->delete();
            return $this->successResponse(
                $supplier,
                'El Proveedor fue eliminado exitosamente.',
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

    public function restore(SupplierRestoreRequest $request)
    {
        try {
            $supplier = Supplier::withTrashed()->findOrFail($request->input('id'))->restore();
            return $this->successResponse(
                $supplier,
                'El Proveedor fue restaurado exitosamente.',
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
