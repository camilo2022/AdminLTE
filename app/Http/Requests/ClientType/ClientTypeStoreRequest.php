<?php

namespace App\Http\Requests\ClientType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClientTypeStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:client_types,name', 'max:255'],
            'code' => ['required', 'string', 'unique:client_types,code', 'max:255']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre del tipo de Cliente es requerido.',
            'name.string' => 'El campo Nombre del tipo de Cliente debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre del tipo de Cliente ya existe en la base de datos.',
            'name.max' => 'El campo Nombre del tipo de Cliente no debe exceder los 255 caracteres.',
            'code.required' => 'El campo Codigo del tipo de Cliente es requerido.',
            'code.string' => 'El campo Codigo del tipo de Cliente debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo del tipo de Cliente ya existe en la base de datos.',
            'code.max' => 'El campo Codigo del tipo de Cliente no debe exceder los 255 caracteres.',
        ];
    }
}
