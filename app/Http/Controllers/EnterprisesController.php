<?php

namespace App\Http\Controllers;

use App\Enterprise;
use App\Http\Controllers\Controller;
use App\Module;
use App\ModuleEnterprise;
use App\SubModuleEnterprise;
use App\SubModule;
use App\User;
use App\UserEnterprise;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class EnterprisesController extends Controller
{
    public function index()
    {
        $enterprises = Enterprise::with('modules','users','submodules')->get();
        return view('Dashboard.Enterprises.Index', compact('enterprises'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_enterprise' => 'required|unique:enterprises',
        ]);
        
        if ($validator->fails()) {
            if ($validator->errors()->has('name_enterprise')) {
                return back()->withErrors('¡No se creó la empresa porque ya existe!');
            }
        }
        Enterprise::create([
            'name_enterprise' => $request->name_enterprise,
        ]);

        return back()->withSuccess('¡Empresa creada satisfactoriamente!');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name_enterprise' => [
                'required',
                Rule::unique('enterprises')->ignore($id),
            ],
        ]);
        
        if ($validator->fails()) {
            if ($validator->errors()->has('name_enterprise')) {
                return back()->withErrors('¡No se actualizó la empresa porque ya existe!');
            }
        }
        $enterprise = Enterprise::findOrFail($id);
        $enterprise->name_enterprise = $request->name_enterprise;
        $enterprise->save();

        return back()->withSuccess('¡Empresa actualizada satisfactoriamente!');
    }

    public function show_users($id)
    {
        $enterprise = Enterprise::findOrFail($id);
        $users = User::select('users.*')
        ->whereNotIn('id', function ($query) use ($id) {
            $query->select('user_id')
                  ->from('user_enterprises')
                  ->where('enterprises_id', '=', $id);
        })
        ->get();
        return view('Dashboard.Enterprises.Assign_user', compact('enterprise','users'));
    }

    public function enterprise_assign_user(Request $request, $id)
    {
        $addusers = array_map('intval', $request->addusers);
        sort($addusers);
        foreach($addusers as $adduser){
            $user_enterprise = new UserEnterprise();
            $user_enterprise->user_id = $adduser;
            $user_enterprise->enterprises_id = $id;
            $user_enterprise->save();
        }
        return redirect()->route('Dashboard.Enterprises.Index')->withSuccess('¡Usuarios asignados a la empresa satisfactoriamente!');
    }

    public function hide_users($id)
    {
        $enterprise = Enterprise::findOrFail($id);
        $users = User::select('users.*')
        ->whereIn('id', function ($query) use ($id) {
            $query->select('user_id')
                  ->from('user_enterprises')
                  ->where('enterprises_id', '=', $id);
        })
        ->get();
        return view('Dashboard.Enterprises.Unssign_user', compact('enterprise','users'));
    }

    public function enterprise_unssign_user(Request $request, $id)
    {
        $remusers = array_map('intval', $request->remusers);
        sort($remusers);
        UserEnterprise::whereIn('user_id',$remusers)->where('enterprises_id', '=', $id)->delete();
        return redirect()->route('Dashboard.Enterprises.Index')->withSuccess('¡Usuarios removidos a la empresa satisfactoriamente!');
    }

    public function show_modules($id)
    {
        $enterprise = Enterprise::findOrFail($id);
        $moduless = Module::select('modules.*')
        ->whereNotIn('id', function ($query) use ($id) {
            $query->select('modules_id')
                  ->from('modules_enterprises')
                  ->where('enterprises_id', '=', $id);
        })
        ->get();

        return view('Dashboard.Enterprises.Assign_modulo', compact('enterprise','moduless'));
    }

    public function enterprise_assign_modules(Request $request, $id)
    {
        $addmodules = array_map('intval', $request->addmodules);
        sort($addmodules);
        foreach($addmodules as $addmodule){
            $modules_enterprise = new ModuleEnterprise();
            $modules_enterprise->enterprises_id = $id;
            $modules_enterprise->modules_id = $addmodule;
            $modules_enterprise->save();
        }
        return redirect()->route('Dashboard.Enterprises.Index')->withSuccess('¡Modulos asignados a la empresa satisfactoriamente!');
    }

    public function hide_modules($id)
    {
        $enterprise = Enterprise::findOrFail($id);
        $moduless = Module::select('modules.*')
        ->whereIn('id', function ($query) use ($id) {
            $query->select('modules_id')
                  ->from('modules_enterprises')
                  ->where('enterprises_id', '=', $id);
        })
        ->get();

        return view('Dashboard.Enterprises.Unssign_modulo', compact('enterprise','moduless'));
    }

    public function enterprise_unssign_modules(Request $request, $id)
    {
        $remmodules = array_map('intval', $request->remmodules);
        sort($remmodules);
        ModuleEnterprise::whereIn('modules_id',$remmodules)->where('enterprises_id', '=', $id)->delete();
        return redirect()->route('Dashboard.Enterprises.Index')->withSuccess('¡Modulos removidos a la empresa satisfactoriamente!');
    }

    public function show_submodules($id)
    {
        $enterprise = Enterprise::findOrFail($id);
        $moduless = Module::select('modules.*')
        ->whereIn('id', function ($query) use ($id) {
            $query->select('modules_id')
                  ->from('modules_enterprises')
                  ->where('enterprises_id', '=', $id);
        })
        ->get();
        return view('Dashboard.Enterprises.Assign_submodule', compact('enterprise', 'moduless'));
    }

    public function show_allsubmodules(Request $request)
    {
        $subNot = SubModule::select('submodules.*')
        ->whereNotIn('id', function ($query) use ($request) {
            $query->select('submodules_id')
                  ->from('submodules_enterprises')
                  ->where('enterprises_id', '=', $request->id_enterprise);
        })
        ->where('id_module','=',$request->id)
        ->get();

        return $subNot;
    }

    public function enterprise_assign_submodules(Request $request, $id)
    {
        $sub_modules = array_map('intval', $request->sub_modules);
        sort($sub_modules);
        foreach($sub_modules as $sub_module){
            $submodules_enterprise = new SubModuleEnterprise();
            $submodules_enterprise->enterprises_id = $id;
            $submodules_enterprise->submodules_id = $sub_module;
            $submodules_enterprise->save();
        }
        return back()->withSuccess('¡SubModulos asignados a la empresa satisfactoriamente!');
    }

    public function hide_submodules($id)
    {
        $enterprise = Enterprise::findOrFail($id);
        $moduless = Module::select('modules.*')
        ->whereIn('id', function ($query) use ($id) {
            $query->select('modules_id')
                  ->from('modules_enterprises')
                  ->where('enterprises_id', '=', $id);
        })
        ->get();
        return view('Dashboard.Enterprises.Unssign_submodule', compact('enterprise', 'moduless'));
    }

    public function hide_allsubmodules(Request $request)
    {
        $subYes = SubModule::select('submodules.*')
        ->whereIn('id', function ($query) use ($request) {
            $query->select('submodules_id')
                  ->from('submodules_enterprises')
                  ->where('enterprises_id', '=', $request->id_enterprise);
        })
        ->where('id_module','=',$request->id)
        ->get();

        return $subYes;
    }

    public function enterprise_unssign_submodules(Request $request, $id)
    {
        $sub_modules = array_map('intval', $request->sub_modules);
        sort($sub_modules);
        SubModuleEnterprise::whereIn('submodules_id',$sub_modules)->where('enterprises_id', '=', $id)->delete();
        return back()->withSuccess('¡SubModulos removidos a la empresa satisfactoriamente!');
    } 

    public function destroy($id)
    {
        try{
            Enterprise::findOrFail($id)->delete();
            return back()->withSuccess('¡Empresa eliminada satisfactoriamente!');
        }catch(\Exception $e){
            return back()->withErrors('¡Error al eliminar la empresa!');
        }   
    }
}
