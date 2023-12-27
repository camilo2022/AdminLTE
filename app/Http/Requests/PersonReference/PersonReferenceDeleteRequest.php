<?php

namespace App\Http\Requests\PersonReference;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonReferenceDeleteRequest extends FormRequest
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
            'id' => ['required', 'exists:people_references,id'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El Identificador de la referencia personal es requerido.',
            'id.exists' => 'El Identificador de la referencia personal no es válido.',
        ];
    }
}
