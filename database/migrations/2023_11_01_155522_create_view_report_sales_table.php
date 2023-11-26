<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* $query = DB::table('orders AS ord')
        ->select('ord.id AS order_id', 'ord.client_id AS order_client_id', 'cli.name AS client_name',
        'cli.document_number AS client_document_number', 'cli.telephone_number AS client_telephone_number',
        'cli.email AS client_email', 'cli_bra.name AS client_branch_name',
        'cli_bra.telephone_number AS client_branch_telephone_number', 'cli_bra.email AS client_branch_email',
        'cli_bra.country_id AS client_branch_country_id', 'cou.name AS country_name',
        'cli_bra.departament_id AS client_branch_departament_id', 'dep.name AS departament_name',
        'cli_bra.city_id AS client_branch_city_id', 'cit.name AS city_name',
        'cli_bra.address AS client_branch_address', 'cli_bra.neighborhood AS client_branch_neighborhood',
        'cli_bra.description AS client_branch_description', 'ord_det.id AS detail_id',
        'ord_det.product_id AS detail_product_id', 'pro.code AS product_code',
        'ord_det.color_id AS detail_color_id', 'col.name AS color_name',
        'ord_det.price AS detail_price',
        'ord_det.02 AS detail_size_02', 'ord_det.04 AS detail_size_04', 'ord_det.06 AS detail_size_06',
        'ord_det.08 AS detail_size_08', 'ord_det.10 AS detail_size_10', 'ord_det.12 AS detail_size_12',
        'ord_det.14 AS detail_size_14', 'ord_det.16 AS detail_size_16', 'ord_det.18 AS detail_size_18',
        'ord_det.20 AS detail_size_20', 'ord_det.22 AS detail_size_22', 'ord_det.24 AS detail_size_24',
        'ord_det.26 AS detail_size_26', 'ord_det.28 AS detail_size_28', 'ord_det.30 AS detail_size_30',
        'ord_det.32 AS detail_size_32', 'ord_det.34 AS detail_size_34', 'ord_det.36 AS detail_size_36',
        'ord_det.38 AS detail_size_38', 'ord_det.XXXS AS detail_size_XXXS', 'ord_det.XXS AS detail_size_XXS',
        'ord_det.XS AS detail_size_XS', 'ord_det.S AS detail_size_S', 'ord_det.M AS detail_size_M',
        'ord_det.L AS detail_size_L', 'ord_det.XL AS detail_size_XL', 'ord_det.XXL AS detail_size_XXL',
        'ord_det.XXXL AS detail_size_XXXL',
        'ord_det.wallet_user_id AS detail_wallet_user_id',
        DB::raw('concat(det_user_wallet.name," ",det_user_wallet.last_name) AS detail_wallet_user'),
        'ord_det.wallet_date AS detail_wallet_date', 'ord_det.dispatched_user_id AS detail_dispatched_user_id',
        DB::raw('concat(det_user_dispatched.name," ",det_user_dispatched.last_name) AS detail_dispatched_user'),
        'ord_det.dispatched_date AS detail_dispatched_date', 'ord_det.order_detail_status AS detail_status',
        'ord.dispatch AS order_dispatch', 'ord.dispatch_date AS order_dispatch_date',
        'ord.seller_user_id AS order_seller_user_id',
        DB::raw('concat(ord_user_seller.name," ",ord_user_seller.last_name) AS order_seller_user'),
        'ord.seller_status AS order_seller_status', 'ord.seller_date AS order_seller_date',
        'ord.seller_date AS order_seller_observation',
        'ord.wallet_user_id AS order_wallet_user_id',
        DB::raw('concat(ord_user_wallet.name," ",ord_user_wallet.last_name) AS order_wallet_user'),
        'ord.wallet_status AS order_wallet_status', 'ord.wallet_date AS order_wallet_date',
        'ord.wallet_date AS order_wallet_observation',
        'ord.dispatched_status AS order_dispatched_status', 'ord.dispatched_date AS order_dispatched_date',
        'ord.payment_status AS order_payment_status', 'ord.collection_id AS order_collection_id',
        'coll.name AS collection_name', 'coll.code AS collection_code',
        'ord_det.created_at AS created_at', 'ord_det.updated_at AS updated_at')
        ->join('clients AS cli', 'cli.id', '=', 'ord.client_id')
        ->join('client_branches AS cli_bra', 'cli_bra.client_id', '=', 'cli.id')
        ->join('countries AS cou', 'cou.id', '=', 'cli_bra.country_id')
        ->join('departaments AS dep', 'dep.id', '=', 'cli_bra.departament_id')
        ->join('cities AS cit', 'cit.id', '=', 'cli_bra.city_id')
        ->join('order_details AS ord_det', 'ord_det.order_id', '=', 'ord.id')
        ->join('products AS pro', 'pro.id', '=', 'ord_det.product_id')
        ->join('colors AS col', 'col.id', '=', 'ord_det.color_id')
        ->join('collections AS coll', 'coll.id', '=', 'ord.collection_id')
        ->leftJoin('users AS det_user_wallet', 'det_user_wallet.id', '=', 'ord_det.wallet_user_id')
        ->leftJoin('users AS det_user_dispatched', 'det_user_dispatched.id', '=', 'ord_det.dispatched_user_id')
        ->leftJoin('users AS ord_user_seller', 'ord_user_seller.id', '=', 'ord.seller_user_id')
        ->leftJoin('users AS ord_user_wallet', 'ord_user_wallet.id', '=', 'ord.wallet_user_id');

        DB::statement('DROP VIEW IF EXISTS view_report_sales');
        DB::statement('CREATE VIEW view_report_sales AS ' . $query->toSql()); */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS view_report_sales');
    }
};
