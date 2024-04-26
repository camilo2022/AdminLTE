<?php

namespace App\Http\Requests\ClothType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClothTypeUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:cloth_types,name,' . $this->route('id') .',id', 'max:255'],
            'code' => ['required', 'string', 'unique:cloth_types,code,' . $this->route('id') .',id', 'max:255'],
            'description' => ['nullable', 'string', 'max:255']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre del tipo de tela es requerido.',
            'name.string' => 'El campo Nombre del tipo de tela debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre del tipo de tela ya existe en la base de datos.',
            'name.max' => 'El campo Nombre del tipo de tela no debe exceder los 255 caracteres.',
            'code.required' => 'El campo Codigo del tipo de tela es requerido.',
            'code.string' => 'El campo Codigo del tipo de tela debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo del tipo de tela ya existe en la base de datos.',
            'code.max' => 'El campo Codigo del tipo de tela no debe exceder los 255 caracteres.',
            'description.string' => 'El campo Descripcion del tipo de tela debe ser una cadena de texto.',
            'description.max' => 'El campo Descripcion del tipo de tela no debe exceder los 255 caracteres.'
        ];
    }
}
