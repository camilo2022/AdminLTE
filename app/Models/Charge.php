<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'charges';

    protected $fillable = [
        'area_id',
        'name',
        'description'
    ];

    public function area() : BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
