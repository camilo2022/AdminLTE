<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transfer\TransferApproveRequest;
use App\Http\Requests\Transfer\TransferCancelRequest;
use App\Http\Requests\Transfer\TransferCreateRequest;
use App\Http\Requests\Transfer\TransferDeleteRequest;
use App\Http\Requests\Transfer\TransferEditRequest;
use App\Http\Requests\Transfer\TransferIndexQueryRequest;
use App\Http\Requests\Transfer\TransferStoreRequest;
use App\Http\Requests\Transfer\TransferUpdateRequest;
use App\Http\Resources\Transfer\TransferIndexQueryCollection;
use App\Models\Inventory;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Warehouse;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.Transfers.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(TransferIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $transfers = Transfer::with([
                    'from_warehouse' => function ($query) { $query->withTrashed(); },
                    'from_user' => function ($query) { $query->withTrashed(); },
                    'to_warehouse' => function ($query) { $query->withTrashed(); },
                    'to_user' => function ($query) { $query->withTrashed(); },
                    'details' => function ($query) { $query->withTrashed(); }
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
                ->withTrashed()
                ->transfersByAssingWarehouse()
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                [
                    'warehouses' => Warehouse::with('users')
                    ->whereHas('users',
                        function ($subQuery) {
                            $subQuery->where('user_id', Auth::user()->id);
                        }
                    )->pluck('id'),
                    'transfers' => new TransferIndexQueryCollection($transfers)
                ],
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

    public function create(TransferCreateRequest $request)
    {
        try {
            if($request->filled('from_warehouse_id')) {
                return $this->successResponse(
                    Warehouse::where('id', '!=', $request->input('from_warehouse_id'))->get(),
                    'Bodegas encontradas con exito.',
                    200
                );
            }

            return $this->successResponse(
                User::with('warehouses')->findOrFail(Auth::user()->id)->warehouses,
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

    public function store(TransferStoreRequest $request)
    {
        try {
            $transfer = new Transfer();
            $transfer->consecutive = DB::selectOne('CALL transfers()')->consecutive;
            $transfer->from_warehouse_id = $request->input('from_warehouse_id');
            $transfer->from_user_id = Auth::user()->id;
            $transfer->from_date = Carbon::now()->format('Y-m-d H:i:s');
            $transfer->from_observation = $request->input('from_observation');
            $transfer->to_warehouse_id = $request->input('to_warehouse_id');
            $transfer->status = 'Pendiente';
            $transfer->save();

            return $this->successResponse(
                $transfer,
                'La Transferencia fue registrado exitosamente.',
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

    public function edit(TransferEditRequest $request, $id)
    {
        try {
            if($request->filled('from_warehouse_id')) {
                return $this->successResponse(
                    Warehouse::where('id', '!=', $request->input('from_warehouse_id'))->get(),
                    'Bodegas encontradas con exito.',
                    200
                );
            }
            
            $transfer = Transfer::with('from_warehouse')->findOrFail($id);

            return $this->successResponse(
                [
                    'transfer' => $transfer,
                    'warehouses' => Warehouse::where('id', '!=', $transfer->from_warehouse_id)->get()
                ],
                'La Transferencia fue encontrado exitosamente.',
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

    public function update(TransferUpdateRequest $request, $id)
    {
        try {
            $transfer = Transfer::findOrFail($id);
            $transfer->from_observation = $request->input('from_observation');
            $transfer->to_warehouse_id = $request->input('to_warehouse_id');
            $transfer->save();

            return $this->successResponse(
                $transfer,
                'La Transferencia fue actualizado exitosamente.',
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
                Transfer::withTrashed()->findOrFail($id),
                'La Transferencia fue encontrado exitosamente.',
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

    public function delete(TransferDeleteRequest $request)
    {
        try {
            $transfer = Transfer::with('details')->findOrFail($request->input('id'));

            foreach ($transfer->details as $detail) {
                $inventory = Inventory::with('product', 'size', 'warehouse', 'color', 'tone')
                    ->whereHas('product', fn($subQuery) => $subQuery->where('id', $detail->product_id))
                    ->whereHas('size', fn($subQuery) => $subQuery->where('id', $detail->size_id))
                    ->whereHas('warehouse', fn($subQuery) => $subQuery->where('id', $transfer->from_warehouse_id))
                    ->whereHas('color', fn($subQuery) => $subQuery->where('id', $detail->color_id))
                    ->whereHas('tone', fn($subQuery) => $subQuery->where('id', $detail->tone_id))
                    ->first();

                $inventory->quantity += $detail->quantity;
                $inventory->save();
                $detail->status = 'Eliminado';
                $detail->save();
                $detail->delete();
            }

            $transfer->status = 'Eliminado';
            $transfer->save();
            $transfer->delete();
            return $this->successResponse(
                $transfer,
                'La Transferencia fue eliminada exitosamente.',
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

    public function approve(TransferApproveRequest $request)
    {
        try {
            $transfer = Transfer::with('details')->findOrFail($request->input('id'));
            $transfer->status = 'Aprobado';
            $transfer->save();

            foreach ($transfer->details as $detail) {
                $inventory = Inventory::with('product', 'size', 'warehouse', 'color', 'tone')
                    ->whereHas('product', fn($subQuery) => $subQuery->where('id', $detail->product_id))
                    ->whereHas('size', fn($subQuery) => $subQuery->where('id', $detail->size_id))
                    ->when($detail->status == 'Pendiente',
                        function ($query) use ($transfer) {
                            $query->whereHas('warehouse', fn($subQuery) => $subQuery->where('id', $transfer->to_warehouse_id));
                        }
                    )
                    ->when($detail->status == 'Cancelado',
                        function ($query) use ($transfer) {
                            $query->whereHas('warehouse', fn($subQuery) => $subQuery->where('id', $transfer->from_warehouse_id));
                        }
                    )
                    ->whereHas('color', fn($subQuery) => $subQuery->where('id', $detail->color_id))
                    ->whereHas('tone', fn($subQuery) => $subQuery->where('id', $detail->tone_id))
                    ->first();
                
                if(!$inventory && $detail->status == 'Pendiente') {
                    $inventory = new Inventory();
                    $inventory->product_id = $detail->product_id;
                    $inventory->size_id = $detail->size_id;
                    $inventory->warehouse_id = $transfer->to_warehouse_id;
                    $inventory->color_id = $detail->color_id;
                    $inventory->tone_id = $detail->tone_id;
                    $inventory->save();
                }

                $inventory->quantity += $detail->quantity;
                $inventory->save();
                $detail->status = $detail->status == 'Pendiente' ? 'Aprobado' : $detail->status;
                $detail->save();
            }

            return $this->successResponse(
                $transfer,
                'La Transferencia fue aprobada exitosamente.',
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

    public function cancel(TransferCancelRequest $request)
    {
        try {
            $transfer = Transfer::with('details')->findOrFail($request->input('id'));

            foreach ($transfer->details as $detail) {
                $inventory = Inventory::with('product', 'size', 'warehouse', 'color')
                    ->whereHas('product', fn($subQuery) => $subQuery->where('id', $detail->product_id))
                    ->whereHas('size', fn($subQuery) => $subQuery->where('id', $detail->size_id))
                    ->whereHas('warehouse', fn($subQuery) => $subQuery->where('id', $transfer->from_warehouse_id))
                    ->whereHas('color', fn($subQuery) => $subQuery->where('id', $detail->color_id))
                    ->whereHas('tone', fn($subQuery) => $subQuery->where('id', $detail->tone_id))
                    ->first();

                $inventory->quantity += $detail->quantity;
                $inventory->save();
                $detail->status = 'Cancelado';
                $detail->save();
            }

            $transfer->status = 'Cancelado';
            $transfer->save();

            return $this->successResponse(
                $transfer,
                'La Transferencia fue cancelada exitosamente.',
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
