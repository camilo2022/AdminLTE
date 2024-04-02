<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;


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
        $clients = Client::withTrashed()->count();
        $users = User::withTrashed()->count();
        $orders = Order::count();
        $products = Product::withTrashed()->count();

        return view('Dashboard.home', compact('clients', 'users', 'orders', 'products'));
    }

}
