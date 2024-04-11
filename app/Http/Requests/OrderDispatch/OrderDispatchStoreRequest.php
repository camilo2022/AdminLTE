<?php

namespace App\Http\Requests\OrderDispatch;

use App\Models\Inventory;
use App\Models\OrderDetail;
use App\Models\OrderDetailQuantity;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderDispatchStoreRequest extends FormRequest
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
        $details = $this->input('details')  ? $this->input('details') : [];
        $updated_details = [];

        foreach($details as $i => $detail) {
            $order_detail = OrderDetail::findOrFail($detail['id']);
            $updated_details[$i]['id'] = $detail['id'];

            foreach($detail['quantities'] as $quantity) {
                try{
                    $order_detail_quantity = OrderDetailQuantity::findOrFail($quantity['id']);

                    $inventory = Inventory::with('warehouse')
                        ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                        ->where('product_id', $order_detail->product_id)
                        ->where('color_id', $order_detail->color_id)
                        ->where('tone_id', $order_detail->tone_id)
                        ->where('size_id', $order_detail_quantity->size_id)
                        ->first();

                    $quantity['min'] = 0;
                    $quantity['max'] = $inventory ? $inventory->quantity + $order_detail_quantity->quantity : 0;

                    $updated_details[$i]['quantities'][] = $quantity;
                } catch(Exception $e) {

                }
            }
        }

        $this->merge([
            'details' => $updated_details,
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'order_id' => ['required', 'exists:orders,id'],
            'details' => ['required', 'array'],
            'details.*.id' => ['required', 'exists:order_details,id', 'numeric'],
            'details.*.quantities' => ['required', 'array'],
            'details.*.quantities.*.id' => ['nullable', 'exists:order_detail_quantities,id'],
        ];

        foreach ($this->input('details') as $i => $detail) {
            foreach($detail['quantities'] as $j => $quantity) {
                $rules["details.{$i}.quantities.{$j}.quantity"] = [
                    'required', 'numeric', 'min:' . $quantity['min'], 'max:' . $quantity['max'],
                ];
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'order_id.required' => 'El Identificador del pedido es requerido.',
            'order_id.exists' => 'El Identificador del pedido no es válido.',
            'details.required' => 'El campo Detalles de filtrado es requerido.',
            'details.array' => 'El campo Detalles de filtrado debe ser un arreglo.',
            'details.*.id.required' => 'El Identificador del detalle del pedido es requerido.',
            'details.*.id.exists' => 'El Identificador del detalle del pedido no es válido.',
            'details.*.id.numeric' => 'El Identificador del detalle del pedido debe ser un valor numérico.',
            'details.*.quantities.required' => 'El campo Cantidades del detalle de filtrado es requerido.',
            'details.*.quantities.array' => 'El campo Cantidades del detalle de filtrado debe ser un arreglo.',
            'details.*.quantities.*.id.required' => 'El Identificador del detalle de la cantidad de unidades es requerido.',
            'details.*.quantities.*.id.exists' => 'El Identificador del detalle de la cantidad de unidades no es valido.',
            'details.*.quantities.*.quantity.required' => 'El campo Cantidad de unidades es requerido.',
            'details.*.quantities.*.quantity.numeric' => 'El campo Cantidad de unidades debe ser un valor numérico.',
            'details.*.quantities.*.quantity.max' => 'El campo Cantidad de unidades no debe exceder los :max unidades.',
            'details.*.quantities.*.quantity.min' => 'El campo Cantidad de unidades debe tener al menos :min unidades.',
        ];
    }
}
