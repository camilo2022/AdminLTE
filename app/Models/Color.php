<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Color extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use AuditableModel;

    protected $table = 'colors';
    protected $fillable = [
        'name',
        'code',
        'value',
    ];

    protected $auditInclude = [
        'name',
        'code',
        'value'
    ];

    public function products() : BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_color_tone', 'color_id', 'product_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
        ->orWhere('code', 'like', '%' . $search . '%')
        ->orWhere('value', 'like', '%' . $search . '%');
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
