<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as DBModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends DBModel
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = [
        'code',
        'description',
        'clothing_line_id',
        'category_id',
        'subcategory_id',
        'model_id',
        'route_photo',
        'price',
        'inventory_status',
        'collection_id'
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
        return $this->belongsToMany(Color::class, 'product_has_colors', 'product_id', 'color_id');
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
}
