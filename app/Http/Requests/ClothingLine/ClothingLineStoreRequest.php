<?php

namespace App\Http\Requests\ClothingLine;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClothingLineStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:clothing_lines,name', 'max:255'],
            'code' => ['required', 'string', 'unique:clothing_lines,code', 'max:255'],
            'description' => ['nullable', 'string', 'max:255']
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
            'description.string' => 'El campo Descripcion de la linea de producto debe ser una cadena de texto.',
            'description.max' => 'El campo Descripcion de la linea de producto no debe exceder los 255 caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nombre de la linea de producto',
            'code' => 'Codigo de la linea de producto',
            'description' => 'Descripcion de la linea de producto'
        ];
    }
}
