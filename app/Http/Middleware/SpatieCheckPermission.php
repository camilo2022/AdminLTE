<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class SpatieCheckPermission
{
    use ApiResponser;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!auth()->user()->hasDirectPermission($permission)) {
            $message = "No está autorizado para realizar esta acción. Falta el permiso: $permission.
            Contacte al administrador para obtener asistencia o solicitar autorización.";
            if($request->ajax()) {
                return $this->errorResponse(
                    [
                        'message' => $message
                    ],
                    403
                );
            }
            return back()->with(
                'danger',
                $message
            );
            throw new AuthorizationException();
        }

        return $next($request);
    }
}
