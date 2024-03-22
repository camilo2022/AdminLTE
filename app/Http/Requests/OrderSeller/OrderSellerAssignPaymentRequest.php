<?php

namespace App\Http\Requests\OrderSeller;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderSellerAssignPaymentRequest extends FormRequest
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
            'value' => ['required', 'numeric'],
            'reference' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date_format:Y-m-d H:i:s'],
            'payment_type_id' => ['required', 'exists:payment_types,id'],
            'bank_id' => ['nullable', 'exists:banks,id'],
            'supports' => ['nullable', 'array'],
            'supports.*' => ['file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
        ];
    }

    public function messages()
    {
        return [
            'order_id.required' => 'El Identificador del pedido es requerido.',
            'order_id.exists' => 'El Identificador del pedido no es válido.',
            'value.required' => 'El campo Valor de pago es requerido.',
            'value.string' => 'El campo Valor de pago debe ser un valor numerico.',
            'reference.required' => 'El campo Referencia de pago es requerido.',
            'reference.string' => 'El campo Referencia de pago debe ser una cadena de caracteres.',
            'reference.max' => 'El campo Referencia de pago no debe exceder los 255 caracteres.',
            'date.required' => 'El campo Fecha de pago es requerido.',
            'date.date_format' => 'El campo Fecha de pago debe tener un formato de fecha valido.',
            'payment_type_id.required' => 'El Identificador del tipo de pago es requerido.',
            'payment_type_id.exists' => 'El Identificador del tipo de pago no es válido.',
            'bank_id.exists' => 'El Identificador del banco no es válido.',
            'supports.array' => 'El campo Soportes de pago debe ser un arreglo.',
            'supports.*.file' => 'El Soporte #:position debe ser un archivo.',
            'supports.*.mimes' => 'El Soporte #:position debe tener una extensión válida (jpeg, jpg, png, gif).',
            'supports.*.max' => 'El Soporte #:position no debe superar los 5 MB (5120 KB).',
        ];
    }
}
