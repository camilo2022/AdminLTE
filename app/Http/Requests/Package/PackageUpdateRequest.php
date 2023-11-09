<?php

namespace App\Http\Requests\Package;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PackageUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:packages,name,' . $this->route('id') .',id'],
            'code' => ['required', 'string', 'unique:packages,code,' . $this->route('id') .',id'],
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre de la correria es requerido.',
            'name.string' => 'El campo Nombre de la correria debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre de la correria ya existe en la base de datos.',
            'code.required' => 'El campo Codigo de la correria es requerido.',
            'code.string' => 'El campo Codigo de la correria debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo de la correria ya existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nombre de la correria',
            'code' => 'Codigo de la correria'
        ];
    }
}

