<?php

namespace App\Http\Requests\Model;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ModelRestoreRequest extends FormRequest
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
            'id' => ['required', 'exists:models,id'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El Identificador del modelo de producto es requerido.',
            'id.exists' => 'El Identificador del modelo de producto no es válido.'
        ];
    }
}
