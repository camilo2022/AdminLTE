<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class SubcategoryExistsForCategory implements Rule
{
    protected $table;
    protected $camp;
    protected $column_camp;
    protected $camp_compared;
    protected $column_camp_compared;

    public function __construct($table, $camp, $column_camp, $camp_compared, $column_camp_compared)
    {
        $this->table = $table;
        $this->camp = $camp;
        $this->column_camp = $column_camp;
        $this->camp_compared = $camp_compared;
        $this->column_camp_compared = $column_camp_compared;
    }

    public function passes($attribute, $value)
    {
        return DB::table($this->table)
            ->where($this->column_camp, $this->camp)
            ->where($this->column_camp_compared, $this->camp_compared)
            ->exists();
    }

    public function message()
    {
        return "La subcategoria no pertenece a la categoria.";
    }
}
