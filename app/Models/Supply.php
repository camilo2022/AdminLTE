<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Supply extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Auditing;

    protected $table = 'supplies';
    protected $fillable = [
        'supplier_id',
        'supply_type_id',
        'cloth_type_id',
        'cloth_composition_id',
        'name',
        'code',
        'description',
        'quantity',
        'quality',
        'width',
        'length',
        'measurement_unit_id',
        'color_id',
        'trademark_id',
        'price_with_vat',
        'price_without_vat'
    ];

    protected $auditInclude = [
        'supplier_id',
        'supply_type_id',
        'cloth_type_id',
        'cloth_composition_id',
        'name',
        'code',
        'description',
        'quantity',
        'quality',
        'width',
        'length',
        'measurement_unit_id',
        'color_id',
        'trademark_id',
        'price_with_vat',
        'price_without_vat'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function files() : MorphMany
    {
      return $this->morphMany(File::class, 'model');
    }

    public function supplier() : BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function supply_type() : BelongsTo
    {
        return $this->belongsTo(SupplyType::class, 'supply_type_id');
    }

    public function cloth_type() : BelongsTo
    {
        return $this->belongsTo(ClothType::class, 'cloth_type_id');
    }

    public function cloth_composition() : BelongsTo
    {
        return $this->belongsTo(ClothComposition::class, 'cloth_composition_id');
    }

    public function measurement_unit() : BelongsTo
    {
        return $this->belongsTo(MeasurementUnit::class, 'measurement_unit_id');
    }

    public function color() : BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function trademark() : BelongsTo
    {
        return $this->belongsTo(Trademark::class, 'trademark_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('id', 'LIKE', '%' . $search . '%')
            ->orWhere('name', 'LIKE', '%' . $search . '%')
            ->orWhere('code', 'LIKE', '%' . $search . '%');
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
