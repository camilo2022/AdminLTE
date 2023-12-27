<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as DBModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Product extends DBModel implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use AuditableModel;

    protected $table = 'products';
    protected $fillable = [
        'code',
        'price',
        'cost',
        'clothing_line_id',
        'category_id',
        'subcategory_id',
        'model_id',
        'trademark_id',
        'correria_id',
        'collection_id'
    ];

    protected $auditInclude = [
        'code',
        'price',
        'cost',
        'clothing_line_id',
        'category_id',
        'subcategory_id',
        'model_id',
        'trademark_id',
        'correria_id',
        'collection_id'
    ];

    public function inventories() : HasMany
    {
        return $this->hasMany(Inventory::class, 'product_id');
    }

    public function photos() : HasMany
    {
        return $this->hasMany(ProductPhoto::class, 'product_id');
    }

    public function colors_tones() : HasMany
    {
        return $this->hasMany(ProductColorTone::class, 'product_id');
    }

    public function sizes() : BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'product_sizes', 'product_id', 'size_id')
            ->withTimestamps()
            ->using(ProductSize::class)
            ->wherePivot('deleted_at', null);
    }

    public function clothing_line() : BelongsTo
    {
        return $this->belongsTo(ClothingLine::class, 'clothing_line_id');
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory() : BelongsTo
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    public function model() : BelongsTo
    {
        return $this->belongsTo(Model::class, 'model_id');
    }

    public function trademark() : BelongsTo
    {
        return $this->belongsTo(Trademark::class, 'trademark_id');
    }

    public function correria() : BelongsTo
    {
        return $this->belongsTo(Correria::class, 'correria_id');
    }

    public function collection() : BelongsTo
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('code', 'like', '%' . $search . '%')
        ->orWhere('price', 'like', '%' . $search . '%')
        ->orWhere('cost', 'like', '%' . $search . '%')
        ->orWhereHas('clothing_line',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'like',  '%' . $search . '%');
            }
        )
        ->orWhereHas('category',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'like',  '%' . $search . '%');
            }
        )
        ->orWhereHas('subcategory',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'like',  '%' . $search . '%');
            }
        )
        ->orWhereHas('model',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'like',  '%' . $search . '%');
            }
        )
        ->orWhereHas('trademark',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'like',  '%' . $search . '%');
            }
        )
        ->orWhereHas('correria',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'like',  '%' . $search . '%');
            }
        )
        ->orWhereHas('collection',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'like',  '%' . $search . '%');
            }
        );
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
