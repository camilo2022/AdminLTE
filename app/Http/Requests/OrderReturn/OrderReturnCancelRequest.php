<?php

namespace App\Http\Requests\OrderReturn;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderReturnCancelRequest extends FormRequest
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
            'id' => ['required', 'exists:order_returns,id']
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El Identificador de devolucion del pedido es requerido.',
            'id.exists' => 'El Identificador de devolucion del pedido no es válido.',
        ];
    }
}
