<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function failedValidation(Validator $validator)
    {
        // Lanzar una excepción de validación con los errores de validación obtenidos
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación.',
            'errors' => $validator->errors()
        ], 422));
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
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
            'clothing_line_id.exists' => 'La linea del producto proporcionado no existe en la base de datos.',
            'category_id.exists' => 'La categoria del producto proporcionado no existe en la base de datos.',
        ];
    }
}
