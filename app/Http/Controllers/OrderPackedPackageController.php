<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderPackedPackage\OrderPackedPackageCloseRequest;
use App\Http\Requests\OrderPackedPackage\OrderPackedPackageDeleteRequest;
use App\Http\Requests\OrderPackedPackage\OrderPackedPackageDetailRequest;
use App\Http\Requests\OrderPackedPackage\OrderPackedPackageIndexQueryRequest;
use App\Http\Requests\OrderPackedPackage\OrderPackedPackageOpenRequest;
use App\Http\Requests\OrderPackedPackage\OrderPackedPackageShowQueryRequest;
use App\Http\Requests\OrderPackedPackage\OrderPackedPackageStoreRequest;
use App\Models\OrderPackage;
use App\Models\OrderPackageDetail;
use App\Models\OrderPackageDetailQuantity;
use App\Models\OrderPacking;
use App\Models\PackageType;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class OrderPackedPackageController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index($id)
    {
        try {
            $orderPackage = OrderPackage::where('package_status', 'Abierto')->where('order_packing_id', $id)->first();
            if($orderPackage) {
                return Redirect::route('Dashboard.Orders.Packed.Package.Show', ['id' => $orderPackage->id]);
            }
            $orderPacked = OrderPacking::with('order_dispatch')->findOrFail($id);
            $packageTypes = PackageType::all();
            return view('Dashboard.OrderPackedPackages.Index', compact('orderPacked', 'packageTypes'));
        } catch (ModelNotFoundException $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la orden de alistamiento y empacado ' . $this->getMessage('ModelNotFoundException'));
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(OrderPackedPackageIndexQueryRequest $request)
    {
        try {
            $orderPackages = OrderPackage::with([
                    'order_packing.order_dispatch', 'package_type',
                    'order_package_details.order_dispatch_detail.order_detail.product', 
                    'order_package_details.order_dispatch_detail.order_detail.color', 
                    'order_package_details.order_dispatch_detail.order_detail.tone', 
                    'order_package_details.order_package_detail_quantities.order_dispatch_detail_quantity.order_detail_quantity.size'
                ])
                ->where('order_packing_id', $request->input('order_packing_id'))
                ->get();

            return $this->successResponse(
                $orderPackages,
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

    public function store(OrderPackedPackageStoreRequest $request) 
    {
        try {
            $orderPackage = new OrderPackage();
            $orderPackage->order_packing_id = $request->input('order_packing_id');
            $orderPackage->package_type_id = $request->input('package_type_id');
            $orderPackage->package_date = Carbon::now()->format('Y-m-d H:i:s');
            $orderPackage->save();

            return $this->successResponse(
                [
                    'url' => URL::route('Dashboard.Orders.Packed.Package.Show', ['id' => $orderPackage->id]),
                    'orderPackage' => $orderPackage
                ],
                'El empaque fue creado exitosamente.',
                201
            );
        }  catch (QueryException $e) {
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

    public function show($id)
    {
        try {
            $orderPackage = OrderPackage::with('package_type', 'order_packing.order_dispatch', 'order_package_details.order_package_detail_quantities.order_dispatch_detail_quantity.order_detail_quantity')->findOrFail($id);
            return view('Dashboard.OrderPackedPackages.Show', compact('orderPackage'));
        } catch (ModelNotFoundException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }  catch (QueryException $e) {
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

    public function showQuery(OrderPackedPackageShowQueryRequest $request)
    {
        try {
            $orderPackage = OrderPackage::with([
                'order_packing.order_dispatch.order_dispatch_details' => fn($query) => $query->whereIn('status', ['Aprobado']),
                'order_packing.order_dispatch.order_dispatch_details.order_detail.product', 
                'order_packing.order_dispatch.order_dispatch_details.order_detail.color', 
                'order_packing.order_dispatch.order_dispatch_details.order_detail.tone', 
                'order_packing.order_dispatch.order_dispatch_details.order_packages_details.order_package_detail_quantities.order_dispatch_detail_quantity.order_detail_quantity.size', 
                'order_packing.order_dispatch.order_dispatch_details.order_dispatch_detail_quantities.order_detail_quantity.size', 
                'order_packing.order_dispatch.order_dispatch_details.order_dispatch_detail_quantities.order_packages_details_quantities', 
                'order_package_details.order_dispatch_detail.order_detail.product', 
                'order_package_details.order_dispatch_detail.order_detail.color', 
                'order_package_details.order_dispatch_detail.order_detail.tone', 
                'order_package_details.order_package_detail_quantities.order_dispatch_detail_quantity.order_detail_quantity.size',
            ])->findOrFail($request->input('order_package_id'));

            return $this->successResponse(
                [
                    'url' => $orderPackage->package_status == 'Cerrado' ? URL::route('Dashboard.Orders.Packed.Package.Index', ['id' => $orderPackage->order_packing_id]) : null,
                    'orderPackage' => $orderPackage
                ],
                'Empaque cargado exitosamente.',
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
        }  catch (QueryException $e) {
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

    public function detail(OrderPackedPackageDetailRequest $request) 
    {
        try {
            $orderPackageDetail = OrderPackageDetail::with('order_dispatch_detail.order_dispatch_detail_quantities.order_detail_quantity', 'order_dispatch_detail.order_detail')
                ->whereHas('order_dispatch_detail.order_detail',
                    function ($subQuery) use ($request) {
                        $subQuery->where('product_id', $request->input('product_id'))
                            ->where('color_id', $request->input('color_id'))
                            ->where('tone_id', $request->input('tone_id'));
                    }
                )
                ->where('order_package_id', $request->input('order_package_id'))
                ->where('order_dispatch_detail_id', $request->input('order_dispatch_detail_id'))
                ->first();
                
            if(!$orderPackageDetail) {
                $orderPackageDetail = new OrderPackageDetail();
                $orderPackageDetail->order_package_id = $request->input('order_package_id');
                $orderPackageDetail->order_dispatch_detail_id = $request->input('order_dispatch_detail_id');
                $orderPackageDetail->save();
            }

            $orderPackageDetail->load('order_package_detail_quantities.order_dispatch_detail_quantity.order_detail_quantity');

            $orderPackageDetailQuantity = $orderPackageDetail->order_package_detail_quantities()
                ->whereHas('order_dispatch_detail_quantity.order_detail_quantity',
                    function ($subQuery) use ($request) {
                        $subQuery->where('size_id', $request->input('size_id'));
                    }
                )
                ->first();

            if(!$orderPackageDetailQuantity) {
                $orderDispatchDetailQuantity = $orderPackageDetail->order_dispatch_detail->order_dispatch_detail_quantities()
                    ->whereHas('order_detail_quantity',
                        function ($subQuery) use ($request) {
                            $subQuery->where('size_id', $request->input('size_id'));
                        }
                    )
                    ->firstOrFail();

                $orderPackageDetailQuantity = new OrderPackageDetailQuantity();
                $orderPackageDetailQuantity->order_package_detail_id = $orderPackageDetail->id;
                $orderPackageDetailQuantity->order_dispatch_detail_quantity_id = $orderDispatchDetailQuantity->id;
                $orderPackageDetailQuantity->quantity = 0;
                $orderPackageDetailQuantity->save();
            }
            
            $orderPackageDetailQuantity->quantity = $request->filled('quantity') ? $request->input('quantity') : $orderPackageDetailQuantity->quantity + 1;
            $orderPackageDetailQuantity->save();

            return $this->successResponse(
                '',
                'Unidades incorporadas al empaque exitosamente.',
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
        }  catch (QueryException $e) {
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

    public function open(OrderPackedPackageOpenRequest $request) 
    {
        try {
            $orderPackage = OrderPackage::findOrFail($request->input('id'));
            $orderPackage->weight = null;
            $orderPackage->package_status = 'Abierto';
            $orderPackage->save();

            return $this->successResponse(
                [
                    'url' => URL::route('Dashboard.Orders.Packed.Package.Show', ['id' => $orderPackage->id]),
                    'orderPackage' => $orderPackage
                ],
                'El empaque fue abierto exitosamente.',
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
        }  catch (QueryException $e) {
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

    public function close(OrderPackedPackageCloseRequest $request) 
    {
        try {
            $orderPackage = OrderPackage::findOrFail($request->input('id'));
            $orderPackage->weight = $request->input('weight');
            $orderPackage->package_status = 'Cerrado';
            $orderPackage->save();

            return $this->successResponse(
                [
                    'url' => URL::route('Dashboard.Orders.Packed.Package.Index', ['id' => $orderPackage->order_packing_id]),
                    'orderPackage' => $orderPackage
                ],
                'El empaque fue abierto exitosamente.',
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
        }  catch (QueryException $e) {
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

    public function delete(OrderPackedPackageDeleteRequest $request)
    {
        try {
            $orderPackage = OrderPackage::findOrFail($request->input('id'))->delete();

            return $this->successResponse(
                [
                    'url' => URL::route('Dashboard.Orders.Packed.Package.Index', ['id' => $request->input('order_packing_id')]),
                    'orderPackage' => $orderPackage
                ],
                'El empaque fue eliminado exitosamente.',
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
