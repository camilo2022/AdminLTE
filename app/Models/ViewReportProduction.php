<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ViewReportProduction extends Model
{
    use HasFactory;

    protected $view = 'view_report_productions';

    public function scopeSearch($query, $search)
    {
        $columns = Schema::getColumnListing('view_report_productions');

        $query->where(function($subQuery) use ($columns, $search){
            foreach($columns as $column){
                $subQuery->orWhere($column, 'like', '%' . $search . '%');
            }
        });

        return $query;
    }

    public function scopeFilterByColumnDinamic($query, $queryColumns)
    {
        $columns = Schema::getColumnListing('view_report_productions');

        foreach ($queryColumns as $column => $content) {
            if (in_array($column, $columns) && !is_null($content->value)) {
                $query = $this->operatorFilterDinamic($query, $column, $content->operator, $content->value);
            }
        }

        return $query;
    }

    private function operatorFilterDinamic($query, $column, $operator, $value)
    {
        if (is_numeric($value) || is_string($value)) {
            switch ($operator->query) {
                case '=':
                    if ($operator->logic == 'AND') {
                        $query->where($column, '=', $value);
                    } elseif ($operator->logic == 'OR') {
                        $query->orWhere($column, '=', $value);
                    }
                    break;
                case '!=':
                    if ($operator->logic == 'AND') {
                        $query->where($column, '!=', $value);
                    } elseif ($operator->logic == 'OR') {
                        $query->orWhere($column, '!=', $value);
                    }
                    break;
                case '%like':
                    if ($operator->logic == 'AND') {
                        $query->where($column, 'like', '%' . $value);
                    } elseif ($operator->logic == 'OR') {
                        $query->orWhere($column, 'like', '%' . $value);
                    }
                    break;
                case 'like%':
                    if ($operator->logic == 'AND') {
                        $query->where($column, '=', 'like', $value . '%');
                    } elseif ($operator->logic == 'OR') {
                        $query->orWhere($column, 'like', $value . '%');
                    }
                    break;
                case '%like%':
                    if ($operator->logic == 'AND') {
                        $query->where($column, 'like', '%' . $value . '%');
                    } elseif ($operator->logic == 'OR') {
                        $query->orWhere($column, 'like', '%' . $value . '%');
                    }
                    break;
                case 'null':
                    if ($operator->logic == 'AND') {
                        $query->whereNull($column);
                    } elseif ($operator->logic == 'OR') {
                        $query->orWhereNull($column);
                    }
                    break;
                case 'notNull':
                    if ($operator->logic == 'AND') {
                        $query->whereNotNull($column);
                    } elseif ($operator->logic == 'OR') {
                        $query->orWhereNotNull($column);
                    }
                    break;
            }
        }

        if (is_array($value)) {
            switch ($operator->query) {
                case 'whereIn':
                    $query->whereIn($column, $value);
                    break;
                case 'whereNotIn':
                    $query->whereNotIn($column, $value);
                    break;
            }
        }

        if (is_object($value)) {
            switch ($operator->query){
                case '=':
                    if ($operator->logic == 'AND') {
                        $query->whereBetween($column, [Carbon::parse($value->date)->startOfDay(), Carbon::parse($value->date)->endOfDay()]);
                    } elseif ($operator->logic == 'OR') {
                        $query->orWhereBetween($column, [Carbon::parse($value->date)->startOfDay(), Carbon::parse($value->date)->endOfDay()]);
                    }
                    break;
                case '!=':
                    if ($operator->logic == 'AND') {
                        $query->whereNotBetween($column, [Carbon::parse($value->date)->startOfDay(), Carbon::parse($value->date)->endOfDay()]);
                    } elseif ($operator->logic == 'OR') {
                        $query->orWhereNotBetween($column, [Carbon::parse($value->date)->startOfDay(), Carbon::parse($value->date)->endOfDay()]);
                    }
                    break;
                case '<':
                    if ($operator->logic == 'AND') {
                        $query->where($column, '<', Carbon::parse($value->date)->startOfDay());
                    } elseif ($operator->logic == 'OR') {
                        $query->orWhere($column, '<', Carbon::parse($value->date)->startOfDay());
                    }
                    break;
                case '<=':
                    if ($operator->logic == 'AND') {
                        $query->where($column, '<=', Carbon::parse($value->date)->endOfDay());
                    } elseif ($operator->logic == 'OR') {
                        $query->orWhere($column, '<=', Carbon::parse($value->date)->endOfDay());
                    }
                    break;
                case '>':
                    if ($operator->logic == 'AND') {
                        $query->where($column, '>', Carbon::parse($value->date)->endOfDay());
                    } elseif ($operator->logic == 'OR') {
                        $query->orWhere($column, '>', Carbon::parse($value->date)->endOfDay());
                    }
                    break;
                case '>=':
                    if ($operator->logic == 'AND') {
                        $query->where($column, '>=', Carbon::parse($value->date)->startOfDay());
                    } elseif ($operator->logic == 'OR') {
                        $query->orWhere($column, '>=', Carbon::parse($value->date)->startOfDay());
                    }
                    break;
                case 'whereBetweenYear':
                    if ($operator->logic == 'AND') {
                        $query->whereBetween($column, [Carbon::parse($value->start_date)->startOfYear(), Carbon::parse($value->end_date)->endOfYear()]);
                    } elseif ($operator->logic == 'OR') {
                        $query->orWhereBetween($column, [Carbon::parse($value->start_date)->startOfYear(), Carbon::parse($value->end_date)->endOfYear()]);
                    }
                    break;
                case 'whereBetweenMonth':
                    if ($operator->logic == 'AND') {
                        $query->whereBetween($column, [Carbon::parse($value->start_date)->startOfMonth(), Carbon::parse($value->end_date)->endOfMonth()]);
                    } elseif ($operator->logic == 'OR') {
                        $query->orWhereBetween($column, [Carbon::parse($value->start_date)->startOfMonth(), Carbon::parse($value->end_date)->endOfMonth()]);
                    }
                    break;
                case 'whereBetween':
                    if ($operator->logic == 'AND') {
                        $query->whereBetween($column, [Carbon::parse($value->start_date)->startOfDay(), Carbon::parse($value->end_date)->endOfDay()]);
                    } elseif ($operator->logic == 'OR') {
                        $query->orWhereBetween($column, [Carbon::parse($value->start_date)->startOfDay(), Carbon::parse($value->end_date)->endOfDay()]);
                    }
                    break;
            }
        }

        return $query;
    }
}
