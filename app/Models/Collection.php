<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'clothing_lines';

    protected $fillable = [
        'name',
        'code',
        'start_date',
        'end_date',
        'active_status'
    ];

    public function orders() : HasMany
    {
        return $this->hasMany(Order::class, 'collection_id');
    }
}
