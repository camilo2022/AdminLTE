<?php
namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\RolesAndPermissions\RolesAndPermissionsIndexQueryCollection;
use App\Http\Requests\RolesAndPermissions\RolesAndPermissionsIndexQueryRequest;
use App\Http\Requests\RolesAndPermissions\RolesAndPermissionsStoreRequest;
use App\Http\Requests\RolesAndPermissions\RolesAndPermissionsDeleteRequest;
use App\Http\Requests\RolesAndPermissions\RolesAndPermissionsPermissionsQueryRequest;
use App\Http\Requests\RolesAndPermissions\RolesAndPermissionsRolesQueryRequest;
use App\Http\Requests\RolesAndPermissions\RolesAndPermissionsUpdateRequest;
use App\Traits\ApiMessage;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class RolesAndPermissionsController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.RoleAndPermissions.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(RolesAndPermissionsIndexQueryRequest $request)
    {
        try{
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            // Consultar roles con relaciones y aplicar filtros
            $rolesAndPermissions = Role::with('permissions')
                ->when($request->filled('search'),
                    function ($query) use ($request) {
                        $query->search($request->input('search'));
                    }
                )
                ->when($request->filled('start_date') && $request->filled('end_date'),
                    function ($query) use ($start_date, $end_date) {
                        $query->filterByDate($start_date, $end_date);
                    }
                )
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));
            // Devolver una respuesta exitosa con los roles y permisos paginados
            return $this->successResponse(
                new RolesAndPermissionsIndexQueryCollection($rolesAndPermissions),
                $this->getMessage('Success'),
                200
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            // Devolver una respuesta de error en caso de excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function create()
    {
        try {
            return $this->successResponse(
                '',
                'Ingrese los datos para hacer la validacion y registro.',
                200
            );
        } catch (Exception $e) {
            // Devolver una respuesta de error en caso de excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function store(RolesAndPermissionsStoreRequest $request)
    {
        try {
            DB::beginTransaction();
             // Crear el rol con el nombre proporcionado en la solicitud
            $role = new Role();
            $role->name = $request->input('role');
            $role->save();
            // Asignar permisos al rol
            $permissions = collect($request->input('permissions'))->map(function ($permission) {
                // Crear o recuperar un permiso con el nombre proporcionado
                return Permission::firstOrCreate([
                    'name' => $permission
                ]);
            });
            // Sincronizar los permisos con el rol
            $role->syncPermissions($permissions);
            // Confirmar la transacción de base de datos
            DB::commit();
            // Devolver una respuesta exitosa con el rol y los permisos creados
            return $this->successResponse(
                $role,
                'El rol y sus permisos fueron creados exitosamente.',
                201
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            // Deshacer la transacción en caso de excepción y devolver una respuesta de error
            DB::rollback();
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function edit($id)
    {
        try {
            return $this->successResponse(
                Role::with('permissions')->findOrFail($id),
                'El rol y los permisos fueron encontrados exitosamente.',
                204
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function update(RolesAndPermissionsUpdateRequest $request, $roleId)
    {
        try {
            DB::beginTransaction();
            // Encontrar el rol
            $role = Role::findOrFail($roleId);

            $currentPermissions = collect($request->input('permissions'));
            // Obtener los permisos actuales del rol
            $existingPermissions = $role->permissions->pluck('name');
            // Detectar nuevos permisos agregados
            $newPermissions = $currentPermissions->diff($existingPermissions);
            // Crear o recuperar nuevos permisos y agregarlos al arreglo de permisos actuales
            foreach ($newPermissions as $permissionName) {
                $permission = Permission::firstOrCreate(['name' => $permissionName]);
                $currentPermissions->push($permission->name); // Agregar el nuevo permiso al arreglo
            }
            // Utilizar syncPermissions() para sincronizar todos los permisos al rol
            $role->syncPermissions($currentPermissions);
            // Detectar permisos eliminados
            $removedPermissions = $existingPermissions->diff($currentPermissions);
            // Revocar permisos del rol y eliminarlos si no están asignados a ningún rol
            foreach ($removedPermissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission) {
                    $role->revokePermissionTo($permission);
                    // Verificar si el permiso ya no está asignado a ningún rol
                    if ($permission->roles->isEmpty()) {
                        $permission->delete();
                    }
                }
            }
            // Utilizar syncPermissions() para sincronizar todos los permisos al rol
            $role->syncPermissions($currentPermissions);

            $role->name = $request->input('role');
            $role->save();

            // Confirmar la transacción de base de datos
            DB::commit();
            // Devolver una respuesta exitosa con el rol y los permisos actualizados
            return $this->successResponse(
                $role,
                'El rol y sus permisos fueron actualizados exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            // Deshacer la transacción en caso de excepción y devolver una respuesta de error
            DB::rollback();
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function delete(RolesAndPermissionsDeleteRequest $request)
    {
        try {
            // Iniciar una transacción de base de datos
            DB::beginTransaction();
            // Eliminar roles y permisos
            Role::whereIn('id', $request->input('role_id'))->delete();
            Permission::whereIn('id', $request->input('permission_id'))->delete();
            // Confirmar la transacción de base de datos
            DB::commit();
            // Devolver una respuesta exitosa
            return $this->successResponse(
                '',
                'El rol y sus permisos fueron eliminados exitosamente.',
                204
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (Exception $e) {
            // Deshacer la transacción en caso de excepción y devolver una respuesta de error
            DB::rollback();
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
