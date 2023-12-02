<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transfer\TransferApproveRequest;
use App\Http\Requests\Transfer\TransferCancelRequest;
use App\Http\Requests\Transfer\TransferDeleteRequest;
use App\Http\Requests\Transfer\TransferEditRequest;
use App\Http\Requests\Transfer\TransferIndexQueryRequest;
use App\Http\Requests\Transfer\TransferStoreRequest;
use App\Http\Requests\Transfer\TransferUpdateRequest;
use App\Http\Resources\Transfer\TransferIndexQueryCollection;
use App\Models\Inventory;
use App\Models\Transfer;
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
                    'send_user' => function ($query) { $query->withTrashed(); },
                    'receive_user' => function ($query) { $query->withTrashed(); },
                    'details',
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
                ->transfersByAssingWarehouse()
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new TransferIndexQueryCollection($transfers),
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
            $transfer->from_user_id = Auth::user()->id;
            $transfer->from_date = Carbon::now()->format('Y-m-d H:i:s');
            $transfer->from_observation = $request->input('from_observation');
            $transfer->status = 'Pendiente';
            $transfer->save();

            DB::statement('CALL transfers(?)', [$transfer->id]);

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

    public function edit($id)
    {
        try {
            return $this->successResponse(
                Transfer::findOrFail($id),
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
                Transfer::findOrFail($id),
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
                $inventory = Inventory::with('product', 'size', 'warehouse', 'color')
                ->whereHas('product', fn($subQuery) => $subQuery->where('id', $detail->product_id))
                ->whereHas('size', fn($subQuery) => $subQuery->where('id', $detail->size_id))
                ->whereHas('warehouse', fn($subQuery) => $subQuery->where('id', $detail->from_warehouse_id))
                ->whereHas('color', fn($subQuery) => $subQuery->where('id', $detail->color_id))
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
                $inventory = Inventory::with('product', 'size', 'warehouse', 'color')
                ->whereHas('product', fn($subQuery) => $subQuery->where('id', $detail->product_id))
                ->whereHas('size', fn($subQuery) => $subQuery->where('id', $detail->size_id))
                ->when($detail->status == 'Pendiente',
                    function ($query) use ($detail) {
                        $query->whereHas('warehouse', fn($subQuery) => $subQuery->where('id', $detail->from_warehouse_id));
                    }
                )
                ->when($detail->status == 'Cancelado',
                    function ($query) use ($detail) {
                        $query->whereHas('warehouse', fn($subQuery) => $subQuery->where('id', $detail->to_warehouse_id));
                    }
                )
                ->whereHas('color', fn($subQuery) => $subQuery->where('id', $detail->color_id))
                ->first();

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
            $transfer->status = 'Cancelado';
            $transfer->save();

            foreach ($transfer->details as $detail) {
                $inventory = Inventory::with('product', 'size', 'warehouse', 'color')
                ->whereHas('product', fn($subQuery) => $subQuery->where('id', $detail->product_id))
                ->whereHas('size', fn($subQuery) => $subQuery->where('id', $detail->size_id))
                ->whereHas('warehouse', fn($subQuery) => $subQuery->where('id', $detail->to_warehouse_id))
                ->whereHas('color', fn($subQuery) => $subQuery->where('id', $detail->color_id))
                ->first();

                $inventory->quantity += $detail->quantity;
                $inventory->save();
                $detail->status = 'Cancelado';
                $detail->save();
            }

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
