<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class User extends Authenticatable implements Auditable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes, CanResetPasswordTrait, Auditing;

    protected $table = 'users';
    protected $fillable = [
        'name',
        'last_name',
        'document_number',
        'phone_number',
        'address',
        'area_id',
        'charge_id',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $auditInclude = [
        'name',
        'last_name',
        'document_number',
        'phone_number',
        'address',
        'area_id',
        'charge_id',
        'email',
        'password'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function area() : BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function charge() : BelongsTo
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }

    public function warehouses() : BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_users', 'user_id', 'warehouse_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('id', 'LIKE', '%' . $search . '%')
        ->orWhere('name', 'LIKE', '%' . $search . '%')
        ->orWhere('last_name', 'LIKE', '%' . $search . '%')
        ->orWhere('address', 'LIKE', '%' . $search . '%')
        ->orWhere('email', 'LIKE', '%' . $search . '%')
        ->orWhereHas('area',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'LIKE',  '%' . $search . '%')
                ->orWhere('name', 'LIKE',  '%' . $search . '%')
                ->orWhere('description', 'LIKE',  '%' . $search . '%');
            }
        )
        ->orWhereHas('charge',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'LIKE',  '%' . $search . '%')
                ->orWhere('name', 'LIKE',  '%' . $search . '%')
                ->orWhere('description', 'LIKE',  '%' . $search . '%');
            }
        )
        ->orWhere('document_number', 'LIKE', '%' . $search . '%')
        ->orWhere('phone_number', 'LIKE', '%' . $search . '%');
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
