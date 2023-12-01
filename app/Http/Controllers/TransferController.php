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
            $tranfers = Transfer::with([
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
                ->orderBy($request->input('column'), $request->input('dir'))
                ->withTrashed() //Trae los registros 'eliminados'
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new TransferIndexQueryCollection($tranfers),
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
            $tranfer = new Transfer();
            $tranfer->from_user_id = Auth::user()->id;
            $tranfer->from_date = Carbon::now()->format('Y-m-d H:i:s');
            $tranfer->from_observation = $request->input('from_observation');
            $tranfer->status = 'Pendiente';
            $tranfer->save();

            return $this->successResponse(
                $tranfer,
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
            $tranfer = Transfer::findOrFail($id);
            $tranfer->from_observation = $request->input('from_observation');
            $tranfer->save();

            return $this->successResponse(
                $tranfer,
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
            $tranfer = Transfer::findOrFail($id);
            return view('Dashboard.Transfers.Index', compact('tranfer'));
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function delete(TransferDeleteRequest $request)
    {
        try {
            $tranfer = Transfer::with('details')->findOrFail($request->input('id'));

            foreach ($tranfer->details as $detail) {
                $inventory = Inventory::with('product', 'size', 'warehouse', 'color')
                ->whereHas('product', fn($subQuery) => $subQuery->where('id', $detail->product_id))
                ->whereHas('size', fn($subQuery) => $subQuery->where('id', $detail->size_id))
                ->whereHas('warehouse', fn($subQuery) => $subQuery->where('id', $detail->from_warehouse_id))
                ->whereHas('color', fn($subQuery) => $subQuery->where('id', $detail->color_id))
                ->first();

                $inventory->quantity += $detail->quantity;
                $inventory->save();
                $detail->delete();
            }

            $tranfer->delete();
            return $this->successResponse(
                $tranfer,
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
            $tranfer = Transfer::with('details')->findOrFail($request->input('id'));
            $tranfer->status = 'Aprobado';
            $tranfer->save();

            foreach ($tranfer->details as $detail) {
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
                $tranfer,
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
            $tranfer = Transfer::with('details')->findOrFail($request->input('id'));
            $tranfer->status = 'Cancelado';
            $tranfer->save();

            foreach ($tranfer->details as $detail) {
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
                $tranfer,
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
