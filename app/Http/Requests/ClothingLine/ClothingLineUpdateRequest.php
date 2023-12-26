<?php

namespace App\Http\Requests\ClothingLine;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClothingLineUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:clothing_lines,name,' . $this->route('id') .',id', 'max:255'],
            'code' => ['required', 'string', 'unique:clothing_lines,code,' . $this->route('id') .',id', 'max:255'],
            'description' => ['required', 'string', 'max:255']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre de la linea de producto es requerido.',
            'name.string' => 'El campo Nombre de la linea de producto debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre de la linea de producto ya existe en la base de datos.',
            'name.max' => 'El campo Nombre de la linea de producto no debe exceder los 255 caracteres.',
            'code.required' => 'El campo Codigo de la linea de producto es requerido.',
            'code.string' => 'El campo Codigo de la linea de producto debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo de la linea de producto ya existe en la base de datos.',
            'code.max' => 'El campo Codigo de la linea de producto no debe exceder los 255 caracteres.',
            'description.required' => 'El campo Codigo de la linea de producto es requerido.',
            'description.string' => 'El campo Codigo de la linea de producto debe ser una cadena de texto.',
            'description.max' => 'El campo Descripcion de la linea de producto no debe exceder los 255 caracteres.',
        ];
    }
}
