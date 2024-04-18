<?php

namespace App\Http\Requests\OrderReturnDetail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderReturnDetailIndexQueryRequest extends FormRequest
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
            'order_return_id' => ['required', 'exists:order_returns,id'],
        ];
    }

    public function messages()
    {
        return [
            'order_return_id.required' => 'El Identificador de la orden de devolucion del pedido es requerido.',
            'order_return_id.exists' => 'El Identificador de la orden de devolucion del no es válido.',
        ];
    }
}
