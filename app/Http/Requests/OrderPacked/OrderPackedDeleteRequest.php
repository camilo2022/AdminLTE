<?php

namespace App\Http\Requests\OrderPacked;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderPackedDeleteRequest extends FormRequest
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
            'id' => ['required', 'exists:order_packings,id'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El Identificador de la orden de alistamiento y empacado de la orden de despacho es requerido.',
            'id.exists' => 'El Identificador de la orden de alistamiento y empacado de la orden de despacho no es válido.',
        ];
    }
}
