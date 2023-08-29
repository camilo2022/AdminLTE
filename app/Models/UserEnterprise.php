<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEnterprise extends Model
{
    protected $table = 'user_enterprises';

    protected $fillable = [
        'user_id', 
        'enterprises_id'
    ];
}
