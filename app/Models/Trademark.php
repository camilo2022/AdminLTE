<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trademark extends Model
{
    use HasFactory;
    protected $table = 'trademarks';

    protected $fillable = [
        'name',
        'description',
        'route_logo'
    ];
}
