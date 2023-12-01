<?php

namespace App\Http\Requests\AreasAndCharges;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AreasAndChargesStoreRequest extends FormRequest
{
    /**
     * Maneja una solicitud fallida de validación.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        // Lanzar una excepción de validación con los errores de validación obtenidos
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación',
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
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:255'],
            'charges' => ['required', 'array'],
            'charges.*.name' => ['required', 'string', 'max:255', 'unique:charges,name'],
            'charges.*.description' => ['nullable', 'string', 'max:255']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre de la categoria es requerido.',
            'name.string' => 'El campo Nombre de la categoria debe ser una cadena de caracteres.',
            'name.unique' => 'El campo Nombre de la categoria ya ha sido tomado.',
            'name.max' => 'El campo Nombre de la categoria no debe exceder los 255 caracteres.',
            'description.string' => 'El campo Descripcion de la categoria debe ser una cadena de caracteres.',
            'description.max' => 'El campo Descripcion de la categoria no debe exceder los 255 caracteres.',
            'charges.required' => 'El campo Subcategorias de la categoria es requerido.',
            'charges.array' => 'El campo Subcategorias de la categoria debe ser un arreglo.',
            'charges.*.name.required' => 'El campo Nombre de la subcategoria es requerido.',
            'charges.*.name.string' => 'El campo Nombre de la subcategoria debe ser una cadena de caracteres.',
            'charges.*.name.unique' => 'El campo Nombre de la subcategoria ya ha sido tomado.',
            'charges.*.name.max' => 'El campo Nombre de la subcategoria no debe exceder los 255 caracteres.',
            'charges.*.description.string' => 'El campo Descripcion de la subcategoria debe ser una cadena de caracteres.',
            'charges.*.description.max' => 'El campo Descripcion de la subcategoria no debe exceder los 255 caracteres.',
        ];
    }
}
