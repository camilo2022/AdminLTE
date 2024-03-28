<?php

namespace App\Http\Requests\Wallet;

use App\Models\OrderDispatch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class WalletAssignPaymentRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación.',
            'errors' => $validator->errors()
        ], 422));
    }

    protected function prepareForValidation()
    {
        $orderDispatch = OrderDispatch::with('order.client.client_type', 'payments', 'invoices')->findOrFail($this->input('order_dispatch_id'));
        $payment_value = $orderDispatch->payments->pluck('value')->sum();
        $invoice_value = $orderDispatch->invoices->pluck('value')->sum();
        $payment_available = $invoice_value - $payment_value;

        $this->merge([
            'payment_available' => $orderDispatch->order->client->client_type->require_quota ? $payment_available : '',
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'order_dispatch_id' => ['required', 'exists:order_dispatches,id'],
            'value' => ['required', 'numeric', 'max:' . $this->input('payment_available')],
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
            'order_dispatch_id.required' => 'El Identificador de la orden de despacho es requerido.',
            'order_dispatch_id.exists' => 'El Identificador de la orden de despacho no es válido.',
            'value.required' => 'El campo Valor de pago es requerido.',
            'value.string' => 'El campo Valor de pago debe ser un valor numerico.',
            'value.max' => 'El Valor de pago no puede ser mayor a la deuda actual que es de ' . number_format($this->input('payment_available'), 0, ',', '.') . '.',
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
