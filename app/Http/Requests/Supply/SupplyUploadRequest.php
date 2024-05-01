<?php

namespace App\Http\Requests\Supply;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SupplyUploadRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación',
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
            'supplies' => ['required', 'file', 'mimes:csv,xls,xlsx'],
        ];
    }

    public function messages()
    {
        return [
            'supplies.required' => 'El campo Archivo de insumos es requerido.',
            'supplies.file' => 'El campo Archivo de insumos debe ser un archivo.',
            'supplies.mimes' => 'El Archivo de insumos debe tener una extensión válida (csv, xls, xlsx).',
        ];
    }
}
