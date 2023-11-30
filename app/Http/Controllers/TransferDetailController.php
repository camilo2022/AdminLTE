<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferDetail\TransferDetailCancelRequest;
use App\Http\Requests\TransferDetail\TransferDetailCreateRequest;
use App\Http\Requests\TransferDetail\TransferDetailDeleteRequest;
use App\Http\Requests\TransferDetail\TransferDetailEditRequest;
use App\Http\Requests\TransferDetail\TransferDetailPendingRequest;
use App\Http\Requests\TransferDetail\TransferDetailStoreRequest;
use App\Http\Requests\TransferDetail\TransferDetailUpdateRequest;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\TransferDetail;
use App\Models\User;
use App\Models\Warehouse;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class TransferDetailController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function create(TransferDetailCreateRequest $request)
    {
        try {
            if($request->filled('warehouse_id') && $request->filled('product_id') && $request->filled('color_id')) {
                return $this->successResponse(
                    Inventory::with('product', 'warehouse', 'color')
                    ->whereHas('product', fn($subQuery) => $subQuery->where('id', $request->input('product_id')))
                    ->whereHas('warehouse', fn($subQuery) => $subQuery->where('id', $request->input('warehouse_id')))
                    ->whereHas('color', fn($subQuery) => $subQuery->where('id', $request->input('color_id')))
                    ->get(),
                    'Subcategorias encontradas con exito.',
                    200
                );
            }

            if($request->filled('warehouse_id')) {
                return $this->successResponse(
                    [
                        'products' => Product::with('inventories')
                        ->whereHas('inventories', fn($subQuery) => $subQuery->where('warehouse_id', $request->input('warehouse_id')))
                        ->get(),
                        'warehouses' => Warehouse::all()
                    ],
                    'Subcategorias encontradas con exito.',
                    200
                );
            }

            if($request->filled('product_id')) {
                return $this->successResponse(
                    Product::with('colors')->findOrFail($request->input('product_id'))->colors,
                    'Subcategorias encontradas con exito.',
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

    public function store(TransferDetailStoreRequest $request)
    {
        try {
            $tranferDetail = new TransferDetail();
            $tranferDetail->transfer_id = $request->input('transfer_id');
            $tranferDetail->product_id = $request->input('product_id');
            $tranferDetail->size_id = $request->input('size_id');
            $tranferDetail->color_id = $request->input('color_id');
            $tranferDetail->quantity = $request->input('quantity');
            $tranferDetail->from_warehouse_id = $request->input('from_warehouse_id');
            $tranferDetail->to_warehouse_id = $request->input('to_warehouse_id');
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
            if($request->filled('warehouse_id') && $request->filled('product_id') && $request->filled('color_id')) {
                return $this->successResponse(
                    Inventory::with('product', 'warehouse', 'color')
                    ->whereHas('product', fn($subQuery) => $subQuery->where('id', $request->input('product_id')))
                    ->whereHas('warehouse', fn($subQuery) => $subQuery->where('id', $request->input('warehouse_id')))
                    ->whereHas('color', fn($subQuery) => $subQuery->where('id', $request->input('color_id')))
                    ->get(),
                    'Subcategorias encontradas con exito.',
                    200
                );
            }

            if($request->filled('warehouse_id')) {
                return $this->successResponse(
                    [
                        'products' => Product::with('inventories')
                        ->whereHas('inventories', fn($subQuery) => $subQuery->where('warehouse_id', $request->input('warehouse_id')))
                        ->get(),
                        'warehouses' => Warehouse::all()
                    ],
                    'Subcategorias encontradas con exito.',
                    200
                );
            }

            if($request->filled('product_id')) {
                return $this->successResponse(
                    [
                        Product::with('colors')->findOrFail($request->input('product_id'))->colors,
                        User::with('warehouses')->findOrFail(Auth::user()->id)->warehouses
                    ],
                    'Subcategorias encontradas con exito.',
                    200
                );
            }

            return $this->successResponse(
                TransferDetail::findOrFail($id),
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
            $tranferDetail->product_id = $request->input('product_id');
            $tranferDetail->size_id = $request->input('size_id');
            $tranferDetail->color_id = $request->input('color_id');
            $tranferDetail->quantity = $request->input('quantity');
            $tranferDetail->from_warehouse_id = $request->input('from_warehouse_id');
            $tranferDetail->to_warehouse_id = $request->input('to_warehouse_id');
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

            $inventory = Inventory::with('product', 'size', 'warehouse', 'color')
            ->whereHas('product', fn($subQuery) => $subQuery->where('id', $tranferDetail->product_id))
            ->whereHas('size', fn($subQuery) => $subQuery->where('id', $tranferDetail->size_id))
            ->whereHas('warehouse', fn($subQuery) => $subQuery->where('id', $tranferDetail->from_warehouse_id))
            ->whereHas('color', fn($subQuery) => $subQuery->where('id', $tranferDetail->color_id))
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
