<?php

namespace App\Http\Requests\ClothComposition;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClothCompositionUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:cloth_compositions,name,' . $this->route('id') .',id', 'max:255'],
            'description' => ['nullable', 'string', 'max:255']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre de la composicion de la tela es requerido.',
            'name.string' => 'El campo Nombre de la composicion de la tela debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre de la composicion de la tela ya existe en la base de datos.',
            'name.max' => 'El campo Nombre de la composicion de la tela no debe exceder los 255 caracteres.',
            'description.string' => 'El campo Descripcion de la composicion de la tela debe ser una cadena de texto.',
            'description.max' => 'El campo Descripcion de la composicion de la tela no debe exceder los 255 caracteres.'
        ];
    }
}
