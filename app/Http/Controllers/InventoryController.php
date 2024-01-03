<?php

namespace App\Http\Controllers;

use App\Exports\Inventory\InventoryExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\InventoryIndexQueryRequest;
use App\Http\Requests\Inventory\InventoryMasiveRequest;
use App\Http\Requests\Inventory\InventoryUploadRequest;
use App\Http\Resources\Inventory\InventoryIndexQueryCollection;
use App\Imports\Inventory\InventoryImport;
use App\Models\Inventory;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.Inventories.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(InventoryIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $inventories = Inventory::with([
                    'product' => fn($query) => $query->withTrashed(),
                    'size' => fn($query) => $query->withTrashed(),
                    'warehouse' => fn($query) => $query->withTrashed(),
                    'color' => fn($query) => $query->withTrashed(),
                    'tone' => fn($query) => $query->withTrashed(),
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
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new InventoryIndexQueryCollection($inventories),
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

    public function upload(InventoryUploadRequest $request)
    {
        try {
            $inventories = Excel::toCollection(new InventoryImport, $request->file('inventories'))->first();

            $inventoriesValidate = new InventoryMasiveRequest();
            $inventoriesValidate->merge([
                'inventories' => $inventories->toArray(),
            ]);

            $validator = Validator::make(
                $inventoriesValidate->all(),
                $inventoriesValidate->rules(),
                $inventoriesValidate->messages()
            );

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $groups = $inventories->groupBy('product_id', 'size_id', 'warehouse_id', 'color_id', 'tone_id');
            $inventories = $groups->map(function ($group) {
                return [
                    'product_id' => $group->first()['product_id'],
                    'size_id' => $group->first()['size_id'],
                    'warehouse_id' => $group->first()['warehouse_id'],
                    'color_id' => $group->first()['color_id'],
                    'tone_id' => $group->first()['tone_id'],
                    'quantity' => $group->sum('quantity')
                ];
            });

            foreach($inventories as $inventory) {
                $inventory = (object) $inventory;
                $existInventory = Inventory::where('product_id', '=', $inventory->product_id)
                ->where('size_id', '=', $inventory->size_id)->where('warehouse_id', '=', $inventory->warehouse_id)
                ->where('color_id', '=', $inventory->color_id)->where('tone_id', '=', $inventory->tone_id)->first();

                if($existInventory) {
                    $existInventory->quantity += ($existInventory->quantity + $inventory->quantity) >= 0 ? $inventory->quantity : 0;
                    $existInventory->save();
                } else {
                    $inventoryNew = new Inventory();
                    $inventoryNew->product_id = $inventory->product_id;
                    $inventoryNew->size_id = $inventory->size_id;
                    $inventoryNew->warehouse_id = $inventory->warehouse_id;
                    $inventoryNew->color_id = $inventory->color_id;
                    $inventoryNew->tone_id = $inventory->tone_id;
                    $inventoryNew->quantity = $inventory->quantity >= 0 ? $inventory->quantity : 0;
                    $inventoryNew->save();
                }
            }

            return $this->successResponse(
                '',
                'Los inventarios fueron registrados exitosamente.',
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
            $inventories = Inventory::with([
                    'product' => fn($query) => $query->withTrashed(),
                    'size' => fn($query) => $query->withTrashed(),
                    'warehouse' => fn($query) => $query->withTrashed(),
                    'color' => fn($query) => $query->withTrashed(),
                    'tone' => fn($query) => $query->withTrashed(),
                ])
                ->get();

            return Excel::download(new InventoryExport($inventories), "INVENTARIOS.xlsx");
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
