<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\CustomPasswordResetNotification;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes, CanResetPasswordTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'document_number',
        'phone_number',
        'address',
        'email',
        'password',
        'enterprise_id',
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

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPasswordResetNotification($token));
    }

    public function scopeSearch($query, $search)
    {
        if (is_numeric($search)) {
            // Filtrar por campos numericos
            return $this->scopeSearchByNumeric($query, $search);
        } elseif (is_string($search)) {
            // Filtrar por campos de texto
            return $this->scopeSearchByString($query, $search);
        }
    }

    public function scopeSearchByString($query, $search)
    {
        return $query->where(
            function ($stringQuery) use ($search) {
                $stringQuery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            }
        );
    }

    public function scopeSearchByNumeric($query, $search)
    {
        return $query->where(
            function ($numericQuery) use ($search) {
                $numericQuery->where('id', 'like', '%' . $search . '%')
                    ->orWhere('document_number', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%');
            }
        );
    }

    public function scopeFilterByRole($query, $role)
    {
        if (is_array($role)) {
            // Filtrar por roles en el arreglo
            return $this->filterByArrayRole($query, $role);
        } elseif (is_string($role)) {
            // Filtrar por un rol específico
            return $this->filterByStringRole($query, $role);
        } elseif (is_numeric($role)) {
            // Filtrar por id del rol
            return $this->filterByNumericRole($query, $role);
        }
    }

    protected function filterByArrayRole($query, $role)
    {
        return $query->whereHas('roles',
            // Busca solo los roles que estan en el array
            function ($roleArrayQuery) use ($role) {
                $roleArrayQuery->whereIn('name', $role);
            }
        );
    }

    public function filterByStringRole($query, $role)
    {
        return $query->whereHas('roles',
            // Busca el rol que esta en la variable
            function ($roleStringQuery) use ($role) {
                $roleStringQuery->where('name', '=', $role);
            }
        );
    }

    protected function filterByNumericRole($query, $role)
    {
        return $query->whereHas('roles',
            // Busca solo los roles que estan en el array
            function ($roleNumericQuery) use ($role) {
                $roleNumericQuery->where('id', 'like', '%' . $role . '%' );
            }
        );
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
