<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use App\RolSubModule;
use App\SubModule;
use App\Module;
use App\User;

class SubModulesController extends Controller
{
    public function index()
    {
        $submodules = SubModule::with('module','roles')->get();
        $moduless = Module::all();
        return view('Dashboard.SubModules.Index', compact('submodules','moduless'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_submodules' => 'required|unique:submodules',
            'route' => [
                'required',
                'unique:submodules',
            ],
        ]);
        
        if ($validator->fails()) {
            if ($validator->errors()->has('name_submodules')) {
                return back()->withErrors('¡No se creó el submodulo porque ya existe!');
            }
        
            if ($validator->errors()->has('route')) {
                return back()->withErrors('¡La ruta ya está asignada a otro submodulo o no existe!');
            }
        }

        if (Route::has($request->route)) {
            SubModule::create([
                'name_submodules' => $request->name_submodules, 
                'route' => $request->route, 
                'id_module' => $request->modulo
            ]);
        
            return back()->withSuccess('¡SubModulo creado satisfactoriamente!');
        } else {
            return back()->withErrors('¡La ruta proporcionada no existe en las rutas definidas!');
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name_submodules' => [
                'required',
                Rule::unique('submodules')->ignore($id),
            ],
            'route' => [
                'required',
                Rule::unique('submodules')->ignore($id),
            ],
        ]);
        if ($validator->fails()) {
            if ($validator->errors()->has('name_submodules')) {
                return back()->withErrors('¡No se actualizo el submodulo porque ya existe!');
            }
        
            if ($validator->errors()->has('route')) {
                return back()->withErrors('¡La ruta ya está asignada a otro submodulo o no existe!');
            }
        }

        $permission->update([
            'name_submodules' => $request->name_submodules,
            'route' => $request->route,
            'id_modulo' => $request->modulo
        ]);

        return back()->withSuccess('¡SubModulo actualizado satisfactoriamente!');
    }

    public function show($id)
    {
        $submodule = SubModule::where("id", "=", $id)->firstOrFail();
        $roles = Role::whereNotIn('id', function ($query) use ($id) {
            $query->select('id_rol')
                ->from('rol_submodules')
                ->where('id_submodule', $id);
        })
        ->get();
        return view('Dashboard.SubModules.Show', compact('submodule','roles'));
    }

    public function module_assign_rol(Request $request, $id)
    {
        $roles = array_map('intval', $request->roles);
        sort($roles);
        foreach($roles as $rol){
            $rol_module = new RolSubModule();
            $rol_module->id_rol = $rol;
            $rol_module->id_submodule = $id;
            $rol_module->save();
        }
        return redirect()->route('Dashboard.SubModule.Index')->withSuccess('¡Roles asignados al submodulo satisfactoriamente!'); 
    }

    public function hide($id)
    {
        $submodule = SubModule::where("id", "=", $id)->firstOrFail();
        $roles = SubModule::with('roles')->where("id", "=", $id)->firstOrFail();
        return view('Dashboard.SubModules.Hide', compact('submodule','roles'));
    }

    public function module_unssign_rol(Request $request, $id)
    {
        $roles = array_map('intval', $request->roles);
        sort($roles);
        RolSubModule::whereIn("id_rol",$roles)->where("id_submodule","=",$id)->delete();
        return redirect()->route('Dashboard.SubModule.Index')->withSuccess('¡Roles removidos al submodulo satisfactoriamente!');
    }

    public function destroy($id)
    {
        try{
            SubModule::findOrFail($id)->delete();
            return back()->withSuccess('¡SubModulo eliminado satisfactoriamente!');
        }catch(\Exception $e){
            return back()->withErrors('¡Error al eliminar el submodulo!');
        }   
    }
}
