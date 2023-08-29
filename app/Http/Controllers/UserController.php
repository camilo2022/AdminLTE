<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;
use App\UserEnterprise;
use App\Enterprise;
use App\Module;
use App\SubModule;
use App\RolModule;
use App\UserModule;
use App\User;
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

    public function create()
    {
        return view('Dashboard.User.Create');
    }

    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(),
        [
           'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);
        if ($validacion->fails())
        {
            return redirect()->route('Dashboard.User.Create')->withErrors($validacion);
        }
        else{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->enterprises_id = Auth::user()->enterprises_id;
            $user->save();

            $user_enterprise = new UserEnterprise();
            $user_enterprise->user_id = $user->id;
            $user_enterprise->enterprises_id = Auth::user()->enterprises_id;
            $user_enterprise->save();
            return redirect()->route('Dashboard.User.Index')->withSuccess('¡Usuario registrado satisfactoriamente!');
        }
    }

    public function edit($id)
    {
        $user = User::with('roles')->where("id","=", $id)->firstOrFail();
        $roles = Role::all();

        return view('Dashboard.User.Edit', compact('user','roles'));
    }


    public function update(Request $request, $id)
    {
        $validacion = Validator::make(['email' => $request->email],
        [
            'email' => ['required', Rule::unique('users')->ignore($id)]
        ]);
        if($validacion->fails()){
            return redirect()->route('Dashboard.User.Index')->withErrors('¡No se puede actualizar el usuario por que el correo ya existe!');
        }else{
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
    }

    public function show_module($id)
    {
        $user = User::with('roles')->where("id","=", $id)->firstOrFail();
        $mod_noasig = Module::select('modules.id','name_modules')
        ->join('user_modules', 'modules.id', '=', 'user_modules.module_id')
        ->where('user_modules.user_id', '=', $id)
        ->get();
        $asignados = $mod_noasig->pluck('id');
        try{
            $mod_asig = Module::select('modules.id','name_modules')
            ->join('rol_modules', 'modules.id', '=', 'rol_modules.id_module')
            ->join('modules_enterprises', 'modules.id', '=', 'modules_enterprises.modules_id')
            ->where('rol_modules.id_rol', '=', $user->roles[0]->id)
            ->where('modules_enterprises.enterprises_id','=', $user->enterprises_id)
            ->whereNotIn('modules.id', $asignados)
            ->get();
        }catch(\Exception $e){
            $mod_asig = [];
        }

        return view('Dashboard.User.Assign_modulo', compact('user','mod_asig'));
    }

    public function user_assign_module(Request $request, $id)
    {
        $addmodules = array_map('intval', $request->addmodules);
        sort($addmodules);
        foreach($addmodules as $addmodule){
            $user_modules = new UserModule();
            $user_modules->user_id = $id;
            $user_modules->module_id = $addmodule;
            $user_modules->sub_modules = '[]';
            $user_modules->save();
        }
        return redirect()->route('Dashboard.User.Index')->withSuccess('¡Modulos asignados al usuario satisfactoriamente!');
    }

    public function hide_module($id)
    {
        $user = User::with('roles')->where("id","=", $id)->firstOrFail();
        $mod_noasig = Module::select('modules.id','name_modules')
        ->join('user_modules', 'modules.id', '=', 'user_modules.module_id')
        ->where('user_modules.user_id', '=', $id)
        ->get();

        return view('Dashboard.User.Unssign_modulo', compact('user','mod_noasig'));
    }

    public function user_unssign_module(Request $request, $id)
    {
        $remmodules = array_map('intval', $request->remmodules);
        sort($remmodules);
        $modulos_asig = UserModule::where('user_id','=',$id)->get();
        foreach($modulos_asig as $modulo_asig){
            if (in_array($modulo_asig->module_id, $remmodules)) {
                $modulo_asig->delete();
            }
        }
        return redirect()->route('Dashboard.User.Index')->withSuccess('¡Modulos removidos al usuario satisfactoriamente!');
    }

    public function show_submodule($id)
    {
        $user = User::where('id', '=', $id)->first();
        $modulesdata = UserModule::join('modules', 'user_modules.module_id', '=', 'modules.id')
        ->where('user_id','=', $id)
        ->select('modules.name_modules','modules.id')
        ->get();
        return view('Dashboard.User.Assign_submodule', compact('modulesdata', 'user'));
    }

    public function show_allsubmodule(Request $request)
    {
        $user = User::findOrFail($request->id_user);
        $submodules = UserModule::where('module_id','=', $request->id)
        ->where('user_id','=', $user->id)
        ->first();
        try{
            $subNot = SubModule::select('submodules.*')
            ->join('submodules_enterprises','submodules.id','=','submodules_enterprises.submodules_id')
            ->join('rol_submodules', 'submodules.id', '=', 'rol_submodules.id_submodule')
            ->where('rol_submodules.id_rol', '=', $user->roles[0]->id)
            ->whereNotIn('submodules.id', json_decode($submodules->sub_modules,true))
            ->where('id_module', '=', $request->id)
            ->where('submodules_enterprises.enterprises_id','=',$user->enterprises_id)
            ->get();
        }catch(Exception $e){
            $subNot = [];
        }
            
        return $subNot;
    }

    public function user_assign_submodule(Request $request, $id)
    {
        $saveusermodel = UserModule::where('user_id', '=', $id)
        ->where('module_id', '=', $request->module_id)
        ->first();
        $sub_modules = array_map('intval', $request->sub_modules);
        sort($sub_modules);
        $sub_mod = json_decode($saveusermodel->sub_modules, true);
        foreach($sub_modules as $sub_module){
            $sub_mod[] = $sub_module;
        }
        sort($sub_mod);
        $saveusermodel->sub_modules = json_encode($sub_mod);
        $saveusermodel->save();
        return back()->withSuccess('¡SubModulos asignados al usuario satisfactoriamente!');
    }

    public function hide_submodule($id)
    {
        $user = User::with('roles')->where("id","=", $id)->firstOrFail();
        $modulesdata = Module::select('modules.id','name_modules')
        ->join('user_modules', 'modules.id', '=', 'user_modules.module_id')
        ->where('user_modules.user_id', '=', $id)
        ->get();

        return view('Dashboard.User.Unssign_submodule', compact('user','modulesdata'));
    }

    public function hide_allsubmodule(Request $request)
    {
        $submodules = UserModule::where('module_id','=', $request->id)
        ->where('user_id','=', $request->id_user)
        ->first();
        $subYes = SubModule::select('submodules.*')
        ->whereIn('id', json_decode($submodules->sub_modules,true))
        ->where('id_module', '=', $request->id)
        ->get();
        return $subYes;
    }

    public function user_unssign_submodule(Request $request, $id)
    {
        $saveusermodel = UserModule::where('user_id', '=', $id)
        ->where('module_id', '=', $request->module_id)
        ->first();
        $sub_modules = array_map('intval', $request->sub_modules);
        sort($sub_modules);
        $sub_mod = json_decode($saveusermodel->sub_modules, true);
        foreach($sub_mod as $sub_m => $valor){
            if(in_array($valor,$sub_modules)){
                unset($sub_mod[$sub_m]);
            }
        }
        sort($sub_mod);
        $saveusermodel->sub_modules = json_encode($sub_mod);
        $saveusermodel->save();
        return back()->withSuccess('¡SubModulos removidos al usuario satisfactoriamente!');
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

    public function updateuser(Request $request)
    {
        $passHashed = Hash::make($request->password);
        $idUser = $request->id_user;
        $user = User::where('id','=',$idUser)->first();
        $user->password = $passHashed;
        $user->save();
        return back()->withSuccess('¡Contraseña cambiada satisfactoriamente!');
    }
}
