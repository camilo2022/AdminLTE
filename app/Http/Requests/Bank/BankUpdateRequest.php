<?php

namespace App\Http\Requests\Bank;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BankUpdateRequest extends FormRequest
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
            'sector_code_entity_code' => $this->input('sector_code'),
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:banks,name,' . $this->route('id') .',id', 'max:255'],
            'sector_code' => ['required', 'string'],
            'entity_code' => ['required', 'string'],
            'sector_code_entity_code' => ['unique:banks,sector_code,' . $this->route('id') .',id,entity_code,' . $this->input('entity_code')],
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre del banco es requerido.',
            'name.string' => 'El campo Nombre del banco debe ser una cadena de caracteres.',
            'name.unique' => 'El campo Nombre del banco ya ha sido tomado.',
            'name.max' => 'El campo Nombre del banco no debe exceder los 255 caracteres.',
            'sector_code.required' => 'El campo Codigo del sector del banco es requerido.',
            'sector_code.string' => 'El campo Codigo del sector del banco debe ser una cadena de caracteres.',
            'entity_code.required' => 'El campo Codigo de entidad del banco es requerido.',
            'entity_code.numeric' => 'El campo Codigo de entidad del banco debe ser una cadena de digitos.',
            'sector_code_entity_code.exists' => 'El banco con los codigos proporcionados ya ha sido tomado.',
        ];
    }
}
