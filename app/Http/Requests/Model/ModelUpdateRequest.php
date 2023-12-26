<?php

namespace App\Http\Requests\Model;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ModelUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:models,name,' . $this->route('id') .',id', 'max:255'],
            'code' => ['required', 'string', 'unique:models,code,' . $this->route('id') .',id', 'max:255'],
            'description' => ['required', 'string', 'max:255']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre del modelo de producto es requerido.',
            'name.string' => 'El campo Nombre del modelo de producto debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre del modelo de producto ya existe en la base de datos.',
            'name.max' => 'El campo Nombre del modelo de producto no debe exceder los 255 caracteres.',
            'code.required' => 'El campo Codigo del modelo de producto es requerido.',
            'code.string' => 'El campo Codigo del modelo de producto debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo del modelo de producto ya existe en la base de datos.',
            'code.max' => 'El campo Codigo del modelo de producto no debe exceder los 255 caracteres.',
            'description.required' => 'El campo Codigo del modelo de producto es requerido.',
            'description.string' => 'El campo Codigo del modelo de producto debe ser una cadena de texto.',
            'description.max' => 'El campo Descripcion del modelo de producto no debe exceder los 255 caracteres.',
        ];
    }
}
