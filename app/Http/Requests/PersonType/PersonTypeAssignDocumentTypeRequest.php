<?php

namespace App\Http\Requests\PersonType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonTypeAssignDocumentTypeRequest extends FormRequest
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
            'person_type_id' => ['required', 'exists:person_types,id'],
            'document_type_id' => ['required', 'exists:document_types,id'],
        ];
    }

    public function messages()
    {
        return [
            'person_type_id.required' => 'El Identificador del tipo de persona es requerido.',
            'person_type_id.exists' => 'El Identificador del tipo de persona no es valido.',
            'document_type_id.required' => 'El Identificador del tipo de documento es requerido.',
            'document_type_id.exists' => 'El Identificador del tipo de documento no es valido.',
        ];
    }
}
