<?php

namespace App\Http\Requests\CategoriesAndSubcategories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoriesAndSubcategoriesDeleteRequest extends FormRequest
{
    /**
     * Maneja una solicitud fallida de validación.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Validation\ValidationException
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
            'id' => ['required', 'exists:categories,id']
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'El campo identificador de la categoria es requerido.',
            'category_id.integer' => 'El identificador de la categoria debe ser un número entero.'
        ];
    }
}
