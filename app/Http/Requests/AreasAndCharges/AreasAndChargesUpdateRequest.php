<?php

namespace App\Http\Requests\AreasAndCharges;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AreasAndChargesUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $this->route('id') . ',id'],
            'description' => ['nullable', 'string', 'max:255'],
            'charges' => ['required', 'array'],
            'charges.*.name' => ['required', 'string', 'max:255', 'unique:charges,name,' . $this->route('id') . ',area_id'],
            'charges.*.description' => ['nullable', 'string', 'max:255']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre del area es requerido.',
            'name.string' => 'El campo Nombre del area debe ser una cadena de caracteres.',
            'name.unique' => 'El campo Nombre del area ya ha sido tomado.',
            'name.max' => 'El campo Nombre del area no debe exceder los 255 caracteres.',
            'description.string' => 'El campo Descripcion del area debe ser una cadena de caracteres.',
            'description.max' => 'El campo Descripcion del area no debe exceder los 255 caracteres.',
            'charges.required' => 'El campo Cargos del area es requerido.',
            'charges.array' => 'El campo Cargos del area debe ser un arreglo.',
            'charges.*.name.required' => 'El campo Nombre del cargo es requerido.',
            'charges.*.name.string' => 'El campo Nombre del cargo debe ser una cadena de caracteres.',
            'charges.*.name.unique' => 'El campo Nombre del cargo ya ha sido tomado.',
            'charges.*.name.max' => 'El campo Nombre del cargo no debe exceder los 255 caracteres.',
            'charges.*.description.string' => 'El campo Descripcion del cargo debe ser una cadena de caracteres.',
            'charges.*.description.max' => 'El campo Descripcion del cargo no debe exceder los 255 caracteres.',
        ];
    }
}
