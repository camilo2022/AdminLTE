<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewReportProduction extends Model
{
    use HasFactory;
    protected $view = 'view_report_productions';
    
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
        ->orWhere('last_name', 'like', '%' . $search . '%')
        ->orWhere('address', 'like', '%' . $search . '%')
        ->orWhere('email', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%')
        ->orWhere('document_number', 'like', '%' . $search . '%')
        ->orWhere('phone_number', 'like', '%' . $search . '%');
    }

    public function scopeFilterByBranch($query, $branch)
    {
        foreach ($branch->columns as $column => $value) {
            if (!is_null($value)) {
                $query = $this->operatorFilterDinamic($query, $column, $branch->operator, $value);
            }
        }

        return $query;
    }

    private function operatorFilterDinamic($query, $column, $operator, $value) 
    {
        if (is_string($value)) {
            switch ($operator) {
                case '=':
                    $query->where($column, '=', $value);
                    break;
                case '!=':
                    $query->where($column, '!=', $value);
                    break;
                case '%LIKE':
                    $query->where($column, 'LIKE', '%' . $value . '%');
                    break;
                case 'LIKE%':
                    $query->where($column, 'LIKE', $value . '%');
                    break;
                case '%LIKE%':
                    $query->where($column, 'LIKE', '%' . $value . '%');
                    break;
            }      
        } 
        
        if (is_array($value)) {
            switch ($operator) {
                case 'whereIn':
                    $query->whereIn($column, $value);
                    break;
                case 'whereNotIn':
                    $query->whereNotIn($column, $value);
                    break;
            }  
        }

        if (is_object($value)) {
            switch ($operator){
                case '=':
                    $query->whereBetween($column, [Carbon::parse($value->date)->startOfDay(), Carbon::parse($value->date)->endOfDay()]);
                    break;
                case '!=':
                    $query->whereNotBetween($column, [Carbon::parse($value->date)->startOfDay(), Carbon::parse($value->date)->endOfDay()]);
                    break;
                case '<':
                    $query->whereNotBetween($column, [Carbon::parse($value->date)->startOfDay()]);
                    break;
                case '>':
                    $query->whereNotBetween($column, [Carbon::parse($value->date)->endOfDay()]);
                    break;
                case 'whereBetweenYear':
                    $query->whereBetween($column, [Carbon::parse($value->start_date)->startOfYear(), Carbon::parse($value->end_date)->endOfYear()]);
                    break;
                case 'whereBetweenMonth':
                    $query->whereBetween($column, [Carbon::parse($value->start_date)->startOfMonth(), Carbon::parse($value->end_date)->endOfMonth()]);
                    break;
                case 'whereBetween':
                    $query->whereBetween($column, [Carbon::parse($value->start_date)->startOfDay(), Carbon::parse($value->end_date)->endOfDay()]);
                    break;
                
            }
        }

        return $query;
    }
}
