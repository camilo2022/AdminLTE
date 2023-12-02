<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Transfer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'transfers';
    protected $fillable = [
        'consecutive',
        'from_user_id',
        'form_date',
        'from_observation',
        'to_user_id',
        'to_date',
        'to_observation',
        'status'
    ];

    public function details() : HasMany
    {
        return $this->hasMany(TransferDetail::class, 'transfer_id');
    }

    public function from_user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function to_user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('consecutive', 'like', '%' . $search . '%')
        ->orWhereHas('from_user',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'like',  '%' . $search . '%')
                ->orWhere('name', 'like',  '%' . $search . '%')
                ->orWhere('last_name', 'like',  '%' . $search . '%');
            }
        )
        ->orWhere('form_date', 'like', '%' . $search . '%')
        ->orWhere('from_observation', 'like', '%' . $search . '%')
        ->orWhereHas('to_user',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'like',  '%' . $search . '%')
                ->orWhere('name', 'like',  '%' . $search . '%')
                ->orWhere('description', 'like',  '%' . $search . '%');
            }
        )
        ->orWhere('to_date', 'like', '%' . $search . '%')
        ->orWhere('to_observation', 'like', '%' . $search . '%')
        ->orWhere('status', 'like', '%' . $search . '%');
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }

    public function scopeTransfersByAssingWarehouse($query)
    {
        return $query->whereHas('from_user.warehouses',
            function ($subQuery) {
                $subQuery->where('user_id', '=', Auth::user()->id);
            }
        )
        ->orWhereHas('to_user.warehouses',
            function ($subQuery) {
                $subQuery->where('user_id', '=', Auth::user()->id);
            }
        );
    }
}
