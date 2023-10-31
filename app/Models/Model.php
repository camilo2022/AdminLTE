<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as DBModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Model extends DBModel
{
    use HasFactory;
    protected $table = 'models';

    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    public function products() : HasMany
    {
        return $this->hasMany(Product::class, 'model_id');
    }
}
