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

    public function __construct($table, $column_camp, $column_camp_compared)
    {
        $this->table = $table;
        $this->column_camp = $column_camp;
        $this->column_camp_compared = $column_camp_compared;
    }

    public function passes($attribute, $value)
    {
        $subcategoryId = $value; 
        $categoryId = request()->input('category_id'); 
        
        // Armamos consulta con valores reales
        return DB::table($this->table)
            ->where($this->column_camp, $categoryId) 
            ->where($this->column_camp_compared, $subcategoryId)
            ->exists();
    }

    public function message()
    {
        return "La subcategoria no pertenece a la categoria.";
    }
}
