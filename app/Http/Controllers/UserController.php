<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Access;
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
        $users = User::where('enterprise_id', '=', Auth::user()->enterprise_id)->get();

        return view('Dashboard.User.Index', compact('users'));
    }

    public function create()
    {
        return view('Dashboard.User.Create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'name' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        if ($validator->fails()){

            $errors = [];
            if ($validator->errors()->has('name')) {
                $errors[] = '¡No se recibe el nombre o excede limite de caracteres!';
            }
            if ($validator->errors()->has('email')) {
                $errors[] = '¡El correo ya esta asignado a otro usuario!';
            }
            if ($validator->errors()->has('password')) {
                $errors[] = '¡La contraseña debe tener minimo 8 caracteres!';
            }
            return redirect()->route('Dashboard.User.Index')->withErrors($errors);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->enterprise_id = Auth::user()->enterprise_id;
        $user->save();

        return redirect()->route('Dashboard.User.Index')->withSuccess('¡Usuario registrado satisfactoriamente!');

    }

    public function edit($id)
    {
        $user = User::with('roles')->where("id","=", $id)->firstOrFail();

        if (request()->ajax()) {
            $accesses = Access::with('roles')->get()
            ->each(function ($access) use ($id) {
                $access->roles->each(function ($role) use ($id) {
                    $isAssigned = DB::table('model_has_roles')
                        ->where('role_id', $role->id)
                        ->where('model_type', 'App\\Models\\User') // Ajusta el modelo si es diferente
                        ->where('model_id', $id)
                        ->exists();
                    $role->asignado = $isAssigned;
                });
            });

            return datatables()->of($accesses)->toJson();
        }
        return view('Dashboard.User.Edit', compact('user','id'));
    }

    public function assignRole(Request $request)
    {
        $user = User::find($request->id_user);
        $user->assignRole($request->rol_name);
        return "Success";
    }

    public function unassignRole(Request $request)
    {
        $user = User::find($request->id_user);
        $user->removeRole($request->rol_name);
        return "Success";
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
        [
            'name' => ['required', 'max:20'],
            'email' => ['required', Rule::unique('users')->ignore($id)]
        ]);

        if($validator->fails()){

            $errors = [];
            if ($validator->errors()->has('name')) {
                $errors[] = '¡No se recibe el nombre o excede limite de caracteres!';
            }
            if ($validator->errors()->has('email')) {
                $errors[] = '¡El correo ya esta asignado a otro usuario!';
            }
            return redirect()->route('Dashboard.User.Index')->withErrors($errors);
        }

        $user = User::with('roles')->where('id','=',$id)->firstOrFail();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('Dashboard.User.Index')->withSuccess('¡Usuario actualizado satisfactoriamente!');

    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'password' => ['required', 'string', 'min:8']
        ]);
        if ($validator->fails()){

            $errors = [];
            if ($validator->errors()->has('password')) {
                $errors[] = '¡La contraseña debe tener minimo 8 caracteres!';
            }
            return redirect()->route('Dashboard.User.Index')->withErrors($errors);
        }

        $user = User::find($request->id_user);
        $user->password = Hash::make($request->password);
        $user->save();
        return back()->withSuccess('¡Contraseña cambiada satisfactoriamente!');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return back()->withSuccess('¡Usuario inactivado satisfactoriamente!');
    }

    public function restore($id)
    {
        $user =  User::withTrashed()->where('id', '=', $id)->first();
        $user->restore();
        return back()->withSuccess('¡Usuario activado satisfactoriamente!');
    }

    public function archive()
    {
        $userse = User::onlyTrashed()->get();
        return view('Dashboard.User.Index_Inactivos', compact('userse'));
    }


}
