<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategory extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'subcategories';

    protected $fillable = [
        'category_id',
        'name',
        'code',
        'description'
    ];

    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
