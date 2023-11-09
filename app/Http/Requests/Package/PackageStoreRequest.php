<?php

namespace App\Http\Requests\Package;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PackageStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function failedValidation(Validator $validator)
    {
        // Lanzar una excepción de validación con los errores de validación obtenidos
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación.',
            'errors' => $validator->errors()
        ], 422));
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:packages,name'],
            'code' => ['required', 'string', 'unique:packages,code']
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre del tipo de paquete es requerido.',
            'name.string' => 'El campo Nombre del tipo de paquete debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre del tipo de paquete ya existe en la base de datos.',
            'code.required' => 'El campo Codigo del tipo de paquete es requerido.',
            'code.string' => 'El campo Codigo del tipo de paquete debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo del tipo de paquete ya existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nombre del tipo de paquete',
            'code' => 'Codigo del tipo de paquete'
        ];
    }
}
