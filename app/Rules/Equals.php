<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class Equals implements Rule
{
    protected $value;
    protected $value_compared;
    protected $name_value_compared;

    public function __construct($value, $value_compared, $name_value_compared)
    {
        $this->value = $value;
        $this->value_compared = $value_compared;
        $this->name_value_compared = $name_value_compared;
    }

    public function passes($attribute, $value)
    {
        return $value === $this->value_compared;
    }

    public function message()
    {
        return "El campo :attribute no coincide con el campo $this->name_value_compared.";
    }
}
