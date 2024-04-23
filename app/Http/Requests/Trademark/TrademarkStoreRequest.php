<?php

namespace App\Http\Requests\Trademark;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TrademarkStoreRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        // Lanzar una excepción de validación con los errores de validación obtenidos
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación.',
            'errors' => $validator->errors()
        ], 422));
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_internal' => $this->input('is_internal') === 'true',
        ]);
    }
    
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:trademarks,name', 'max:255'],
            'code' => ['required', 'string', 'unique:trademarks,code', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_internal' => ['required', 'boolean'],
            'logo' => ['nullable', 'mimes:jpeg,jpg,png,gif', 'max:2048']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre de la marca es requerido.',
            'name.string' => 'El campo Nombre de la marca debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre de la marca ya existe en la base de datos.',
            'name.max' => 'El campo Nombre de la marca no debe tener mas de 255 caracteres.',
            'code.required' => 'El campo Codigo de la marca es requerido.',
            'code.string' => 'El campo Codigo de la marca debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo de la marca ya existe en la base de datos.',
            'code.max' => 'El campo Nombre de la marca no debe tener mas de 255 caracteres.',
            'description.string' => 'El campo Descripcion de la marca debe ser una cadena de texto.',
            'description.max' => 'El campo Descripcion de la marca no debe tener mas de 255 caracteres.',
            'is_internal.required' => 'El campo Marca interna de la empresa es requerido.',
            'is_internal.boolean' => 'El campo Marca interna de la empresa debe ser true o false.',
            'logo.mimes' => 'El campo archivo de Logo de la marca debe tener una extensión válida (jpeg, jpg, png, gif).',
            'logo.max' => 'El campo archivo de Logo de la marca no debe superar los 2 MB (2048 KB).',
        ];
    }
}
