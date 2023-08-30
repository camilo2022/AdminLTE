<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use App\RolModule;
use App\User;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rol = Role::with("permissions")->get();
        $permission = Permission::all(); 
        return view('Dashboard.Rol.Index', compact('rol', 'permission') );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles',
        ]);
        if ($validator->fails()) {
            return back()->withErrors('¡No se creó el rol por que ya existe!');
        }
        $rol = Role::create(['name' => $request->name,]);
        return back()->withSuccess('¡Rol creado satisfactoriamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rol = Role::where("id", "=", $id)->firstOrFail();
        $permissions = Permission::whereNotIn('id', function ($query) use ($id) {
            $query->select('permission_id')
                ->from('role_has_permissions')
                ->where('role_id', $id);
        })
        ->get();
        return view('Dashboard.Rol.Show', compact('rol','permissions'));
    }

    public function rol_assign_permission(Request $request, $id)
    {
        $rol = Role::where("id","=",$id)->firstOrFail();
        $permisos = $request->permisos;
        foreach($permisos as $permiso)
        {
            $rol->givePermissionTo($permiso);
        }
        return redirect()->route('Dashboard.Rol.Index')->withSuccess('¡Permisos asignados al rol satisfactoriamente!'); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rol = Role::where("id","=", $id)->firstOrFail();
        $rol->name = $request->name;
        $rol->save();
        return back()->withSuccess('¡Rol actualizado satisfactoriamente!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Role::findOrFail($id)->delete();
            return back()->withSuccess('¡Rol eliminado satisfactoriamente!');
        }catch(\Exception $e){
            return back()->withErrors('¡Error al eliminar el rol!');
        }   
    }

    public function hide($id)
    {
        $rol = Role::where("id", "=", $id)->firstOrFail();
        $permission = Role::with("permissions")->where("id", "=", $id)->firstOrFail();
        return view('Dashboard.Rol.Hide', compact('permission','rol'));
    }

    public function rol_unssign_permission(Request $request, $id)
    {
        $rol = Role::where("id","=",$id)->firstOrFail();
        $permisos = $request->permisos;
        foreach($permisos as $permiso)
        {
            $rol->revokePermissionTo($permiso);
        }
        return redirect()->route('Dashboard.Rol.Index')->withSuccess('¡Permisos revocados al rol satisfactoriamente!');
    }

}
