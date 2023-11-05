<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class DateNotBetween implements Rule
{
    protected $table;
    protected $startField;
    protected $endField;
    protected $value;
    protected $excludedId;

    public function __construct($table, $startField, $endField, $value, $excludedId = null)
    {
        $this->table = $table;
        $this->startField = $startField;
        $this->endField = $endField;
        $this->value = $value;
        if ($excludedId) {
            $this->excludedId = $excludedId;
        }
    }

    public function passes($attribute, $value)
    {
        // Realiza una consulta para verificar si existen registros en la tabla especificada
        // donde el valor de $value no está entre $startField y $endField.
        $count = DB::table($this->table)
            ->when(!is_null($this->excludedId),
                function ($query) {
                    $query->where('id', '!=', $this->excludedId);
                }
            )
            ->where($this->startField, '<=', Carbon::parse($value)->startOfDay())
            ->where($this->endField, '>=', Carbon::parse($value)->startOfDay())
            ->count();

        return $count === 0;
    }

    public function message()
    {
        return 'La :attribute ya está en el rango de fechas de otra correria.';
    }
}


