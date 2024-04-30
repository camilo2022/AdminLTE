<?php

namespace App\Http\Controllers;

use App\Exports\Supply\SupplyExport;
use App\Models\Supply;
use App\Http\Controllers\Controller;
use App\Http\Requests\Supply\SupplyChargeRequest;
use App\Http\Requests\Supply\SupplyCreateRequest;
use App\Http\Requests\Supply\SupplyDeleteRequest;
use App\Http\Requests\Supply\SupplyDestroyRequest;
use App\Http\Requests\Supply\SupplyEditRequest;
use App\Http\Requests\Supply\SupplyIndexQueryRequest;
use App\Http\Requests\Supply\SupplyMasiveRequest;
use App\Http\Requests\Supply\SupplyRestoreRequest;
use App\Http\Requests\Supply\SupplyStoreRequest;
use App\Http\Requests\Supply\SupplyUpdateRequest;
use App\Http\Requests\Supply\SupplyUploadRequest;
use App\Http\Resources\Supply\SupplyIndexQueryCollection;
use App\Imports\Supply\SupplyImport;
use App\Models\ClothComposition;
use App\Models\ClothType;
use App\Models\Color;
use App\Models\File;
use App\Models\MeasurementUnit;
use App\Models\Supplier;
use App\Models\SupplyType;
use App\Models\Trademark;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class SupplyController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.Supplies.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(SupplyIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $supplies = Supply::with([
                    'supplier' => fn($query) => $query->withTrashed(),
                    'supply_type' => fn($query) => $query->withTrashed(),
                    'cloth_type' => fn($query) => $query->withTrashed(),
                    'cloth_composition' => fn($query) => $query->withTrashed(),
                    'measurement_unit' => fn($query) => $query->withTrashed(),
                    'color' => fn($query) => $query->withTrashed(),
                    'trademark' => fn($query) => $query->withTrashed()
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
                new SupplyIndexQueryCollection($supplies),
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

    public function create(SupplyCreateRequest $request)
    {
        try {
            if($request->filled('supply_type_id')) {
                $supply_type = SupplyType::find($request->input('supply_type_id'));
                if ($supply_type && $supply_type->is_cloth) {
                    return $this->successResponse(
                        [
                            'cloth_type' => ClothType::all(),
                            'cloth_composition' => ClothComposition::all(),
                        ],
                        'Categorias encontrados con exito.',
                        200
                    );
                } else {
                    return $this->successResponse(
                        '',
                        'Categorias encontrados con exito.',
                        200
                    );
                }
            }

            return $this->successResponse(
                [
                    'supplier' => Supplier::all(),
                    'supply_type' => SupplyType::all(),
                    'measurement_unit' => MeasurementUnit::all(),
                    'color' => Color::all(),
                    'trademark' => Trademark::all()
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

    public function store(SupplyStoreRequest $request)
    {
        try {
            $supply = new Supply();
            $supply->supplier_id = $request->input('supplier_id');
            $supply->supply_type_id = $request->input('supply_type_id');
            $supply->cloth_type_id = $request->input('cloth_type_id');
            $supply->cloth_composition_id = $request->input('cloth_composition_id');
            $supply->name = $request->input('name');
            $supply->code = $request->input('code');
            $supply->description = $request->input('description');
            $supply->quantity = $request->input('quantity');
            $supply->quality = $request->input('quality');
            $supply->width = $request->input('width');
            $supply->length = $request->input('length');
            $supply->measurement_unit_id = $request->input('measurement_unit_id');
            $supply->color_id = $request->input('color_id');
            $supply->trademark_id = $request->input('trademark_id');
            $supply->price_with_vat = $request->input('price_with_vat');
            $supply->price_without_vat = $request->input('price_without_vat');
            $supply->save();

            return $this->successResponse(
                $supply,
                'El insumo fue registrado exitosamente.',
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

    public function edit(SupplyEditRequest $request, $id)
    {
        try {
            if($request->filled('supply_type_id')) {
                $supply_type = SupplyType::find($request->input('supply_type_id'));
                if ($supply_type && $supply_type->is_cloth) {
                    return $this->successResponse(
                        [
                            'cloth_type' => ClothType::all(),
                            'cloth_composition' => ClothComposition::all(),
                        ],
                        'Categorias encontrados con exito.',
                        200
                    );
                } else {
                    return $this->successResponse(
                        '',
                        'Categorias encontrados con exito.',
                        200
                    );
                }
            }

            return $this->successResponse(
                [
                    'supply' => Supply::findOrFail($id),
                    'supplier' => Supplier::all(),
                    'supply_type' => SupplyType::all(),
                    'measurement_unit' => MeasurementUnit::all(),
                    'color' => Color::all(),
                    'trademark' => Trademark::all()
                ],
                'El insumo fue encontrado exitosamente.',
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

    public function update(SupplyUpdateRequest $request, $id)
    {
        try {
            $supply = Supply::withTrashed()->findOrFail($id);
            $supply->supplier_id = $request->input('supplier_id');
            $supply->supply_type_id = $request->input('supply_type_id');
            $supply->cloth_type_id = $request->input('cloth_type_id');
            $supply->cloth_composition_id = $request->input('cloth_composition_id');
            $supply->name = $request->input('name');
            $supply->code = $request->input('code');
            $supply->description = $request->input('description');
            $supply->quantity = $request->input('quantity');
            $supply->quality = $request->input('quality');
            $supply->width = $request->input('width');
            $supply->length = $request->input('length');
            $supply->measurement_unit_id = $request->input('measurement_unit_id');
            $supply->color_id = $request->input('color_id');
            $supply->trademark_id = $request->input('trademark_id');
            $supply->price_with_vat = $request->input('price_with_vat');
            $supply->price_without_vat = $request->input('price_without_vat');
            $supply->save();

            return $this->successResponse(
                $supply,
                'El insumo fue actualizado exitosamente.',
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
                [
                    'supply' => Supply::with('files')->findOrFail($id)
                ],
                'El insumo fue encontrado exitosamente.',
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

    public function charge(SupplyChargeRequest $request)
    {
        try {
            foreach ($request->file('files') as $archive) {
                $file = new File();
                $file->model_type = Supply::class;
                $file->model_id = $request->input('id');
                $file->name = $archive->getClientOriginalName();
                $file->path = $archive->store('Supplies/' . $request->input('id'), 'public');
                $file->mime = $archive->getMimeType();
                $file->extension = $archive->getClientOriginalExtension();
                $file->size = $archive->getSize();
                $file->user_id = Auth::user()->id;
                $file->metadata = json_encode((array) stat($archive));
                $file->save();
            }
            return $this->successResponse(
                '',
                'Los archivos del insumo fueron cargados exitosamente.',
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

    public function destroy(SupplyDestroyRequest $request)
    {
        try {
            $file = File::findOrFail($request->input('id'));
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
            $file->delete();

            return $this->successResponse(
                $file,
                'La imagen del insumo fue eliminado exitosamente.',
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

    public function delete(SupplyDeleteRequest $request)
    {
        try {
            $supply = Supply::withTrashed()->findOrFail($request->input('id'))->delete();
            return $this->successResponse(
                $supply,
                'El insumo fue eliminado exitosamente.',
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

    public function restore(SupplyRestoreRequest $request)
    {
        try {
            $supply = Supply::withTrashed()->findOrFail($request->input('id'))->restore();
            return $this->successResponse(
                $supply,
                'El insumo fue restaurado exitosamente.',
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

    public function upload(SupplyUploadRequest $request)
    {
        try {
            $supplies = Excel::toCollection(new SupplyImport, $request->file('supplies'))->first();

            $suppliesValidate = new SupplyMasiveRequest();
            $suppliesValidate->merge([
                'supplies' => $supplies->toArray(),
            ]);

            $validator = Validator::make(
                $suppliesValidate->all(),
                $suppliesValidate->rules(),
                $suppliesValidate->messages()
            );

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            foreach($supplies as $supply) {
                $supply->supplier_id = $request->input('supplier_id');
                $supply->supply_type_id = $request->input('supply_type_id');
                $supply->cloth_type_id = $request->input('cloth_type_id');
                $supply->cloth_composition_id = $request->input('cloth_composition_id');
                $supply->name = $request->input('name');
                $supply->code = $request->input('code');
                $supply->description = $request->input('description');
                $supply->quantity = $request->input('quantity');
                $supply->quality = $request->input('quality');
                $supply->width = $request->input('width');
                $supply->length = $request->input('length');
                $supply->measurement_unit_id = $request->input('measurement_unit_id');
                $supply->color_id = $request->input('color_id');
                $supply->trademark_id = $request->input('trademark_id');
                $supply->price_with_vat = $request->input('price_with_vat');
                $supply->price_without_vat = $request->input('price_without_vat');

                $existSupply = Supply::where('supplier_id', $supply['supplier_id'])
                ->where('supply_type_id', $supply['supply_type_id'])
                ->where('cloth_type_id', $supply['cloth_type_id'])
                ->where('cloth_composition_id', $supply['cloth_composition_id'])
                ->where('code', $supply['code'])
                ->where('quality', $supply['quality'])
                ->where('width', $supply['width'])
                ->where('length', $supply['length'])
                ->where('measurement_unit_id', $supply['measurement_unit_id'])
                ->where('color_id', $supply['color_id'])
                ->where('trademark_id', $supply['trademark_id'])
                ->first();

                if($existSupply) {
                    $existSupply->name = $supply['name'];
                    $existSupply->description = $supply['description'];
                    $existSupply->quantity = ($existSupply->quantity + $supply['quantity']) >= 0 ? $supply['quantity'] : 0;
                    $existSupply->price_with_vat = $supply['price_with_vat'];
                    $existSupply->price_without_vat = $supply['price_without_vat'];
                    $existSupply->save();
                } else {
                    $supplyNew = new Supply();
                    $supplyNew->supplier_id = $request['supplier_id'];
                    $supplyNew->supply_type_id = $request['supply_type_id'];
                    $supplyNew->cloth_type_id = $request['cloth_type_id'];
                    $supplyNew->cloth_composition_id = $request['cloth_composition_id'];
                    $supplyNew->name = $request['name'];
                    $supplyNew->code = $request['code'];
                    $supplyNew->description = $request['description'];
                    $supplyNew->quantity = $request['quantity'];
                    $supplyNew->quality = $request['quality'];
                    $supplyNew->width = $request['width'];
                    $supplyNew->length = $request['length'];
                    $supplyNew->measurement_unit_id = $request['measurement_unit_id'];
                    $supplyNew->color_id = $request['color_id'];
                    $supplyNew->trademark_id = $request['trademark_id'];
                    $supplyNew->price_with_vat = $request['price_with_vat'];
                    $supplyNew->price_without_vat = $request['price_without_vat'];
                    $supplyNew->save();
                }
            }

            return $this->successResponse(
                $supplies,
                'Los insumos fueron registrados exitosamente.',
                201
            );
        } catch (ValidationException $e) {
            // Maneja los errores de validación del nuevo formulario y retorna una respuesta de error
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ValidationException'),
                    'errors' => $e->errors(),
                ],
                422
            );
        } catch (Exception $e) {
            // Devuelve una respuesta de error en caso de excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function download()
    {
        try {
            $supplies = Supply::with([
                    'supplier' => fn($query) => $query->withTrashed(),
                    'supply_type' => fn($query) => $query->withTrashed(),
                    'cloth_type' => fn($query) => $query->withTrashed(),
                    'cloth_composition' => fn($query) => $query->withTrashed(),
                    'measurement_unit' => fn($query) => $query->withTrashed(),
                    'color' => fn($query) => $query->withTrashed(),
                    'trademark' => fn($query) => $query->withTrashed()
                ])
                ->get();

            return Excel::download(new SupplyExport($supplies),"INSUMOS.xlsx");
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
