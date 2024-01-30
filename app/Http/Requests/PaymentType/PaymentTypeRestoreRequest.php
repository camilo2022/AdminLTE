<?php

namespace App\Http\Requests\PaymentType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentTypeRestoreRequest extends FormRequest
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
            'id' => ['required', 'exists:payment_types,id'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El Identificador del tipo de pago es requerido.',
            'id.exists' => 'El Identificador del tipo de pago no es válido.',
        ];
    }
}
