<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseHasUser extends Model
{
    use HasFactory;
    protected $table = 'warehouse_has_users';

    protected $fillable = [
        'warehouse_id',
        'user_id'
    ];
}
