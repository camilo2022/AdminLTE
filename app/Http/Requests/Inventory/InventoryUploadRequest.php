<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class InventoryUploadRequest extends FormRequest
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
            'inventories' => ['required', 'file', 'mimes:csv,xls,xlsx'],
        ];
    }

    public function messages()
    {
        return [
            'inventories.required' => 'El campo Archivo de inventarios es requerido.',
            'inventories.file' => 'El campo Archivo de inventarios debe ser un archivo.',
            'inventories.mimes' => 'El Archivo de inventarios debe tener una extensión válida (csv, xls, xlsx).',
        ];
    }
}
