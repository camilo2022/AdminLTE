<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Subcategory extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Auditing;

    protected $table = 'subcategories';
    protected $fillable = [
        'category_id',
        'name',
        'code',
        'description'
    ];

    protected $auditInclude = [
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
