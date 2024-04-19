<?php

namespace App\Http\Requests\OrderReturnDetail;

use App\Models\OrderReturn;
use App\Models\OrderReturnDetail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderReturnDetailStoreRequest extends FormRequest
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
        $order_return_detail_quantities = $this->input('order_return_detail_quantities') ? $this->input('order_return_detail_quantities') : [];
        $updated_order_return_details = [];

        $order_detail = OrderReturn::with('order.order_details.order_detail_quantities.size')
            ->findOrFail($this->input('order_return_id'))->order->order_details
            ->where('product_id', $this->input('product_id'))
            ->where('color_id', $this->input('color_id'))
            ->where('tone_id', $this->input('tone_id'))
            ->whereIn('status', ['Despachado', 'Parcialmente Devuelto'])
            ->first();

        $order_return_details = OrderReturnDetail::with('order_return_detail_quantities.order_detail_quantity')
            ->where('order_detail_id', $order_detail->id)
            ->whereIn('status', ['Pendiente', 'Aprobado'])
            ->get()->pluck('order_return_detail_quantities');

        foreach($order_return_detail_quantities as $order_return_detail_quantity) {
            $order_detail_quantity = $order_detail->order_detail_quantities->where('size_id', $order_return_detail_quantity['size_id'])->first();

            $order_return_detail_quantity['min'] = 0;
            $order_return_detail_quantity['max'] = $order_detail_quantity ? $order_detail_quantity->quantity - $order_return_details->flatten()->where('order_detail_quantity.size_id', $order_return_detail_quantity['size_id'])->pluck('quantity')->sum() : 0;
            $order_return_detail_quantity['order_detail_quantity_id'] = $order_detail_quantity->id;

            $updated_order_return_details[] = $order_return_detail_quantity;
        }

        $this->merge([
            'order_return_detail_quantities' => $updated_order_return_details,
            'order_detail_id' => $order_detail->id
        ]);
    }

    public function authorize()
    {
        return true;

    }

    public function rules()
    {
        $rules = [
            'order_return_id' => ['required', 'exists:order_returns,id'],
            'order_detail_id' => ['required', 'exists:order_details,id', 'unique:order_return_details,order_detail_id,NULL,id,order_return_id,' . $this->input('order_return_id')],
            'product_id' => ['required', 'exists:products,id'],
            'color_id' => ['required', 'exists:colors,id'],
            'tone_id' => ['required', 'exists:tones,id'],
            'observation' => ['nullable', 'string', 'max:255'],
            'order_return_detail_quantities' => ['required', 'array'],
            'order_return_detail_quantities.*' => ['required', 'array'],
            'order_return_detail_quantities.*.size_id' => ['required', 'exists:sizes,id'],
        ];

        foreach ($this->input('order_return_detail_quantities') as $i => $order_return_detail_quantity) {
            $rules["order_return_detail_quantities.{$i}.quantity"] = [
                'required', 'numeric', 'min:' . $order_return_detail_quantity['min'], 'max:' . $order_return_detail_quantity['max'],
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'order_return_id.required' => 'El Identificador de la orden de devolucion del Pedido es requerido.',
            'order_return_id.exists' => 'El Identificador de la orden de devolucion del Pedido no es valido.',
            'order_detail_id.required' => 'El Identificador del detalle del Pedido es requerido.',
            'order_detail_id.exists' => 'El Identificador del detalle del Pedido no es valido.',
            'order_detail_id.unique' => 'El Identificador del detalle del Pedido ya ha sido tomado en otro detalle.',
            'product_id.required' => 'El Identificador del Producto es requerido.',
            'product_id.exists' => 'El Identificador del Producto no es valido.',
            'color_id.required' => 'El Identificador del Color es requerido.',
            'color_id.exists' => 'El Identificador del Color no es valido.',
            'tone_id.required' => 'El Identificador del Tono es requerido.',
            'tone_id.exists' => 'El Identificador del Tono no es valido.',
            'observation.string' => 'El campo Observacion del detalle de la orden de devolucion del pedido debe ser una cadena de caracteres.',
            'observation.max' => 'El campo Observacion del detalle de la orden de devolucion del pedido no debe exceder los 255 caracteres.',
            'order_return_detail_quantities.required' => 'El campo Detalles de la orden de devolucion del pedido es requerido.',
            'order_return_detail_quantities.array' => 'El campo Detalles de la orden de devolucion del pedido debe ser un arreglo.',
            'order_return_detail_quantities.*.required' => 'El item :position del campo Detalles de la orden de devolucion del pedido es requerido.',
            'order_return_detail_quantities.*.array' => 'El item :position del campo Detalles de la orden de devolucion del pedido debe ser un arreglo.',
            'order_return_detail_quantities.*.size_id.required' => 'El Identificador de la Talla en el item :position del campo Detalles de la orden de devolucion del pedido es requerido.',
            'order_return_detail_quantities.*.size_id.exists' => 'El Identificador de la Talla en el item :position del campo Detalles de la orden de devolucion del pedido no es valido.',
            'order_return_detail_quantities.*.quantity.required' => 'El campo Cantidad de unidades en el item :position del campo Detalles de la orden de devolucion del pedido es requerido.',
            'order_return_detail_quantities.*.quantity.numeric' => 'El campo Cantidad de unidades en el item :position del campo Detalles de la orden de devolucion del pedido debe ser un valor numérico.',
            'order_return_detail_quantities.*.quantity.max' => 'El campo Cantidad de unidades en el item :position del campo Detalles de la orden de devolucion del pedido no debe exceder los :max unidades.',
            'order_return_detail_quantities.*.quantity.min' => 'El campo Cantidad de unidades en el item :position del campo Detalles de la orden de devolucion del pedido debe tener al menos :min unidades.',
        ];
    }
}
