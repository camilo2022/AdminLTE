<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;
    protected $table = 'clients';

    protected $fillable = [
        'name',
        'document_number',
        'telephone_number',
        'email',
        'quota'
    ];

    public function client_branches() : HasMany
    {
        return $this->hasMany(ClientBranch::class, 'client_id');
    }
}
