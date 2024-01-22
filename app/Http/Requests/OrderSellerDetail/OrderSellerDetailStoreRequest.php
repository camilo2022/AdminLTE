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

        $order_detail_quantities = $this->input('order_detail_quantities');
        $updated_order_details = [];

        foreach($order_detail_quantities as $order_detail_quantity) {
            $inventory = Inventory::with('product', 'warehouse', 'color', 'tone', 'size')
            ->whereHas('product', fn($subQuery) => $subQuery->where('id', $this->input('product_id')))
            ->whereHas('warehouse', fn($subQuery) => $subQuery->where('to_discount', true))
            ->whereHas('color', fn($subQuery) => $subQuery->where('id', $this->input('color_id')))
            ->whereHas('tone', fn($subQuery) => $subQuery->where('id', $this->input('tone_id')))
            ->whereHas('size', fn($subQuery) => $subQuery->where('id', $order_detail_quantity['size_id']))
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
            'product_id' => ['required', 'exists:products,id', 'unique:order_details,product_id,NULL,id,order_id,' . $this->input('order_id')],
            'color_id' => ['required', 'exists:colors,id'],
            'tone_id' => ['required', 'exists:tones,id'],
            'price' => ['required', 'numeric', 'between:0,999999.99'],
            'seller_observation' => ['nullable', 'string', 'max:255'],
            'product_color_tone' => ['exists:product_color_tone,product_id,color_id,' . $this->input('color_id') . ',tone_id,' . $this->input('tone_id')],
            'order_detail_quantities' => ['required', 'array'],
            'order_detail_quantities.*' => ['required', 'array'],
            'order_detail_quantities.*.size_id' => ['required', 'exists:sizes,id'],
            'order_detail_quantities.*.product_size' => ['required', 'exists:product_sizes,size_id,product_id,' . $this->input('product_id')]
        ];

        foreach ($this->input('order_detail_quantities') as $index => $order_detail_quantity) {
            $rules["order_detail_quantities.{$index}.quantity"] = [
                'required', 'numeric', 'min:' . $order_detail_quantity['min'], 'max:' . $order_detail_quantity['max'],
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'order_id.required' => 'El Identificador del Pedido es requerido.',
            'order_id.exists' => 'El Identificador del pedido no es valido.',
            'product_id.required' => 'El Identificador del Producto es requerido.',
            'product_id.exists' => 'El Identificador del producto no es valido.',
            'product_id.unique' => 'El Identificador del producto ya ha sido tomado en otro detalle.',

            'client_id.required' => 'El campo Cliente es requerido.',
            'client_id.exists' => 'El Identificador del cliente no es valido.',
            'client_branch_id.required' => 'El campo Sucursal del Cliente es requerido.',
            'client_branch_id.exists' => 'El Identificador de la sucursal del cliente no es valido.',
            'dispatch.required' => 'El campo Despacho es requerido.',
            'dispatch.string' => 'El campo Despacho debe ser una cadena de caracteres.',
            'dispatch.max' => 'El campo Despacho no debe exceder los 255 caracteres.',
            'dispatch_date.required' => 'El campo Fecha de despacho es requerido.',
            'dispatch_date.date' => 'El campo Fecha de despacho debe ser una fecha valida.',
            'dispatch_date.after_or_equal' => 'El campo Fecha de despacho debe ser posterior o igual a la fecha actual :now.',
            'seller_observation.string' => 'El campo Observacion del asesor debe ser una cadena de caracteres.',
            'seller_observation.max' => 'El campo Observacion del asesor no debe exceder los 255 caracteres.',
            'correria_id.required' => 'El campo Correria es requerido.',
            'correria_id.exists' => 'El Identificador de la correria no es valido.',
            'client_clientBranch.exists' => 'La sucursal no pertenece al cliente seleccionado.'
        ];
    }
}
