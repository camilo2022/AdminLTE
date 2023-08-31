<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{

    protected $table = 'enterprises';

    protected $fillable = [
        'name'
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'enterprise_id');
    }
}
