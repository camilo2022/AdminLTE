<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
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
        return $query->where('name', 'like', '%' . $search . '%')
        ->orWhere('last_name', 'like', '%' . $search . '%')
        ->orWhere('address', 'like', '%' . $search . '%')
        ->orWhere('email', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%')
        ->orWhere('document_number', 'like', '%' . $search . '%')
        ->orWhere('phone_number', 'like', '%' . $search . '%');
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
