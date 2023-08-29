<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\SubModule;
use App\Models\User;
use App\Models\UserEnterprise;
use App\Models\UserModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;
use Exception;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->hasRole('superadmin')) {
            $users = User::all();
        } else {
            $users = User::whereDoesntHave('roles', function ($query) {
                $query->where('name', 'superadmin');
            })->where('enterprises_id', '=', Auth::user()->enterprises_id)->get();
        }

        return view('Dashboard.User.Index', compact('users'));
    }

}
