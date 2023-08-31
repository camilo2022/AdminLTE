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
        $user->enterprises_id = Auth::user()->enterprises_id;
        $user->save();

        return redirect()->route('Dashboard.User.Index')->withSuccess('¡Usuario registrado satisfactoriamente!');

    }

    public function edit($id)
    {
        $user = User::with('roles')->where("id","=", $id)->firstOrFail();
        return $roles = Role::select('roles.id as id_role','roles.name as name_role','modules.id as id_module','modules.name as name_modules','submodules.id as id_submodules','submodules.name as name_submodules','submodules.module_id','submodules.role_id')
        ->join('submodules','submodules.role_id','=','roles.id')
        ->join('modules','modules.id','=','submodules.module_id')
        ->get();
        if (request()->ajax()) {
            $roles = Role::select('roles.id as id_rol','roles.name as name_rol','modules.*','submodules.*')
            ->join('submodules','submodules.role_id','=','roles.id')
            ->join('modules','modules.id','=','submodules.module_id')
            ->get();

            return datatables()->of($roles)->addColumn('btnoes', function ($roles) {
                return '<input type="checkbox" name="my-checkbox" checked data-bootstrap-switch data-off-color="danger" data-on-color="success">';
            })->rawColumns(['btnoes'])->toJson();
        }
        return view('Dashboard.User.Edit', compact('user'));
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
        if(isset($user->roles[0])){
            if($user->roles[0]->name != $request->rol){
                UserModule::where('user_id', $user->id)
                ->whereNotIn('module_id', function ($query) use ($user, $request) {
                    $query->select('id_module')
                        ->from('rol_modules')
                        ->join('roles', 'rol_modules.id_rol', '=', 'roles.id')
                        ->where('roles.name', '=', $request->rol)
                        ->where('rol_modules.id_rol', '=', $user->rol_id);
                })
                ->delete();
            }
            $user->removeRole($user->roles[0]->id);
        }
        $user->assignRole($request->rol);
        return redirect()->route('Dashboard.User.Index')->withSuccess('¡Rol asignado al usuario satisfactoriamente!');

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
