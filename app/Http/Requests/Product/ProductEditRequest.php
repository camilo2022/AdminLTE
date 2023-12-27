<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductEditRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        // Lanzar una excepción de validación con los errores de validación obtenidos
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación.',
            'errors' => $validator->errors()
        ], 422));
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'clothing_line_id' => ['nullable', 'exists:clothing_lines,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ];
    }

    public function messages()
    {
        return [
            'clothing_line_id.exists' => 'El Identificador de la linea del producto no es valido.',
            'category_id.exists' => 'El Identificador de la categoria del producto no es valido.',
        ];
    }
}
