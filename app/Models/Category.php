<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';

    protected $fillable = [
        'clothing_line_id',
        'name',
        'code',
        'description'
    ];

    public function subcategory() : HasMany
    {
        return $this->hasMany(ClothingLine::class, 'subcategory_id');
    }

    public function clothing_line() : BelongsTo
    {
        return $this->belongsTo(ClothingLine::class, 'clothing_line_id');
    }
}
