<?php

namespace App\Http\Requests\PersonReference;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonReferenceIndexRequest extends FormRequest
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
            'person_id' => ['required', 'exists:people,id'],
        ];
    }

    public function messages()
    {
        return [
            'person_id.required' => 'El Identificador de la persona es requerido.',
            'person_id.exists' => 'El Identificador de la persona no es válido.',
        ];
    }
}
