<?php

namespace App\Http\Requests\CategoriesAndSubcategories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoriesAndSubcategoriesRestoreRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
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
            'id' => ['required', 'exists:categories,id']
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El Identificador de la categoria es requerido.',
            'id.integer' => 'El Identificador de la categoria no es valido.'
        ];
    }
}
