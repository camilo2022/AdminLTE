<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Module;
use App\Enterprise;
use App\RolModule;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class ModuleController extends Controller
{
    public function index()
    {
        $moduless = Module::with('roles','enterprises','submodules')->get();
        return view('Dashboard.Module.Index', compact('moduless'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_modules' => 'required|unique:modules',
            'icon_modules' => 'required|unique:modules',
        ]);
        
        if ($validator->fails()) {
            if ($validator->errors()->has('name_modules')) {
                return back()->withErrors('¡No se creó el modulo porque ya existe!');
            }
        
            if ($validator->errors()->has('icon_modules')) {
                return back()->withErrors('¡No se creó el modulo porque ya hay uno con ese icono asignado!');
            }
        }
        $modulo = new Module();
        $modulo->name_modules = $request->name_modules;
        $modulo->icon_modules = $request->icon_modules;
        $modulo->is_active = 1;
        $modulo->save();

        return back()->withSuccess('¡Modulo creado satisfactoriamente!');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name_modules' => [
                'required',
                Rule::unique('modules')->ignore($id),
            ],
            'icon_modules' => [
                'required',
                Rule::unique('modules')->ignore($id),
            ],
        ]);
        
        if ($validator->fails()) {
            if ($validator->errors()->has('name_modules')) {
                return back()->withErrors('¡No se creó el modulo porque ya existe!');
            }
        
            if ($validator->errors()->has('icon_modules')) {
                return back()->withErrors('¡El icono ya está asignado a otro modulo!');
            }
        }
        
        $modulo = Module::findOrFail($id);
        $modulo->name_modules = $request->name_modules;
        $modulo->icon_modules = $request->icon_modules;
        $modulo->save();

        return back()->withSuccess('¡Modulo actualizado satisfactoriamente!');
    }

    public function show($id)
    {
        $module = Module::where("id", "=", $id)->firstOrFail();
        $roles = Role::whereNotIn('id', function ($query) use ($id) {
            $query->select('id_rol')
                ->from('rol_modules')
                ->where('id_module', $id);
        })
        ->get();
        return view('Dashboard.Module.Show', compact('module','roles'));
    }

    public function module_assign_rol(Request $request, $id)
    {
        $roles = array_map('intval', $request->roles);
        sort($roles);
        foreach($roles as $rol){
            $rol_module = new RolModule();
            $rol_module->id_rol = $rol;
            $rol_module->id_module = $id;
            $rol_module->save();
        }
        return redirect()->route('Dashboard.Module.Index')->withSuccess('¡Roles asignados al modulo satisfactoriamente!'); 
    }

    public function hide($id)
    {
        $module = Module::where("id", "=", $id)->firstOrFail();
        $roles = Module::with("roles")->where("id", "=", $id)->firstOrFail();
        return view('Dashboard.Module.Hide', compact('module','roles'));
    }

    public function module_unssign_rol(Request $request, $id)
    {
        $roles = array_map('intval', $request->roles);
        sort($roles);
        RolModule::whereIn("id_rol",$roles)->where("id_module","=",$id)->delete();
        return redirect()->route('Dashboard.Module.Index')->withSuccess('¡Roles removidos al modulo satisfactoriamente!');
    }

    public function destroy($id)
    {
        try{
            Module::findOrFail($id)->delete();
            return back()->withSuccess('¡Modulo eliminado satisfactoriamente!');
        }catch(\Exception $e){
            return back()->withErrors('¡Error al eliminar el modulo!');
        }   
    }
}
