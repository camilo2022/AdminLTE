<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferDetail\TransferDetailCancelRequest;
use App\Http\Requests\TransferDetail\TransferDetailCreateRequest;
use App\Http\Requests\TransferDetail\TransferDetailDeleteRequest;
use App\Http\Requests\TransferDetail\TransferDetailEditRequest;
use App\Http\Requests\TransferDetail\TransferDetailIndexQueryRequest;
use App\Http\Requests\TransferDetail\TransferDetailPendingRequest;
use App\Http\Requests\TransferDetail\TransferDetailStoreRequest;
use App\Http\Requests\TransferDetail\TransferDetailUpdateRequest;
use App\Http\Resources\TransferDetail\TransferDetailIndexQueryCollection;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\TransferDetail;
use App\Models\Warehouse;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class TransferDetailController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function indexQuery(TransferDetailIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $tranferDetails = TransferDetail::with([
                    'transfer' => function ($query) { $query->withTrashed(); },
                    'product' => function ($query) { $query->withTrashed(); },
                    'size' => function ($query) { $query->withTrashed(); },
                    'color' => function ($query) { $query->withTrashed(); },
                    'tone' => function ($query) { $query->withTrashed(); },
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
                ->where('transfer_id', '=', $request->input('transfer_id'))
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
                    'transferDetails' => new TransferDetailIndexQueryCollection($tranferDetails)
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

    public function create(TransferDetailCreateRequest $request)
    {
        try {
            if($request->filled('warehouse_id') && $request->filled('product_id') && $request->filled('color_id') && $request->filled('tone_id')) {
                return $this->successResponse(
                    Inventory::with('product', 'warehouse', 'color', 'tone')
                        ->whereHas('product', fn($subQuery) => $subQuery->where('id', $request->input('product_id')))
                        ->whereHas('warehouse', fn($subQuery) => $subQuery->where('id', $request->input('warehouse_id')))
                        ->whereHas('color', fn($subQuery) => $subQuery->where('id', $request->input('color_id')))
                        ->whereHas('tone', fn($subQuery) => $subQuery->where('id', $request->input('tone_id')))
                        ->get(),
                    'Inventario encontrado con exito.',
                    200
                );
            }

            if($request->filled('product_id')) {
                return $this->successResponse(
                    Product::with('colors_tones.color', 'colors_tones.tone')->findOrFail($request->input('product_id')),
                    'Colores y tonos del producto encontrados con exito.',
                    200
                );
            }

            return $this->successResponse(
                Product::with('inventories', 'sizes')
                    ->whereHas('inventories', fn($subQuery) => $subQuery->where('warehouse_id', $request->input('warehouse_id')))
                    ->get(),
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

    public function store(TransferDetailStoreRequest $request)
    {
        try {
            $tranferDetail = new TransferDetail();
            $tranferDetail->transfer_id = $request->input('transfer_id');
            $tranferDetail->product_id = $request->input('product_id');
            $tranferDetail->size_id = $request->input('size_id');
            $tranferDetail->color_id = $request->input('color_id');
            $tranferDetail->tone_id = $request->input('tone_id');
            $tranferDetail->quantity = $request->input('quantity');
            $tranferDetail->status = 'Pendiente';
            $tranferDetail->save();

            return $this->successResponse(
                $tranferDetail,
                'El Detalle de la transferencia fue registrado exitosamente.',
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

    public function edit(TransferDetailEditRequest $request, $id)
    {
        try {
            if($request->filled('warehouse_id') && $request->filled('product_id') && $request->filled('color_id') && $request->filled('tone_id')) {
                return $this->successResponse(
                    Inventory::with('product', 'warehouse', 'color', 'tone')
                        ->whereHas('product', fn($subQuery) => $subQuery->where('id', $request->input('product_id')))
                        ->whereHas('warehouse', fn($subQuery) => $subQuery->where('id', $request->input('warehouse_id')))
                        ->whereHas('color', fn($subQuery) => $subQuery->where('id', $request->input('color_id')))
                        ->whereHas('tone', fn($subQuery) => $subQuery->where('id', $request->input('tone_id')))
                        ->get(),
                    'Inventario encontrado con exito.',
                    200
                );
            }

            return $this->successResponse(
                TransferDetail::with('product', 'size', 'color', 'tone')->findOrFail($id),
                'El Detalle de la transferencia fue encontrado exitosamente.',
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

    public function update(TransferDetailUpdateRequest $request, $id)
    {
        try {
            $tranferDetail = TransferDetail::findOrFail($id);
            $tranferDetail->quantity = $request->input('quantity');
            $tranferDetail->save();

            return $this->successResponse(
                $tranferDetail,
                'El Detalle de la transferencia fue actualizado exitosamente.',
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

    public function delete(TransferDetailDeleteRequest $request)
    {
        try {
            $tranferDetail = TransferDetail::findOrFail($request->input('id'));

            $inventory = Inventory::with('product', 'size', 'warehouse', 'color', 'tone')
                ->whereHas('product', fn($subQuery) => $subQuery->where('id', $tranferDetail->product_id))
                ->whereHas('size', fn($subQuery) => $subQuery->where('id', $tranferDetail->size_id))
                ->whereHas('warehouse', fn($subQuery) => $subQuery->where('id', $tranferDetail->from_warehouse_id))
                ->whereHas('color', fn($subQuery) => $subQuery->where('id', $tranferDetail->color_id))
                ->whereHas('tone', fn($subQuery) => $subQuery->where('id', $tranferDetail->tone_id))
                ->first();

            $inventory->quantity += $tranferDetail->quantity;
            $inventory->save();

            $tranferDetail->delete();
            return $this->successResponse(
                $tranferDetail,
                'El Detalle de la transferencia fue eliminado exitosamente.',
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

    public function pending(TransferDetailPendingRequest $request)
    {
        try {
            $tranferDetail = TransferDetail::findOrFail($request->input('id'));
            $tranferDetail->status = 'Pendiente';
            $tranferDetail->save();

            return $this->successResponse(
                $tranferDetail,
                'El Detalle de la transferencia fue aprobado exitosamente.',
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

    public function cancel(TransferDetailCancelRequest $request)
    {
        try {
            $tranferDetail = TransferDetail::findOrFail($request->input('id'));
            $tranferDetail->status = 'Cancelado';
            $tranferDetail->save();

            return $this->successResponse(
                $tranferDetail,
                'El Detalle de la transferencia fue cancelado exitosamente.',
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
