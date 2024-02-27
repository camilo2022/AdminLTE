<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleChannel\SaleChannelAssignReturnTypeRequest;
use App\Http\Requests\SaleChannel\SaleChannelDeleteRequest;
use App\Http\Requests\SaleChannel\SaleChannelIndexQueryRequest;
use App\Http\Requests\SaleChannel\SaleChannelRemoveReturnTypeRequest;
use App\Http\Requests\SaleChannel\SaleChannelRestoreRequest;
use App\Http\Requests\SaleChannel\SaleChannelStoreRequest;
use App\Http\Requests\SaleChannel\SaleChannelUpdateRequest;
use App\Http\Resources\SaleChannel\SaleChannelIndexQueryCollection;
use App\Models\ReturnType;
use App\Models\SaleChannel;
use App\Models\SaleChannelReturnType;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class SaleChannelController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.SaleChannels.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(SaleChannelIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $saleChannels = SaleChannel::when($request->filled('search'),
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
                new SaleChannelIndexQueryCollection($saleChannels),
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

    public function store(SaleChannelStoreRequest $request)
    {
        try {
            $saleChannel = new SaleChannel();
            $saleChannel->name = $request->input('name');
            $saleChannel->require_verify_wallet = $request->input('require_verify_wallet');
            $saleChannel->save();

            return $this->successResponse(
                $saleChannel,
                'El canal de venta fue registrado exitosamente.',
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
                SaleChannel::withTrashed()->findOrFail($id),
                'El canal de venta fue encontrado exitosamente.',
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

    public function update(SaleChannelUpdateRequest $request, $id)
    {
        try {
            $saleChannel = SaleChannel::withTrashed()->findOrFail($id);
            $saleChannel->name = $request->input('name');
            $saleChannel->require_verify_wallet = $request->input('require_verify_wallet');
            $saleChannel->save();

            return $this->successResponse(
                $saleChannel,
                'El canal de venta fue actualizada exitosamente.',
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
            $returnTypes = ReturnType::with('sale_channels')->get();
            $saleChannel = SaleChannel::findOrFail($id);

            foreach ($returnTypes as $returnType) {
                $saleChannelsId = $returnType->sale_channels->pluck('id')->all();
                $returnType->exists = in_array($id, $saleChannelsId);
            }

            return $this->successResponse(
                [
                    'saleChannel' => $saleChannel,
                    'returnTypes' => $returnTypes
                ],
                'El canal de venta fue encontrado exitosamente.',
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

    public function assignReturnType(SaleChannelAssignReturnTypeRequest $request)
    {
        try {
            $sale_channel_return_types = new SaleChannelReturnType();
            $sale_channel_return_types->sale_channel_id = $request->input('sale_channel_id');
            $sale_channel_return_types->return_type_id = $request->input('return_type_id');
            $sale_channel_return_types->save();

            return $this->successResponse(
                $sale_channel_return_types,
                'Tipo de devolucion asignado exitosamente.',
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

    public function removeReturnType(SaleChannelRemoveReturnTypeRequest $request)
    {
        try {
            $warehouse_users = SaleChannelReturnType::where('sale_channel_id', $request->input('sale_channel_id'))
            ->where('return_type_id', $request->input('return_type_id'))->delete();

            return $this->successResponse(
                $warehouse_users,
                'Tipo de devolucion removido exitosamente.',
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

    public function delete(SaleChannelDeleteRequest $request)
    {
        try {
            $saleChannel = SaleChannel::withTrashed()->findOrFail($request->input('id'))->delete();
            return $this->successResponse(
                $saleChannel,
                'El canal de venta fue eliminada exitosamente.',
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

    public function restore(SaleChannelRestoreRequest $request)
    {
        try {
            $saleChannel = SaleChannel::withTrashed()->findOrFail($request->input('id'))->restore();
            return $this->successResponse(
                $saleChannel,
                'El canal de venta fue restaurado exitosamente.',
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
