<?php

namespace App\Http\Requests\OrderReturn;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderReturnStoreRequest extends FormRequest
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
            'return_type_id' => ['required', 'exists:return_types,id'],
            'return_date' => ['required', 'date_format:Y-m-d H:i:s'],
            'return_observation' => ['nullable', 'string', 'max:255']
        ];
    }

    public function messages()
    {
        return [
            'order_id.required' => 'El Identificador del pedido es requerido.',
            'order_id.exists' => 'El Identificador del pedido no es válido.',
            'return_type_id.required' => 'El Identificador del tipo de devolucion es requerido.',
            'return_type_id.exists' => 'El Identificador del tipo de devolucion no es válido.',
            'return_date.required' => 'El campo Fecha de devolucion del pedido es requerido.',
            'return_date.date_format' => 'El campo Fecha de devolucion del pedido debe tener un formato de fecha valido.',
            'return_observation.string' => 'El campo Observacion de la devolucion del pedido debe ser una cadena de caracteres.',
            'return_observation.max' => 'El campo Observacion de la devolucion del pedido no debe exceder los 255 caracteres.'
        ];
    }
}
