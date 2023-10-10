<?php
namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AssignRoleAndPermissionsRequest;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use ApiResponser;

    private $success = 'Consulta Exitosa.';
    private $errorException = 'Algo salió mal.';
    private $errorQueryException = 'Error del servidor de la base de datos.';
    private $errorModelNotFoundException = 'El usuario no pudo ser encontrado.';

    public function index()
    {
        try {
            return view('Dashboard.Users.Index');
        } catch (Exception $e) {
            return back()->withErrors(
                [
                    'Ocurrió un error al cargar la vista: ' . $e->getMessage()
                ]
            );
        }
    }

    /**
     * Listado de usuarios con filtros y paginación.
     *
     * Esta función devuelve un listado paginado de usuarios aplicando los siguientes filtros:
     *
     * - Filtro por rango de fechas de creación (start_date y end_date).
     * - Filtro por nombre de usuario (search).
     * - Filtro por rol de usuario (role).
     *
     * Si los parámetros de filtro están presentes en la solicitud, se aplicarán a la consulta.
     *
     * @param \App\Http\Requests\UserIndexQueryRequest $request La solicitud HTTP con los
     * parámetros de filtro y paginación.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * Una lista paginada de usuarios que cumplen con los filtros especificados.
     *
     * @throws \Exception
     * Devuelve una respuesta de error en caso de excepción.
     */
    public function indexQuery(UserIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->start_date)->startOfDay();
            $end_date = Carbon::parse($request->end_date)->endOfDay();
            //Consulta por nombre
            $users = User::with('roles', 'permissions')
                ->when($request->filled('search'),
                    function ($query) use ($request) {
                        $query->search($request->search);
                    }
                )
                ->when($request->filled('start_date') && $request->filled('end_date'),
                    function ($query) use ($start_date, $end_date) {
                        $query->filterByDate($start_date, $end_date);
                    }
                )
                ->when($request->filled('role'),
                    function ($query) use ($request) {
                        $query->filterByRole($request->role);
                    }
                )
                ->orderBy($request->column, $request->dir)
                ->paginate($request->perPage);

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
            return back()->withErrors(
                [
                    'Ocurrió un error al cargar la vista: ' . $e->getMessage()
                ]
            );
        }
    }

    /**
     * Listado de usuarios inactivos con filtros y paginación.
     *
     * Esta función devuelve un listado paginado de usuarios inactivos aplicando los siguientes filtros:
     *
     * - Filtro por rango de fechas de creación (start_date y end_date).
     * - Filtro por nombre de usuario, apellido, número de documento o correo electrónico (search).
     *
     * Si los parámetros de filtro están presentes en la solicitud, se aplicarán a la consulta de usuarios inactivos.
     *
     * @param \App\Http\Requests\UserInactivesRequest $request La solicitud HTTP con los parámetros
     * de filtro y paginación.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * Lista paginada de usuarios inactivos que cumplen con los filtros especificados.
     */
    public function inactivesQuery(UserInactivesQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->start_date)->startOfDay();
            $end_date = Carbon::parse($request->end_date)->endOfDay();

            $users = User::with('roles','permissions')
                //Consulta por nombre, apellido, cedula o correo
                ->when($request->filled('search'),
                    function ($query) use ($request) {
                        $query->search($request->search);
                    }
                )
                ->when($request->filled('start_date') && $request->filled('end_date'),
                    function ($query) use ($start_date, $end_date) {
                        $query->filterByDate($start_date, $end_date);
                    }
                )
                ->orderBy($request->column, $request->dir)
                ->onlyTrashed() //Trae los registros 'eliminados'
                ->paginate($request->perPage);

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

    /**
     * Crear un nuevo usuario.
     *
     * Esta función crea un nuevo usuario con los datos proporcionados en la solicitud.
     *
     * @param \App\Http\Requests\UserStoreRequest $request La solicitud HTTP con los datos del nuevo usuario.
     *
     * @return \Illuminate\Http\JsonResponse
     * Una respuesta JSON que incluye el usuario creado y un mensaje de éxito.
     *
     * @throws Exception
     * Devuelve una respuesta de error en caso de excepción.
     */
    public function store(UserStoreRequest $request)
    {
        try {
            $user = new User();
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->document_number = $request->document_number;
            $user->phone_number = $request->phone_number;
            $user->address = $request->address;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->enterprise_id = Auth::user()->enterprise_id;
            $user->save();

            return $this->successResponse(
                $user,
                'Usuario creado exitosamente.',
                201
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

    /**
     * Actualizar un usuario existente.
     *
     * Esta función actualiza los datos de un usuario existente con los datos proporcionados en la solicitud.
     *
     * @param \App\Http\Requests\UserUpdateRequest $request La solicitud HTTP con los datos de
     * actualización del usuario.
     * @param int $id El ID del usuario que se va a actualizar.
     *
     * @return \Illuminate\Http\JsonResponse
     * Una respuesta JSON que incluye el usuario actualizado y un mensaje de éxito.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * Devuelve una respuesta de error si el usuario no se encuentra.
     * @throws Exception
     * Devuelve una respuesta de error en caso de excepción.
     */
    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->document_number = $request->document_number;
            $user->phone_number = $request->phone_number;
            $user->address = $request->address;
            $user->email = $request->email;
            $user->enterprise_id = Auth::user()->enterprise_id;
            $user->save();
            return $this->successResponse(
                $user,
                'Registro actualizado exitosamente',
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

    public function password(UserPasswordRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->password = Hash::make($request->password);
            $user->save();
            return $this->successResponse(
                $user,
                'Contraseña del usuario actualizada exitosamente',
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

    /**
     * Eliminar un usuario existente por su ID.
     *
     * Esta función elimina un usuario existente según el ID proporcionado en la solicitud.
     *
     * @param \App\Http\Requests\UserDeleteRequest $request La solicitud HTTP con el ID del usuario a eliminar.
     *
     * @return \Illuminate\Http\JsonResponse
     * Una respuesta JSON que indica que el usuario se ha eliminado exitosamente (sin datos) y un mensaje de éxito.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * Devuelve una respuesta de error si el usuario no se encuentra.
     * @throws Exception
     * Devuelve una respuesta de error en caso de excepción.
     */
    public function delete(UserDeleteRequest $request)
    {
        try {
            $user = User::findOrFail($request->id)->delete();
            return $this->successResponse(
                $user,
                'Usuario eliminado exitosamente.',
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

    /**
     * Restaurar un usuario inactivo por su ID.
     *
     * Esta función restaura un usuario inactivo según el ID proporcionado en la solicitud.
     *
     * @param \App\Http\Requests\UserRestoreRequest $request La solicitud HTTP con el ID del usuario a restaurar.
     *
     * @return \Illuminate\Http\JsonResponse
     * Una respuesta JSON que indica que el usuario se ha restaurado exitosamente y un mensaje de éxito.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * Devuelve una respuesta de error si el usuario no se encuentra.
     * @throws Exception
     * Devuelve una respuesta de error en caso de excepción.
     */
    public function restore(UserRestoreRequest $request)
    {
        try {
            $user = User::withTrashed()->findOrFail($request->id)->restore();
            return $this->successResponse(
                $user,
                'Usuario restaurado exitosamente',
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

    /**
     * Asignar un rol y permisos a un usuario existente.
     *
     * Esta función asigna un rol y permisos a un usuario existente según los datos proporcionados en la solicitud.
     *
     * @param \App\Http\Requests\AssignRoleAndPermissionsRequest $request La solicitud HTTP con los datos de
     * asignación de rol y permisos.
     *
     * @return \Illuminate\Http\JsonResponse
     * Una respuesta JSON que indica que el rol y los permisos se han asignado exitosamente al usuario y un
     * mensaje de éxito.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * Devuelve una respuesta de error si el usuario o el rol no se encuentran.
     * @throws \Illuminate\Database\QueryException
     * Devuelve una respuesta de error si ocurre una excepción de consulta de base de datos.
     * @throws Exception
     * Devuelve una respuesta de error en caso de otras excepciones.
     */
    public function assignRoleAndPermissions(AssignRoleAndPermissionsRequest $request)
    {
        try {
            // Obtener el rol existente
            $role = Role::where('name', '=', $request->role)->firstOrFail();
            $user = User::findOrFail($request->id);
            if (!$role) {
                return $this->successResponse(
                    $user,
                    'El rol especificado no existe',
                    404
                );
            }
            // Verificar si el usuario ya tiene el rol
            if ($user->hasRole($role->name)) {
                return $this->successResponse(
                    $user,
                    'El usuario ya tiene el rol asignado',
                    400
                );
            }
             // Asignar el rol al usuario
            $user->assignRole([$role]);
            // Asociar los permisos existentes del rol al usuario
            $user->givePermissionTo($request->permissions);
            return $this->successResponse(
                $user,
                'Rol y permisos asignados al usuario exitosamente.',
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

    /**
     * Remover todos los roles y permisos de un usuario.
     *
     * Esta función remueve todos los roles y permisos asociados a un usuario según el ID proporcionado en la solicitud.
     *
     * @param \App\Http\Requests\RemoveRolesAndPermissionsRequest $request La solicitud HTTP con el ID del
     * usuario cuyos roles y permisos se deben remover.
     *
     * @return \Illuminate\Http\JsonResponse
     * Una respuesta JSON que indica que los roles y permisos se han removido exitosamente del
     * usuario y un mensaje de éxito.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * Devuelve una respuesta de error si el usuario no se encuentra.
     * @throws Exception
     * Devuelve una respuesta de error en caso de excepción.
     */
    public function removeRoleAndPermissions(RemoveRolesAndPermissionsRequest $request)
    {
        try {
            $user = User::findOrFail($request->id);
            
            // Remover los permisos del usuario
            $user->revokePermissionTo($request->permissions);

            // Remover el rol del usuario
            $user->removeRole($request->role);

            return $this->successResponse(
                $user,
                'Rol y permisos removidos al usuario exitosamente.',
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
