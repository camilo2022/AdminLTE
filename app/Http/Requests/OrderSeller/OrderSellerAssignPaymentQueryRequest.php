<?php

namespace App\Http\Requests\OrderSeller;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderSellerAssignPaymentQueryRequest extends FormRequest
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
            'order_id' => ['required', 'exists:orders,id'],
            'payment_type_id' => ['nullable', 'exists:payment_types,id'],
        ];
    }

    public function messages()
    {
        return [
            'order_id.required' => 'El Identificador del pedido es requerido.',
            'order_id.exists' => 'El Identificador del pedido no es válido.',
            'payment_type_id.exists' => 'El Identificador del tipo de pago no es válido.',
        ];
    }
}
