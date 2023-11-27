<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as DBModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends DBModel
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'products';

    protected $fillable = [
        'code',
        'description',
        'clothing_line_id',
        'category_id',
        'subcategory_id',
        'model_id',
        'trademark_id',
        'collection_id',
        'price'
    ];

    public function product_inventories() : HasMany
    {
        return $this->hasMany(Inventory::class, 'product_id');
    }

    public function product_history_prices() : HasMany
    {
        return $this->hasMany(ProductHistoryPrice::class, 'product_id');
    }

    public function product_photos() : HasMany
    {
        return $this->hasMany(ProductPhoto::class, 'product_id');
    }

    public function product_order_details() : HasMany
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }

    public function colors() : BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'product_has_colors', 'product_id', 'color_id')
            ->withTimestamps()
            ->using(ProductHasColor::class)
            ->wherePivot('deleted_at', null);
    }

    public function sizes() : BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'product_has_sizes', 'product_id', 'size_id')
            ->withTimestamps()
            ->using(ProductHasSize::class)
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

    public function collection() : BelongsTo
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('code', 'like', '%' . $search . '%')
        ->orWhere('description', 'like', '%' . $search . '%')
        ->orWhere('price', 'like', '%' . $search . '%')
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
