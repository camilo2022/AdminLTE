<?php

namespace App\Http\Requests\PersonType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonTypeRemoveDocumentTypeRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación.',
            'errors' => $validator->errors()
        ], 422));
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'person_type_document_type' => $this->input('person_type_id'),
        ]);
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
            'person_type_document_type' => ['exists:person_type_document_types,person_type_id,document_type_id,' . $this->input('document_type_id')]
        ];
    }

    public function messages()
    {
        return [
            'person_type_id.required' => 'El Identificador del tipo de persona es requerido.',
            'person_type_id.exists' => 'El Identificador del tipo de persona no es valido.',
            'document_type_id.required' => 'El Identificador del tipo de documento es requerido.',
            'document_type_id.exists' => 'El Identificador del tipo de documento no es valido.',
            'person_type_document_type.exists' => 'La relacion del Identificador del tipo de persona y el Identificador del tipo de documento no es valido.'
        ];
    }
}
