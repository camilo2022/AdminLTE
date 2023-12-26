<?php

namespace App\Http\Requests\SaleChannel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaleChannelUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:sale_channels,name,' . $this->route('id') .',id', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre del canal de Venta es requerido.',
            'name.string' => 'El campo Nombre del canal de Venta debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre del canal de Venta ya existe en la base de datos.',
            'name.max' => 'El campo Nombre del canal de Venta no debe exceder los 255 caracteres.',
        ];
    }
}
