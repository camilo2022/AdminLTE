<?php

namespace App\Http\Controllers;

use App\Models\Access;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;


class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accesses = Access::all();
        if (request()->ajax()) {
            $roles = Role::with("permissions")->select('roles.*','accesses.name as access_name')->join('accesses','accesses.id','=','roles.access_id')->get();

            return datatables()->of($roles)
            ->addColumn('btnEdit', function ($roles) {
                return '<a type="button" class="btn btn-primary text-white btn-sm" onclick="editRole('.$roles->id.',`'.$roles->name.'`,`'.$roles->access_name.'`)" data-toggle="modal" data-target="#modal_rol_editar"><i class="fas fa-pen text-white"></i></a>';
            })
            ->addColumn('btnDelete', function ($roles) {
                return '<a type="button" class="btn btn-danger text-white btn-sm" onclick="deleteRole(`'.route('Dashboard.Rol.Destroy',$roles->id).'`)"><i class="fas fa-trash text-white"></i></a>';
            })
            ->rawColumns(['btnEdit','btnDelete'])->toJson();
        }
        return view('Dashboard.Role.Index', compact('accesses'));

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
            'access' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = [];
            if ($validator->errors()->has('name')) {
                $errors[] = '¡El nombre del rol ya existe!';
            }
            return '¡No se creó el rol por que ya existe!';
        }
        Role::create(['name' => $request->name,]);
        return 'Se creo el rol satisfactoriamente.';
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
        Role::findOrFail($id)->delete();
        return 'Se elimino el rol satisfactoriamente.';
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
