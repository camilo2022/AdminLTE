<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class SpatieCheckPermission
{
    use ApiResponser;

    private $errorAuthorizationException = 'No está autorizado para realizar esta acción.';
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
            if($request->ajax()) {
                return $this->errorResponse(
                    [
                        'message' => $this->errorAuthorizationException
                    ],
                    403
                );
            }
            throw new AuthorizationException();
        }

        return $next($request);
    }
}
