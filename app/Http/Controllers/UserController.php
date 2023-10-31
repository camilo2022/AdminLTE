<?php
namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AssignRoleAndPermissionsQueryRequest;
use App\Http\Requests\User\AssignRoleAndPermissionsRequest;
use App\Http\Requests\User\RemoveRoleAndPermissionsQueryRequest;
use App\Http\Requests\User\UserIndexQueryRequest;
use App\Http\Resources\User\UserIndexQueryCollection;
use App\Http\Requests\User\RemoveRolesAndPermissionsRequest;
use App\Http\Requests\User\UserDeleteRequest;
use App\Http\Requests\User\UserInactivesQueryRequest;
use App\Http\Requests\User\UserPasswordRequest;
use App\Http\Requests\User\UserRestoreRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserInactivesCollection;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use ApiResponser;

    private $success = 'Consulta Exitosa.';
    private $errorException = 'Algo salió mal.';
    private $errorQueryException = 'Error del servidor de la base de datos.';
    private $errorModelNotFoundException = 'El registro no fue encontrado en la base de datos.';

    public function index()
    {
        try {
            return view('Dashboard.Users.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(UserIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $users = User::with('roles', 'permissions')
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

            return $this->successResponse(
                new UserIndexQueryCollection($users),
                $this->success,
                200
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->errorQueryException,
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->errorException,
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function inactives()
    {
        try {
            return view('Dashboard.Users.Inactives');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function inactivesQuery(UserInactivesQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();

            $users = User::with('roles','permissions')
                //Consulta por nombre, apellido, cedula o correo
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
                ->onlyTrashed() //Trae los registros 'eliminados'
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new UserInactivesCollection($users),
                $this->success,
                200
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->errorQueryException,
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->errorException,
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function store(UserStoreRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->input('name'),
                'last_name' => $request->input('last_name'),
                'document_number' => $request->input('document_number'),
                'phone_number' => $request->input('phone_number'),
                'address' => $request->input('address'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            return $this->successResponse(
                $user,
                'El usuario fue registrado exitosamente.',
                201
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->errorQueryException,
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            // Devolver una respuesta de error en caso de excepción
            return $this->errorResponse(
                [
                    'message' => $this->errorException,
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id)->update([
                'name' => $request->input('name'),
                'last_name' => $request->input('last_name'),
                'document_number' => $request->input('document_number'),
                'phone_number' => $request->input('phone_number'),
                'address' => $request->input('address'),
                'email' => $request->input('email'),
            ]);

            return $this->successResponse(
                $user,
                'El usuario fue actualizado exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->errorModelNotFoundException,
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->errorQueryException,
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->errorException,
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function password(UserPasswordRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id)->update([
                'password' => Hash::make($request->input('password')),
            ]);

            return $this->successResponse(
                $user,
                'La contraseña del usuario fue actualizada exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->errorModelNotFoundException,
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->errorQueryException,
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->errorException,
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function delete(UserDeleteRequest $request)
    {
        try {
            $user = User::findOrFail($request->id)->delete();
            return $this->successResponse(
                $user,
                'El usuario fue eliminado exitosamente.',
                204
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->errorModelNotFoundException,
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->errorException,
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function restore(UserRestoreRequest $request)
    {
        try {
            $user = User::withTrashed()->findOrFail($request->id)->restore();
            return $this->successResponse(
                $user,
                'El usuario fue restaurado exitosamente.',
                204
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->errorModelNotFoundException,
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->errorException,
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function assignRoleAndPermissionsQuery(AssignRoleAndPermissionsQueryRequest $request)
    {
        try {
            $user = User::findOrFail($request->id);
            $roles = Role::with('permissions')->get();

            $rolesWithMissingPermissions = [];

            foreach ($roles as $role) {
                $missingPermissions = [];
                foreach (collect($role->permissions)->pluck('name') as $permission) {
                    if (!$user->hasRole($role->name) || !$user->hasDirectPermission($permission)) {
                        $missingPermissions[] = $permission;
                    }
                }
                if (!empty($missingPermissions)) {
                    $rolesWithMissingPermissions[] = (object) [
                        'role' => $role->name,
                        'permissions' => $missingPermissions
                    ];
                }
            }

            return $this->successResponse(
                $rolesWithMissingPermissions,
                'La consulta para asignar rol y permisos realizada exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->errorModelNotFoundException,
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->errorException,
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function assignRoleAndPermissions(AssignRoleAndPermissionsRequest $request)
    {
        try {
            // Obtener el rol existente
            $role = Role::findByName($request->role);
            $user = User::findOrFail($request->id);
            if (!$role) {
                return $this->successResponse(
                    $user,
                    'El rol especificado no existe.',
                    404
                );
            }
            // Verificar si el usuario no tiene el rol
            if (!$user->hasRole($request->role)) {
                // Asignar el rol al usuario
                $user->assignRole([$role]);
            }

            // Asociar los permisos existentes del rol al usuario
            $user->givePermissionTo($request->permissions);
            return $this->successResponse(
                $user,
                'El rol y los permiso fueron asignados al usuario exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->errorModelNotFoundException,
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (Exception $e) {
            // Manejar otras excepciones
            return $this->errorResponse(
                [
                    'message' => $this->errorException,
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function removeRoleAndPermissionsQuery(RemoveRoleAndPermissionsQueryRequest $request)
    {
        try {
            $user = User::with('roles.permissions','permissions')->findOrFail($request->id);

            $rolesWithMissingPermissions = [];

            foreach($user->roles as $role){
                $missingPermissions = [];
                foreach (collect($role->permissions)->pluck('name') as $permission) {
                    if ($user->hasDirectPermission($permission)) {
                        $missingPermissions[] = $permission;
                    }
                }
                if (!empty($missingPermissions)) {
                    $rolesWithMissingPermissions[] = (object) [
                        'role' => $role->name,
                        'permissions' => $missingPermissions
                    ];
                }
            }

            return $this->successResponse(
                $rolesWithMissingPermissions,
                'La consulta para remover rol y permisos realizada exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->errorModelNotFoundException,
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->errorException,
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function removeRoleAndPermissions(RemoveRolesAndPermissionsRequest $request)
    {
        try {
            $role = Role::with('permissions')->findByName($request->role);
            $user = User::findOrFail($request->id);

            // Remover los permisos del usuario
            $user->revokePermissionTo($request->permissions);

            $missingPermissions = [];

            foreach (collect($role->permissions)->pluck('name') as $permission) {
                if ($user->hasDirectPermission($permission)) {
                    $missingPermissions[] = $permission;
                }
            }

            if(empty($missingPermissions)) {
                // Remover el rol del usuario
                $user->removeRole($request->role);
            }

            return $this->successResponse(
                $user,
                'El rol y los permisos fueron removidos al usuario exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->errorModelNotFoundException,
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->errorException,
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
