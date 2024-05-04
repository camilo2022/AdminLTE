<?php

namespace App\Http\Requests\OrderPurchase;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderPurchaseAssignPaymentQueryRequest extends FormRequest
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
            'order_purchase_id' => ['required', 'exists:order_purchases,id'],
            'payment_type_id' => ['nullable', 'exists:payment_types,id'],
        ];
    }

    public function messages()
    {
        return [
            'order_purchase_id.required' => 'El Identificador de la orden de compra es requerido.',
            'order_purchase_id.exists' => 'El Identificador de la orden de compra no es válido.',
            'payment_type_id.exists' => 'El Identificador del tipo de pago no es válido.',
        ];
    }
}
