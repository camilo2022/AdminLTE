<?php

namespace App\Http\Requests\OrderReturnDetail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderReturnDetailCancelRequest extends FormRequest
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
            'id' => ['required', 'exists:order_return_details,id']
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El Identificador del detalle de la orden de devolucion del pedido es requerido.',
            'id.exists' => 'El Identificador del detalle de la orden de devolucion del no es válido.',
        ];
    }
}
