<?php

namespace App\Http\Requests\OrderWallet;

use App\Models\Inventory;
use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderWalletApproveRequest extends FormRequest
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
        $order = Order::with('order_details.order_detail_quantities', 'client.client_type')->findOrFail($this->input('id'));
        $order_value = 0;
        $quota_available = $order->client->quota - $order->client->debt;

        foreach($order->order_details->whereIn('status', ['Revision']) as $detail) {
            $boolean = true;
            foreach($detail->order_detail_quantities as $quantity) {
                $inventory = Inventory::with('warehouse')
                    ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                    ->where('product_id', $detail->product_id)
                    ->where('size_id', $quantity->size_id)
                    ->where('color_id', $detail->color_id)
                    ->where('tone_id', $detail->tone_id)
                    ->first();

                if($inventory->quantity < $quantity->quantity) {
                    $boolean = false;
                    break;
                }
            }

            $order_value += $boolean ? $detail->price * $detail->order_detail_quantities->pluck('quantity')->sum() : 0 ;
        }

        $this->merge([
            'order_value' => $order->client->client_type->require_quota ? $order_value : 0,
            'quota_available' => $order->client->client_type->require_quota ? $quota_available : 0,
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => ['required', 'exists:orders,id'],
            'quota_available' => ['nullable', 'numeric', 'min:' . $this->input('order_value')],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El campo Pedido es requerido.',
            'id.exists' => 'El Identificador del pedido no es valido.',
            'quota_available.min' => 'El cliente tiene un cupo disponible actual de ' . number_format($this->input('quota_available'), 0, ',', '.') . '. El valor del pedido es de ' . number_format($this->input('order_value'), 0, ',', '.') . '. No se puede aprobar el pedido hasta que el cliente tengo cupo disponible.',
        ];
    }
}
