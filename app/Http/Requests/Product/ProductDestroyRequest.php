<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductDestroyRequest extends FormRequest
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
            'id' => ['required', 'exists:product_photos,id'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El Identificador de la foto del producto es requerido.',
            'id.exists' => 'El Identificador de la foto del producto no es válido.',
        ];
    }
}
