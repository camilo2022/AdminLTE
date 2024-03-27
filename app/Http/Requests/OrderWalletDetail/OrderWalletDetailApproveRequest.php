<?php

namespace App\Http\Requests\OrderWalletDetail;

use App\Models\OrderDetail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderWalletDetailApproveRequest extends FormRequest
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
        $orderDetail = OrderDetail::with('order.client.client_type', 'order_detail_quantities')->findOrFail($this->input('id'));
        $order_value = $orderDetail->price * $orderDetail->order_detail_quantities->pluck('quantity')->sum();
        $quota_available = $orderDetail->order->client->quota - $orderDetail->order->client->debt;

        $this->merge([
            'order_value' => $orderDetail->order->client->client_type->require_quota ? $order_value : '',
            'quota_available' => $orderDetail->order->client->client_type->require_quota ? $quota_available : '',
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => ['required', 'exists:order_details,id'],
            'quota_available' => ['nullable', 'numeric', 'min:' . $this->input('order_value')],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El campo Detalle del pedido es requerido.',
            'id.exists' => 'El Identificador del detalle del pedido no es valido.',
            'quota_available.min' => 'El cliente tiene un cupo disponible actual de ' . number_format($this->input('quota_available'), 0, ',', '.') . '. El valor del detalle del pedido es de ' . number_format($this->input('order_value'), 0, ',', '.') . '. No se puede aprobar el detalle del pedido hasta que el cliente tengo cupo disponible.',
        ];
    }
}
