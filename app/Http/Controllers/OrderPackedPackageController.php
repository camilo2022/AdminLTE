<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderPackedPackage\OrderPackedPackageIndexQueryRequest;
use App\Models\OrderPackage;
use App\Models\OrderPacking;
use App\Models\PackageType;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Redirect;

class OrderPackedPackageController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index($id)
    {
        try {
            $orderPackage= OrderPackage::where('package_status', 'Abierto')->where('order_packing_id', $id)->first();
            if($orderPackage) {
                return Redirect::route('Dashboard.Orders.Packed.Package.Show', ['id' => $orderPackage->id]);
            }
            $orderPacked = OrderPacking::with('order_dispatch')->findOrFail($id);
            return view('Dashboard.OrderPackedPackages.Index', compact('orderPacked'));
        } catch (ModelNotFoundException $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la orden de alistamiento y empacado ' . $this->getMessage('ModelNotFoundException'));
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(OrderPackedPackageIndexQueryRequest $request)
    {
        try {
            $orderPackage = OrderPackage::with([
                    'package_type', 'order_package_details.order_dispatch_detail.order_detail',
                    'order_package_details.order_package_detail_quantities.order_dispatch_detail_quantity.order_detail_quantity',
                ])
                ->where('order_packing_id', $request->input('order_packing_id'))
                ->get();

            $packageTypes = PackageType::all();

            return $this->successResponse(
                [
                    'orderPackage' => $orderPackage,
                    'packageTypes' => $packageTypes,
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
}
