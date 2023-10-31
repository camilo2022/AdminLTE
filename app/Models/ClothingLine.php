<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClothingLine extends Model
{
    use HasFactory;
    protected $table = 'clothing_lines';

    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    public function categories() : HasMany
    {
        return $this->hasMany(Category::class, 'clothing_line_id');
    }
}
