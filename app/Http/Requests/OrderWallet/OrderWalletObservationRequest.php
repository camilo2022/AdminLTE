<?php

namespace App\Http\Requests\OrderWallet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderWalletObservationRequest extends FormRequest
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
            'id' => ['required', 'exists:orders,id'],
            'wallet_observation' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El campo Pedido es requerido.',
            'id.exists' => 'El Identificador del pedido no es valido.',
            'wallet_observation.required' => 'El campo Observacion de cartera es requerido.',
            'wallet_observation.string' => 'El campo Observacion de cartera debe ser una cadena de caracteres.',
            'wallet_observation.max' => 'El campo Observacion de cartera no debe exceder los 255 caracteres.',
        ];
    }
}
