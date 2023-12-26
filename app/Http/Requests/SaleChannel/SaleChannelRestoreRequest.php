<?php

namespace App\Http\Requests\SaleChannel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaleChannelRestoreRequest extends FormRequest
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
            'id' => ['required', 'exists:sale_channels,id'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El Identificador del canal de venta es requerido.',
            'id.exists' => 'El Identificador del canal de venta no es válido.',
        ];
    }
}
