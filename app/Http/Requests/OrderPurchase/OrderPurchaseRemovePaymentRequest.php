<?php

namespace App\Http\Requests\OrderPurchase;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderPurchaseRemovePaymentRequest extends FormRequest
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
            'id' => ['required', 'exists:payments,id'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El Identificador del pago de la orden de compra es requerido.',
            'id.exists' => 'El Identificador del pago de la orden de compra no es válido.',
        ];
    }
}
