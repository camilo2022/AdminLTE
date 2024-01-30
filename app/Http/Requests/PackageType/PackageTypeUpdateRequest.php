<?php

namespace App\Http\Requests\PackageType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PackageTypeUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:package_types,name,' . $this->route('id') .',id'],
            'code' => ['required', 'string', 'unique:package_types,code,' . $this->route('id') .',id'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre del tipo de empaque es requerido.',
            'name.string' => 'El campo Nombre del tipo de empaque debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre del tipo de empaque ya existe en la base de datos.',
            'code.required' => 'El campo Codigo del tipo de empaque es requerido.',
            'code.string' => 'El campo Codigo del tipo de empaque debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo del tipo de empaque ya existe en la base de datos.'
        ];
    }
}
