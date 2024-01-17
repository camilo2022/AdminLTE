<?php

namespace App\Http\Requests\ReturnType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReturnTypeRestoreRequest extends FormRequest
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
            'id' => ['required', 'exists:return_types,id'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El Identificador del tipo de devolucion es requerido.',
            'id.exists' => 'El Identificador del tipo de devolucion no es válido.',
        ];
    }
}
