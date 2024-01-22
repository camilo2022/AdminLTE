<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class File extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;

    protected $table = 'files';
    protected $fillable = [
        'model_type',
        'model_id',
        'name',
        'path',
        'type',
        'size',
        'user_id'
    ];

    protected $auditInclude = [
        'model_type',
        'model_id',
        'name',
        'path',
        'type',
        'size',
        'user_id'
    ];

    public function model() : MorphTo
    {
        return $this->morphTo();
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
