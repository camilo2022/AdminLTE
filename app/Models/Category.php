<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Category extends Model implements Auditable
{
    use HasFactory, SoftDeletes, AuditableModel;

    protected $table = 'categories';
    protected $fillable = [
        'clothing_line_id',
        'name',
        'code',
        'description'
    ];

    protected $auditInclude = [
        'clothing_line_id',
        'name',
        'code',
        'description'
    ];

    public function subcategories() : HasMany
    {
        return $this->hasMany(Subcategory::class, 'category_id');
    }

    public function clothing_line() : BelongsTo
    {
        return $this->belongsTo(ClothingLine::class, 'clothing_line_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('id', 'like', '%' . $search . '%')
        ->orWhere('name', 'like', '%' . $search . '%')
        ->orWhere('code', 'like', '%' . $search . '%')
        ->orWhere('description', 'like', '%' . $search . '%')
        ->orWhereHas('subcategories',
            function ($subQuerySubcategories) use ($search) {
                $subQuerySubcategories->where('id', 'like',  '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%')
                ->orWhere('code', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
            }
        )
        ->orWhereHas('clothing_line',
            function ($subQueryClothingLine) use ($search) {
                $subQueryClothingLine->where('id', 'like',  '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%')
                ->orWhere('code', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
            }
        );
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
