<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use App\Models\OrderDispatch;
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
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users WHERE users.id = orders.seller_user_id) AS order_seller_user"),
                'orders.seller_status AS order_seller_status', 'orders.seller_date AS order_seller_date', 'orders.seller_observation AS order_seller_observation',
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users WHERE users.id = orders.wallet_user_id) AS order_wallet_user"),
                'orders.wallet_status AS order_wallet_status', 'orders.wallet_date AS order_wallet_date', 'orders.wallet_observation AS order_wallet_observation',
                'orders.dispatched_status AS order_dispatched_status', 'orders.dispatched_date AS order_dispatched_date', 'correrias.name AS order_correria_name', 
                'correrias.code AS order_correria_code', 'order_details.id AS order_detail_id', 'products.code AS order_detail_product', 
                DB::raw("(SELECT concat(name, ' - ', code) FROM colors WHERE colors.id = order_details.color_id) AS order_detail_color"),
                DB::raw("(SELECT concat(name, ' - ', code) FROM tones WHERE tones.id = order_details.tone_id) AS order_detail_tone"),
                'order_details.price AS order_detail_price', 'order_details.seller_date AS order_detail_seller_date',
                'order_details.seller_observation AS order_detail_seller_observation',
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users WHERE users.id = order_details.wallet_user_id) AS order_detail_wallet_user"),
                'order_details.wallet_date AS order_detail_wallet_date',
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users WHERE users.id = order_details.dispatched_user_id) AS order_detail_dispatched_user"),
                'order_details.dispatched_date AS order_detail_dispatched_date', 'order_details.status AS order_detail_status'
            );

            foreach($sizes as $size) {
                $code = strtolower(str_replace(' ', '', $size->code));
                $sales->addSelect(
                    DB::raw("COALESCE((SELECT quantity FROM order_detail_quantities WHERE order_detail_quantities.order_detail_id = order_details.id AND order_detail_quantities.size_id = {$size->id}), 0) AS {$code}")
                );
            }

            $sales->leftJoin('clients', 'clients.id', '=', 'orders.client_id')
                ->leftJoin('person_types', 'person_types.id', '=', 'clients.person_type_id')
                ->leftJoin('client_types', 'client_types.id', '=', 'clients.client_type_id')
                ->leftJoin('document_types', 'document_types.id', '=', 'clients.document_type_id')
                ->leftJoin('client_branches', 'client_branches.id', '=', 'orders.client_branch_id')
                ->leftJoin('countries', 'countries.id', '=', 'client_branches.country_id')
                ->leftJoin('departaments', 'departaments.id', '=', 'client_branches.departament_id')
                ->leftJoin('cities', 'cities.id', '=', 'client_branches.city_id')
                ->leftJoin('provinces', 'provinces.id', '=', 'cities.province_id')
                ->leftJoin('transporters', 'transporters.id', '=', 'orders.transporter_id')
                ->leftJoin('sale_channels', 'sale_channels.id', '=', 'orders.sale_channel_id')
                ->leftJoin('correrias', 'correrias.id', '=', 'orders.correria_id')
                ->leftJoin('order_details', 'order_details.order_id', '=', 'orders.id')
                ->leftJoin('products', 'products.id', '=', 'order_details.product_id');

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

    public function indexDispatches(Request $request)
    {
        try {
            $sizes = Size::all();

            if($request->ajax()) {
            
                $columns = [
                    'order_id', 'order_client_name', 'order_person_type_name', 'order_client_type_name', 'order_document_type_code', 
                    'order_client_document_number', 'order_client_branch_name', 'order_client_branch_code', 'order_client_branch_country', 
                    'order_client_branch_departament', 'order_client_branch_province', 'order_client_branch_city', 'order_client_branch_address',  
                    'order_client_branch_neighborhood', 'order_transporter_name', 'order_sale_channel_name', 'order_dispatch', 
                    'order_dispatch_date', 'order_seller_user', 'order_seller_status', 'order_seller_date', 'order_seller_observation', 
                    'order_wallet_user', 'order_wallet_status', 'order_wallet_date', 'order_wallet_observation', 'order_dispatched_status', 
                    'order_dispatched_date', 'order_correria_name',  'order_correria_code', 'order_dispatch_id', 'order_dispatch_dispatch_user', 
                    'order_dispatch_dispatch_status', 'order_dispatch_dispatch_date', 'order_dispatch_consecutive', 'order_dispatch_payment_status',
                    'order_dispatch_invoice_user', 'order_dispatch_invoice_date', 'order_detail_id', 'order_dispatch_detail_id', 
                    'order_detail_product', 'order_detail_color', 'order_detail_tone', 'order_detail_price'
                ];
                foreach($sizes as $size) {
                    $code = strtolower(str_replace(' ', '', $size->code));
                    array_push($columns, $code);
                }
                $columns = array_merge($columns, ['order_detail_seller_date', 'order_detail_seller_observation', 'order_detail_wallet_user', 'order_detail_wallet_date', 'order_detail_dispatched_user', 'order_detail_dispatched_date', 'order_detail_status', 'order_dispatch_detail_status']);

                return $this->successResponse(
                    $columns,
                    $this->getMessage('Success'),
                    200
                );
            }

            return view('Dashboard.Reports.IndexDispatches', compact('sizes'));
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexDispatchesQuery()
    {
        try {
            $sizes = Size::all();

            $dispatches = Order::select(
                'orders.id AS order_id', 'clients.name AS order_client_name', 'person_types.name AS order_person_type_name', 'client_types.name AS order_client_type_name', 
                'document_types.code AS order_document_type_code', 'clients.document_number AS order_client_document_number', 'client_branches.name AS order_client_branch_name',
                'client_branches.code AS order_client_branch_code', 'countries.name AS order_client_branch_country', 'departaments.name AS order_client_branch_departament',
                'provinces.name AS order_client_branch_province', 'cities.name AS order_client_branch_city', 'client_branches.address AS order_client_branch_address', 
                'client_branches.neighborhood AS order_client_branch_neighborhood', 'transporters.name AS order_transporter_name', 'sale_channels.name AS order_sale_channel_name',
                'orders.dispatch AS order_dispatch', 'orders.dispatch_date AS order_dispatch_date', 
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users WHERE users.id = orders.seller_user_id) AS order_seller_user"),
                'orders.seller_status AS order_seller_status', 'orders.seller_date AS order_seller_date', 'orders.seller_observation AS order_seller_observation',
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users WHERE users.id = orders.wallet_user_id) AS order_wallet_user"),
                'orders.wallet_status AS order_wallet_status', 'orders.wallet_date AS order_wallet_date', 'orders.wallet_observation AS order_wallet_observation',
                'orders.dispatched_status AS order_dispatched_status', 'orders.dispatched_date AS order_dispatched_date', 'correrias.name AS order_correria_name', 
                'correrias.code AS order_correria_code', 'order_dispatches.id AS order_dispatch_id',
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users WHERE users.id = order_dispatches.dispatch_user_id) AS order_dispatch_dispatch_user"),
                'order_dispatches.dispatch_status AS order_dispatch_dispatch_status', 'order_dispatches.dispatch_date AS order_dispatch_dispatch_date',
                'order_dispatches.consecutive AS order_dispatch_consecutive', 'order_dispatches.payment_status AS order_dispatch_payment_status',
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users WHERE users.id = order_dispatches.invoice_user_id) AS order_dispatch_invoice_user"),
                'order_dispatches.invoice_date AS order_dispatch_invoice_date', 'order_details.id AS order_detail_id', 
                'order_dispatch_details.id AS order_dispatch_detail_id', 'products.code AS order_detail_product', 
                DB::raw("(SELECT concat(name, ' - ', code) FROM colors WHERE colors.id = order_details.color_id) AS order_detail_color"),
                DB::raw("(SELECT concat(name, ' - ', code) FROM tones WHERE tones.id = order_details.tone_id) AS order_detail_tone"),
                'order_details.price AS order_detail_price', 'order_details.seller_date AS order_detail_seller_date',
                'order_details.seller_observation AS order_detail_seller_observation',
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users WHERE users.id = order_details.wallet_user_id) AS order_detail_wallet_user"),
                'order_details.wallet_date AS order_detail_wallet_date',
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users WHERE users.id = order_details.dispatched_user_id) AS order_detail_dispatched_user"),
                'order_details.dispatched_date AS order_detail_dispatched_date', 'order_details.status AS order_detail_status', 'order_dispatch_details.status AS order_dispatch_detail_status'
            );

            foreach($sizes as $size) {
                $code = strtolower(str_replace(' ', '', $size->code));
                $dispatches->addSelect(DB::raw("COALESCE((SELECT order_dispatch_detail_quantities.quantity FROM order_dispatch_detail_quantities LEFT JOIN order_detail_quantities ON order_dispatch_detail_quantities.order_detail_quantity_id = order_detail_quantities.id WHERE order_detail_quantities.order_detail_id = order_details.id AND order_detail_quantities.size_id = {$size->id}), 0) AS {$code}"));
            }

            $dispatches->leftJoin('clients', 'clients.id', '=', 'orders.client_id')
                ->leftJoin('person_types', 'person_types.id', '=', 'clients.person_type_id')
                ->leftJoin('client_types', 'client_types.id', '=', 'clients.client_type_id')
                ->leftJoin('document_types', 'document_types.id', '=', 'clients.document_type_id')
                ->leftJoin('client_branches', 'client_branches.id', '=', 'orders.client_branch_id')
                ->leftJoin('countries', 'countries.id', '=', 'client_branches.country_id')
                ->leftJoin('departaments', 'departaments.id', '=', 'client_branches.departament_id')
                ->leftJoin('cities', 'cities.id', '=', 'client_branches.city_id')
                ->leftJoin('provinces', 'provinces.id', '=', 'cities.province_id')
                ->leftJoin('transporters', 'transporters.id', '=', 'orders.transporter_id')
                ->leftJoin('sale_channels', 'sale_channels.id', '=', 'orders.sale_channel_id')
                ->leftJoin('correrias', 'correrias.id', '=', 'orders.correria_id')
                ->join('order_dispatches', 'order_dispatches.order_id', '=', 'orders.id')
                ->leftJoin('order_details', 'order_details.order_id', '=', 'orders.id')
                ->join('order_dispatch_details', 'order_dispatch_details.order_detail_id', '=', 'order_details.id')
                ->leftJoin('products', 'products.id', '=', 'order_details.product_id');

            return datatables()->of($dispatches->get())->toJson();
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

    public function indexProductions(Request $request)
    {
        try {
            $sizes = Size::all();

            if($request->ajax()) {
            
                $columns = [
                    'order_id', 'order_client_name', 'order_person_type_name', 'order_client_type_name', 'order_document_type_code', 
                    'order_client_document_number', 'order_client_branch_name', 'order_client_branch_code', 'order_client_branch_country', 
                    'order_client_branch_departament', 'order_client_branch_province', 'order_client_branch_city', 'order_client_branch_address',  
                    'order_client_branch_neighborhood', 'order_transporter_name', 'order_sale_channel_name', 'order_dispatch', 
                    'order_dispatch_date', 'order_seller_user', 'order_seller_status', 'order_seller_date', 'order_seller_observation', 
                    'order_wallet_user', 'order_wallet_status', 'order_wallet_date', 'order_wallet_observation', 'order_dispatched_status', 
                    'order_dispatched_date', 'order_correria_name',  'order_correria_code', 'order_dispatch_id', 'order_dispatch_dispatch_user', 
                    'order_dispatch_dispatch_status', 'order_dispatch_dispatch_date', 'order_dispatch_consecutive', 'order_dispatch_payment_status',
                    'order_dispatch_invoice_user', 'order_dispatch_invoice_date', 'order_detail_id', 'order_dispatch_detail_id', 
                    'order_detail_product', 'order_detail_color', 'order_detail_tone', 'order_detail_price'
                ];
                foreach($sizes as $size) {
                    $code = strtolower(str_replace(' ', '', $size->code));
                    array_push($columns, $code);
                }
                $columns = array_merge($columns, ['order_detail_seller_date', 'order_detail_seller_observation', 'order_detail_wallet_user', 'order_detail_wallet_date', 'order_detail_dispatched_user', 'order_detail_dispatched_date', 'order_detail_status', 'order_dispatch_detail_status']);

                return $this->successResponse(
                    $columns,
                    $this->getMessage('Success'),
                    200
                );
            }

            return view('Dashboard.Reports.IndexProductions', compact('sizes'));
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexProductionsQuery()
    {
        try {
            $sizes = Size::all();

            $dispatches = Order::select(
                'orders.id AS order_id', 'clients.name AS order_client_name', 'person_types.name AS order_person_type_name', 'client_types.name AS order_client_type_name', 
                'document_types.code AS order_document_type_code', 'clients.document_number AS order_client_document_number', 'client_branches.name AS order_client_branch_name',
                'client_branches.code AS order_client_branch_code', 'countries.name AS order_client_branch_country', 'departaments.name AS order_client_branch_departament',
                'provinces.name AS order_client_branch_province', 'cities.name AS order_client_branch_city', 'client_branches.address AS order_client_branch_address', 
                'client_branches.neighborhood AS order_client_branch_neighborhood', 'transporters.name AS order_transporter_name', 'sale_channels.name AS order_sale_channel_name',
                'orders.dispatch AS order_dispatch', 'orders.dispatch_date AS order_dispatch_date', 
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users WHERE users.id = orders.seller_user_id) AS order_seller_user"),
                'orders.seller_status AS order_seller_status', 'orders.seller_date AS order_seller_date', 'orders.seller_observation AS order_seller_observation',
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users WHERE users.id = orders.wallet_user_id) AS order_wallet_user"),
                'orders.wallet_status AS order_wallet_status', 'orders.wallet_date AS order_wallet_date', 'orders.wallet_observation AS order_wallet_observation',
                'orders.dispatched_status AS order_dispatched_status', 'orders.dispatched_date AS order_dispatched_date', 'correrias.name AS order_correria_name', 
                'correrias.code AS order_correria_code', 'order_dispatches.id AS order_dispatch_id',
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users WHERE users.id = order_dispatches.dispatch_user_id) AS order_dispatch_dispatch_user"),
                'order_dispatches.dispatch_status AS order_dispatch_dispatch_status', 'order_dispatches.dispatch_date AS order_dispatch_dispatch_date',
                'order_dispatches.consecutive AS order_dispatch_consecutive', 'order_dispatches.payment_status AS order_dispatch_payment_status',
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users WHERE users.id = order_dispatches.invoice_user_id) AS order_dispatch_invoice_user"),
                'order_dispatches.invoice_date AS order_dispatch_invoice_date', 'order_details.id AS order_detail_id', 
                'order_dispatch_details.id AS order_dispatch_detail_id', 'products.code AS order_detail_product', 
                DB::raw("(SELECT concat(name, ' - ', code) FROM colors WHERE colors.id = order_details.color_id) AS order_detail_color"),
                DB::raw("(SELECT concat(name, ' - ', code) FROM tones WHERE tones.id = order_details.tone_id) AS order_detail_tone"),
                'order_details.price AS order_detail_price', 'order_details.seller_date AS order_detail_seller_date',
                'order_details.seller_observation AS order_detail_seller_observation',
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users WHERE users.id = order_details.wallet_user_id) AS order_detail_wallet_user"),
                'order_details.wallet_date AS order_detail_wallet_date',
                DB::raw("(SELECT concat(name, ' ', last_name) FROM users WHERE users.id = order_details.dispatched_user_id) AS order_detail_dispatched_user"),
                'order_details.dispatched_date AS order_detail_dispatched_date', 'order_details.status AS order_detail_status', 'order_dispatch_details.status AS order_dispatch_detail_status'
            );

            foreach($sizes as $size) {
                $code = strtolower(str_replace(' ', '', $size->code));
                $dispatches->addSelect(DB::raw("COALESCE((SELECT order_dispatch_detail_quantities.quantity FROM order_dispatch_detail_quantities LEFT JOIN order_detail_quantities ON order_dispatch_detail_quantities.order_detail_quantity_id = order_detail_quantities.id WHERE order_detail_quantities.order_detail_id = order_details.id AND order_detail_quantities.size_id = {$size->id}), (SELECT quantity FROM order_detail_quantities WHERE order_detail_quantities.order_detail_id = order_details.id AND order_detail_quantities.size_id = {$size->id}), 0) AS {$code}"));
            }

            $dispatches->leftJoin('clients', 'clients.id', '=', 'orders.client_id')
                ->leftJoin('person_types', 'person_types.id', '=', 'clients.person_type_id')
                ->leftJoin('client_types', 'client_types.id', '=', 'clients.client_type_id')
                ->leftJoin('document_types', 'document_types.id', '=', 'clients.document_type_id')
                ->leftJoin('client_branches', 'client_branches.id', '=', 'orders.client_branch_id')
                ->leftJoin('countries', 'countries.id', '=', 'client_branches.country_id')
                ->leftJoin('departaments', 'departaments.id', '=', 'client_branches.departament_id')
                ->leftJoin('cities', 'cities.id', '=', 'client_branches.city_id')
                ->leftJoin('provinces', 'provinces.id', '=', 'cities.province_id')
                ->leftJoin('transporters', 'transporters.id', '=', 'orders.transporter_id')
                ->leftJoin('sale_channels', 'sale_channels.id', '=', 'orders.sale_channel_id')
                ->leftJoin('correrias', 'correrias.id', '=', 'orders.correria_id')
                ->leftJoin('order_dispatches', 'order_dispatches.order_id', '=', 'orders.id')
                ->leftJoin('order_details', 'order_details.order_id', '=', 'orders.id')
                ->leftJoin('order_dispatch_details', 'order_dispatch_details.order_detail_id', '=', 'order_details.id')
                ->leftJoin('products', 'products.id', '=', 'order_details.product_id');

            return datatables()->of($dispatches->get())->toJson();
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

    public function indexWallets(Request $request)
    {
        try {
            if($request->ajax()) {

                $columns = [
                    'client_name', 'client_person_type', 'client_client_type', 'client_document_type', 'client_document_number', 
                    'client_country', 'client_departament', 'client_province', 'client_city', 'client_address', 'client_neighborhood', 
                    'client_email', 'client_telephone_number_first', 'client_telephone_number_second', 'client_quota', 'client_debt', 
                    'client_balance', 'client_branch_name', 'client_branch_code', 'client_branch_country', 'client_branch_departament',
                    'client_branch_province', 'client_branch_city', 'client_branch_address', 'client_branch_neighborhood', 
                    'client_branch_email', 'client_branch_telephone_number_first', 'client_branch_telephone_number_second',
                    'client_branch_invoice', 'client_branch_payment', 'client_branch_balance'
                ];

                return $this->successResponse(
                    $columns,
                    $this->getMessage('Success'),
                    200
                );
            }

            return view('Dashboard.Reports.IndexWallets');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexWalletsQuery()
    {
        try {
            $wallets = Client::select(
                'clients.name AS client_name', 'person_types.name AS client_person_type', 'client_types.name AS client_client_type',
                'document_types.code AS client_document_type', 'clients.document_number AS client_document_number', 
                DB::raw("(SELECT countries.name FROM countries WHERE countries.id = clients.country_id) AS client_country"),
                DB::raw("(SELECT departaments.name FROM departaments WHERE departaments.id = clients.departament_id) AS client_departament"),
                DB::raw("(SELECT provinces.name FROM provinces JOIN cities ON cities.province_id = provinces.id WHERE cities.id = clients.city_id) AS client_province"),
                DB::raw("(SELECT cities.name FROM cities WHERE cities.id = clients.city_id) AS client_city"),
                'clients.address AS client_address', 'clients.neighborhood AS client_neighborhood', 'clients.email AS client_email', 
                'clients.telephone_number_first AS client_telephone_number_first', 'clients.telephone_number_second AS client_telephone_number_second', 
                'clients.quota AS client_quota', 'clients.debt AS client_debt', DB::raw('(clients.quota - clients.debt) AS client_balance'),
                'client_branches.name AS client_branch_name', 'client_branches.code AS client_branch_code', 
                DB::raw("(SELECT countries.name FROM countries WHERE countries.id = client_branches.country_id) AS client_branch_country"),
                DB::raw("(SELECT departaments.name FROM departaments WHERE departaments.id = client_branches.departament_id) AS client_branch_departament"),
                DB::raw("(SELECT provinces.name FROM provinces JOIN cities ON cities.province_id = provinces.id WHERE cities.id = client_branches.city_id) AS client_branch_province"),
                DB::raw("(SELECT cities.name FROM cities WHERE cities.id = client_branches.city_id) AS client_branch_city"),
                'client_branches.address AS client_branch_address', 'client_branches.neighborhood AS client_branch_neighborhood', 'client_branches.email AS client_branch_email', 
                'client_branches.telephone_number_first AS client_branch_telephone_number_first', 'client_branches.telephone_number_second AS client_branch_telephone_number_second',
                DB::raw('(SELECT SUM(value) FROM invoices JOIN order_dispatches ON order_dispatches.id = invoices.model_id JOIN orders ON orders.id = order_dispatches.order_id WHERE invoices.model_type = "App\Models\OrderDispatch" AND orders.client_branch_id = client_branches.id) AS client_branch_invoice'),
                DB::raw('(SELECT SUM(value) FROM payments JOIN order_dispatches ON order_dispatches.id = payments.model_id JOIN orders ON orders.id = order_dispatches.order_id WHERE payments.model_type = "App\Models\OrderDispatch" AND orders.client_branch_id = client_branches.id) AS client_branch_payment'),
                DB::raw('(SELECT COALESCE(((SELECT SUM(value) FROM invoices JOIN order_dispatches ON order_dispatches.id = invoices.model_id JOIN orders ON orders.id = order_dispatches.order_id WHERE invoices.model_type = "App\Models\OrderDispatch" AND orders.client_branch_id = client_branches.id)-(SELECT SUM(value) FROM payments JOIN order_dispatches ON order_dispatches.id = payments.model_id JOIN orders ON orders.id = order_dispatches.order_id WHERE payments.model_type = "App\Models\OrderDispatch" AND orders.client_branch_id = client_branches.id)), 0)) AS client_branch_balance')
            )
            ->leftJoin('person_types', 'person_types.id', '=', 'clients.person_type_id')
            ->leftJoin('client_types', 'client_types.id', '=', 'clients.client_type_id')
            ->leftJoin('document_types', 'document_types.id', '=', 'clients.document_type_id')
            ->leftJoin('client_branches', 'client_branches.client_id', '=', 'clients.id')
            ->where('client_types.require_quota', true)
            ->get();

            return datatables()->of($wallets)->toJson();
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
