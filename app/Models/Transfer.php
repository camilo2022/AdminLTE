<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transfer extends Model
{
    use HasFactory;
    protected $table = 'transfers';

    protected $fillable = [
        'consecutive',
        'from_user_id',
        'form_date',
        'from_observation',
        'to_user_id',
        'to_time',
        'to_observation',
        'status'
    ];

    public function details() : HasMany
    {
        return $this->hasMany(TransferDetail::class, 'transfer_id');
    }

    public function send_user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'send_user_id');
    }

    public function receive_user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'receive_user_id');
    }
}
