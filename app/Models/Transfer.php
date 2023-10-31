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
        'send_user_id',
        'send_time',
        'receive_user_id',
        'receive_time',
        'transfer_status'
    ];

    public function transfer_details() : HasMany
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
