<?php

namespace App\Http\Requests\OrderWallet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderWalletPartiallyApproveRequest extends FormRequest
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
            'id' => ['required', 'exists:orders,id']
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El campo Pedido es requerido.',
            'id.exists' => 'El Identificador del pedido no es valido.'
        ];
    }
}
