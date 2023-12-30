<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonType\PersonTypeAssignDocumentTypeRequest;
use App\Http\Requests\PersonType\PersonTypeDeleteRequest;
use App\Http\Requests\PersonType\PersonTypeIndexQueryRequest;
use App\Http\Requests\PersonType\PersonTypeRemoveDocumentTypeRequest;
use App\Http\Requests\PersonType\PersonTypeRestoreRequest;
use App\Http\Requests\PersonType\PersonTypeStoreRequest;
use App\Http\Requests\PersonType\PersonTypeUpdateRequest;
use App\Http\Resources\PersonType\PersonTypeIndexQueryCollection;
use App\Models\DocumentType;
use App\Models\PersonType;
use App\Models\PersonTypeDocumentType;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class PersonTypeController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.PersonTypes.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(PersonTypeIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $personTypes = PersonType::when($request->filled('search'),
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
                new PersonTypeIndexQueryCollection($personTypes),
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

    public function store(PersonTypeStoreRequest $request)
    {
        try {
            $personType = new PersonType();
            $personType->name = $request->input('name');
            $personType->code = $request->input('code');
            $personType->require_people = $request->input('require_people');
            $personType->save();

            return $this->successResponse(
                $personType,
                'El tipo de persona fue registrado exitosamente.',
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
            return $this->successResponse(
                PersonType::withTrashed()->findOrFail($id),
                'El tipo de persona fue encontrado exitosamente.',
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

    public function update(PersonTypeUpdateRequest $request, $id)
    {
        try {
            $personType = PersonType::withTrashed()->findOrFail($id);
            $personType->name = $request->input('name');
            $personType->code = $request->input('code');
            $personType->require_people = $request->input('require_people');
            $personType->save();

            return $this->successResponse(
                $personType,
                'El tipo de persona fue actualizada exitosamente.',
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
            $documentTypes = DocumentType::with('person_types')->get();
            $personType = PersonType::findOrFail($id);

            foreach ($documentTypes as $documentType) {
                $personTypesId = $documentType->person_types->pluck('id')->all();
                $documentType->admin = in_array($id, $personTypesId);
            }

            return $this->successResponse(
                [
                    'personType' => $personType,
                    'documentTypes' => $documentTypes
                ],
                'El tipo de persona fue encontrado exitosamente.',
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

    public function assignDocumentType(PersonTypeAssignDocumentTypeRequest $request)
    {
        try {
            $personType_documentTypes = new PersonTypeDocumentType();
            $personType_documentTypes->person_type_id = $request->input('person_type_id');
            $personType_documentTypes->document_type_id = $request->input('document_type_id');
            $personType_documentTypes->save();

            return $this->successResponse(
                $personType_documentTypes,
                'Tipo de documento asignado exitosamente.',
                200
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

    public function removeDocumentType(PersonTypeRemoveDocumentTypeRequest $request)
    {
        try {
            $personType_documentTypes = PersonTypeDocumentType::where('person_type_id', '=', $request->input('person_type_id'))
            ->where('document_type_id', '=', $request->input('document_type_id'))->delete();

            return $this->successResponse(
                $personType_documentTypes,
                'Tipo de documento removido exitosamente.',
                200
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

    public function delete(PersonTypeDeleteRequest $request)
    {
        try {
            $personType = PersonType::withTrashed()->findOrFail($request->input('id'))->delete();
            return $this->successResponse(
                $personType,
                'El tipo de persona fue eliminada exitosamente.',
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

    public function restore(PersonTypeRestoreRequest $request)
    {
        try {
            $personType = PersonType::withTrashed()->findOrFail($request->input('id'))->restore();
            return $this->successResponse(
                $personType,
                'El tipo de persona fue restaurado exitosamente.',
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
