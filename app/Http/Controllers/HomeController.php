<?php

namespace App\Http\Controllers;

use App\Models\Size;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $query = "CREATE OR REPLACE VIEW view_order_details AS ";

        $selectQuery = "SELECT order_id, product_id, color_id";

        $sizes = Size::all(); // obtener los registros de la tabla sizes

        foreach ($sizes as $size) {
        $selectQuery .= ", SUM(CASE WHEN size_id = {$size->id} THEN quantity ELSE 0 END) AS {$size->code}";
        }

        $query .= $selectQuery . " FROM order_details GROUP BY order_id, product_id, color_id";
        return $query;
        return view('Dashboard.home');
    }

}
