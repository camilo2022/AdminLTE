<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Permission;

class Submodule extends Model
{
    use HasFactory;
    protected $table = 'submodules';

    protected $fillable = [
        'name',
        'type',
        'url',
        'icon',
        'module_id',
        'permission_id'
    ];

    public function module() : BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function permission() : BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
