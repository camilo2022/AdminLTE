<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Transporter extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Auditing;

    protected $table = 'transporters';
    protected $fillable = [
        'name',
        'document_number',
        'telephone_number',
        'email'
    ];

    protected $auditInclude = [
        'name',
        'document_number',
        'telephone_number',
        'email'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', '%' . $search . '%')
        ->orWhere('document_number', 'LIKE', '%' . $search . '%')
        ->orWhere('telephone_number', 'LIKE', '%' . $search . '%')
        ->orWhere('email', 'LIKE', '%' . $search . '%');
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
