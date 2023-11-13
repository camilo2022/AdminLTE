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
            'category_id' => ['required', 'exists:roles,id'],
            'subcategory_id' => ['required', 'array'],
            'subcategory_id.*' => ['required', 'exists:categories,id'], // Asegura que los id de permisos existan en la tabla permissions
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'El campo identificador del rol es requerido.',
            'category_id.integer' => 'El identificador del rol debe ser un número entero.',
            'subcategory_id.required' => 'El campo identificadores de las subcategorias es requerido.',
            'subcategory_id.array' => 'El campo identificadores de las subcategorias debe ser un arreglo.',
            'subcategory_id.*.required' => 'El identificador de la subcategoria es requerido.',
            'subcategory_id.*.exists' => 'El identificador de la subcategoria no existe en la base de datos.',
        ];
    }
}
