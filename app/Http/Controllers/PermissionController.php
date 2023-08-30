<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{

    public function index()
    {
        $permissions = Permission::all();
        return view('Dashboard.Permission.Index', compact('permissions'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions',
        ]);
        if ($validator->fails()) {
            return back()->withErrors('¡No se creó el permiso por que ya existe!');
        }

        $permission = Permission::create([
            'name' => $request->name,
        ]);

        return back()->withSuccess('¡Permiso creado satisfactoriamente!');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('permissions')->ignore($permission->id),
            ],
        ]);
        if ($validator->fails()) {
            return back()->withErrors('¡No se actualizó el permiso por que ya existe!');
        }
        $permission->update([
            'name' => $request->name,
        ]);
        return back()->withSuccess('¡Permiso actualizado satisfactoriamente!');
    }

    public function destroy($id)
    {
        Permission::findOrFail($id)->delete();
        return back()->withSuccess('¡Permiso eliminado satisfactoriamente!');
    }
}
