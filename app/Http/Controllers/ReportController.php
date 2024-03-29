<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Size;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function indexSales(Request $request)
    {
        try {
            if($request->ajax()) {
                $sizes = Size::all();
            
                $columns = [
                    'order_id', 'order_client_name', 'order_person_type_name', 'order_client_type_name', 'order_document_type_code', 
                    'order_client_document_number', 'order_client_branch_name', 'order_client_branch_code', 'order_client_branch_country', 
                    'order_client_branch_departament', 'order_client_branch_province', 'order_client_branch_city', 'order_client_branch_address',  
                    'order_client_branch_neighborhood', 'order_transporter_name', 'order_sale_channel_name', 'order_dispatch', 
                    'order_dispatch_date', 'order_seller_user', 'order_seller_status', 'order_seller_date', 'order_seller_observation', 
                    'order_wallet_user', 'order_wallet_status', 'order_wallet_date', 'order_wallet_observation', 'order_dispatched_status', 
                    'order_dispatched_date', 'order_correria_name',  'order_correria_code', 'order_detail_id', 'order_detail_product',  
                    'order_detail_color', 'order_detail_tone', 'order_detail_price'
                ];
                foreach($sizes as $size) {
                    $code = strtolower(str_replace(' ', '', $size->code));
                    array_push($columns, $code);
                }
                $columns = array_merge($columns, ['order_detail_seller_date', 'order_detail_seller_observation', 'order_detail_wallet_user', 'order_detail_wallet_date', 'order_detail_dispatched_user', 'order_detail_dispatched_date', 'order_detail_status']);

                return $this->successResponse(
                    $columns,
                    $this->getMessage('Success'),
                    200
                );
            }
            $sizes = Size::all();
            return view('Dashboard.Reports.IndexSales', compact('sizes'));
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexSalesQuery()
    {
        try {
            $sizes = Size::all();

            $sales = Order::select(
                'orders.id AS order_id', 'clients.name AS order_client_name', 'person_types.name AS order_person_type_name', 'client_types.name AS order_client_type_name', 
                'document_types.code AS order_document_type_code', 'clients.document_number AS order_client_document_number', 'client_branches.name AS order_client_branch_name',
                'client_branches.code AS order_client_branch_code', 'countries.name AS order_client_branch_country', 'departaments.name AS order_client_branch_departament',
                'provinces.name AS order_client_branch_province', 'cities.name AS order_client_branch_city', 'client_branches.address AS order_client_branch_address', 
                'client_branches.neighborhood AS order_client_branch_neighborhood', 'transporters.name AS order_transporter_name', 'sale_channels.name AS order_sale_channel_name',
                'orders.dispatch AS order_dispatch', 'orders.dispatch_date AS order_dispatch_date', 
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users where users.id = orders.seller_user_id) AS order_seller_user"),
                'orders.seller_status AS order_seller_status', 'orders.seller_date AS order_seller_date', 'orders.seller_observation AS order_seller_observation',
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users where users.id = orders.wallet_user_id) AS order_wallet_user"),
                'orders.wallet_status AS order_wallet_status', 'orders.wallet_date AS order_wallet_date', 'orders.wallet_observation AS order_wallet_observation',
                'orders.dispatched_status AS order_dispatched_status', 'orders.dispatched_date AS order_dispatched_date', 'correrias.name AS order_correria_name', 
                'correrias.code AS order_correria_code', 'order_details.id AS order_detail_id', 'products.code AS order_detail_product', 
                DB::raw("(SELECT concat(name, ' - ', code) FROM colors where colors.id = order_details.color_id) AS order_detail_color"),
                DB::raw("(SELECT concat(name, ' - ', code) FROM tones where tones.id = order_details.tone_id) AS order_detail_tone"),
                'order_details.price AS order_detail_price', 'order_details.seller_date AS order_detail_seller_date',
                'order_details.seller_observation AS order_detail_seller_observation',
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users where users.id = order_details.wallet_user_id) AS order_detail_wallet_user"),
                'order_details.wallet_date AS order_detail_wallet_date',
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users where users.id = order_details.dispatched_user_id) AS order_detail_dispatched_user"),
                'order_details.dispatched_date AS order_detail_dispatched_date', 'order_details.status AS order_detail_status'
            );

            foreach($sizes as $size) {
                $code = strtolower(str_replace(' ', '', $size->code));
                $sales->addSelect(
                    DB::raw("COALESCE((SELECT quantity FROM order_detail_quantities WHERE order_detail_quantities.order_detail_id = order_details.id AND order_detail_quantities.size_id = {$size->id}), 0) AS {$code}")
                );
            }

            $sales->join('clients', 'clients.id', '=', 'orders.client_id')
                ->join('person_types', 'person_types.id', '=', 'clients.person_type_id')
                ->join('client_types', 'client_types.id', '=', 'clients.client_type_id')
                ->join('document_types', 'document_types.id', '=', 'clients.document_type_id')
                ->join('client_branches', 'client_branches.id', '=', 'orders.client_branch_id')
                ->join('countries', 'countries.id', '=', 'client_branches.country_id')
                ->join('departaments', 'departaments.id', '=', 'client_branches.departament_id')
                ->join('cities', 'cities.id', '=', 'client_branches.city_id')
                ->join('provinces', 'provinces.id', '=', 'cities.province_id')
                ->join('transporters', 'transporters.id', '=', 'orders.transporter_id')
                ->join('sale_channels', 'sale_channels.id', '=', 'orders.sale_channel_id')
                ->join('correrias', 'correrias.id', '=', 'orders.correria_id')
                ->join('order_details', 'order_details.order_id', '=', 'orders.id')
                ->join('products', 'products.id', '=', 'order_details.product_id');

            return datatables()->of($sales->get())->toJson();
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
