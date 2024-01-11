<?php

namespace App\Http\Requests\PersonType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonTypeStoreRequest extends FormRequest
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
            'require_people' => $this->input('require_people') === 'true',
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:person_types,name', 'max:255'],
            'code' => ['required', 'string', 'unique:person_types,code', 'max:255'],
            'require_people' => ['required', 'boolean']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre del tipo de Persona es requerido.',
            'name.string' => 'El campo Nombre del tipo de Persona debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre del tipo de Persona ya existe en la base de datos.',
            'name.max' => 'El campo Nombre del tipo de Persona no debe exceder los 255 caracteres.',
            'code.required' => 'El campo Codigo del tipo de Persona es requerido.',
            'code.string' => 'El campo Codigo del tipo de Persona debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo del tipo de Persona ya existe en la base de datos.',
            'code.max' => 'El campo Codigo del tipo de Persona no debe exceder los 255 caracteres.',
            'require_people.required' => 'El campo Referencias del tipo de Persona es requerido.',
            'require_people.boolean' => 'El campo Referencias del tipo de Persona debe ser true o false.',
        ];
    }
}
