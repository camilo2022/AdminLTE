<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where(
            function ($subQuery) use ($search) {
                // Busco en la base de datos por coincidencias en "name", "last_name" e "email" y
                // por valores exactos en "document_number"
                $subQuery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('document_number', '=', $search)
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhereHas('roles',
                        function ($subRoleQuery) use ($search) {
                            $subRoleQuery->where('name', 'like',  '%' . $search . '%');
                        }
                    );
            }
        );
    }
    
    public function scopeFilterByRole($query, $request)
    {
        if (is_array($request->role)) {
            // Filtrar por roles en el arreglo
            return $this->filterByArrayRole($query, $request);
        } elseif (is_string($request->role)) {
            // Filtrar por un rol específico
            return $this->filterByStringRole($query, $request);
        } elseif (is_numeric($request->role)) {
            // Filtrar por id del rol
            return $this->filterByNumericRole($query, $request);
        }
    }
    
    protected function filterByArrayRole($query, $request)
    {
        return $query->whereHas('roles',
            // Busca solo los roles que estan en el array
            function ($roleQuery) use ($request) {
                $roleQuery->whereIn('name', $request->role);
            }
        );
    }

    public function filterByStringRole($query, $request)
    {
        return $query->whereHas('roles',
            // Busca el rol que esta en la variable
            function ($roleQuery) use ($request) {
                $roleQuery->where('name', '=', $request->role);
            }
        );
    }

    protected function filterByNumericRole($query, $request)
    {
        return $query->whereHas('roles',
            // Busca solo los roles que estan en el array
            function ($roleQuery) use ($request) {
                $roleQuery->where('id', 'like', '%' . $request->role . '%' );
            }
        );
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }

}
