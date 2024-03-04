<?php

namespace App\Http\Requests\OrderSellerDetail;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderSellerDetailStoreRequest extends FormRequest
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
        $product = Product::findOrFail($this->input('product_id'));

        $order_detail_quantities = $this->input('order_detail_quantities')  ? $this->input('order_detail_quantities') : [];
        $updated_order_details = [];

        foreach($order_detail_quantities as $order_detail_quantity) {
            $inventory = Inventory::with('warehouse')
                ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
                ->where('product', $this->input('product_id'))
                ->where('color', $this->input('color_id'))
                ->where('tone', $this->input('tone_id'))
                ->where('size', $order_detail_quantity['size_id'])
                ->first();

            $order_detail_quantity['min'] = 0;
            $order_detail_quantity['max'] = $inventory ? $inventory->quantity : 0;
            $order_detail_quantity['product_size'] = $order_detail_quantity['size_id'];

            $updated_order_details[] = $order_detail_quantity;
        }

        $this->merge([
            'order_detail_quantities' => $updated_order_details,
            'product_color_tone' => $this->input('product_id'),
            'price' => $product->price
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
            'product_id' => ['required', 'exists:products,id', 'unique:order_details,product_id,NULL,id,order_id,' . $this->input('order_id') . ',color_id,' . $this->input('color_id') . ',tone_id,' . $this->input('tone_id')],
            'color_id' => ['required', 'exists:colors,id'],
            'tone_id' => ['required', 'exists:tones,id'],
            'price' => ['required', 'numeric', 'between:0,999999.99'],
            'seller_observation' => ['nullable', 'string', 'max:255'],
            'product_color_tone' => ['exists:product_color_tone,product_id,color_id,' . $this->input('color_id') . ',tone_id,' . $this->input('tone_id')],
            'order_detail_quantities' => ['required', 'array'],
            'order_detail_quantities.*' => ['required', 'array'],
            'order_detail_quantities.*.size_id' => ['required', 'exists:sizes,id'],
            'order_detail_quantities.*.product_size' => ['exists:product_sizes,size_id,product_id,' . $this->input('product_id')]
        ];

        foreach ($this->input('order_detail_quantities') as $i => $order_detail_quantity) {
            $rules["order_detail_quantities.{$i}.quantity"] = [
                'required', 'numeric', 'min:' . $order_detail_quantity['min'], 'max:' . $order_detail_quantity['max'],
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'order_id.required' => 'El Identificador del Pedido es requerido.',
            'order_id.exists' => 'El Identificador del Pedido no es valido.',
            'product_id.required' => 'El Identificador del Producto es requerido.',
            'product_id.exists' => 'El Identificador del Producto no es valido.',
            'product_id.unique' => 'El Identificador del Producto ya ha sido tomado en otro detalle.',
            'color_id.required' => 'El Identificador del Color es requerido.',
            'color_id.exists' => 'El Identificador del Color no es valido.',
            'tone_id.required' => 'El Identificador del Tono es requerido.',
            'tone_id.exists' => 'El Identificador del Tono no es valido.',
            'price.required' => 'El campo Precio del producto es requerido.',
            'price.numeric' => 'El campo Precio del producto debe ser numerico.',
            'price.between' => 'El campo Precio del producto debe estar en un rango de 0 a 999999.99.',
            'seller_observation.string' => 'El campo Observacion del asesor debe ser una cadena de caracteres.',
            'seller_observation.max' => 'El campo Observacion del asesor no debe exceder los 255 caracteres.',
            'product_color_tone.exists' => 'El color y tono no pertenecen al producto seleccionado.',
            'order_detail_quantities.required' => 'El campo Detalles del pedido es requerido.',
            'order_detail_quantities.array' => 'El campo Detalles del pedido debe ser un arreglo.',
            'order_detail_quantities.*.required' => 'El item :position del campo Detalles del pedido es requerido.',
            'order_detail_quantities.*.array' => 'El item :position del campo Detalles del pedido debe ser un arreglo.',
            'order_detail_quantities.*.size_id.required' => 'El Identificador de la Talla es requerido.',
            'order_detail_quantities.*.size_id.exists' => 'El Identificador de la Talla no es valido.',
            'order_detail_quantities.*.product_size.exists' => 'La talla no pertenece al producto seleccionado.',
            'order_detail_quantities.*.quantity.required' => 'El campo Cantidad de unidades es requerido.',
            'order_detail_quantities.*.quantity.numeric' => 'El campo Cantidad de unidades debe ser un valor numérico.',
            'order_detail_quantities.*.quantity.max' => 'El campo Cantidad de unidades no debe exceder los :max unidades.',
            'order_detail_quantities.*.quantity.min' => 'El campo Cantidad de unidades debe tener al menos :min unidades.',
        ];
    }
}
