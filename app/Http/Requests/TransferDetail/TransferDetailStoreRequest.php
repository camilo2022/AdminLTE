<?php

namespace App\Http\Requests\TransferDetail;

use App\Models\Inventory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransferDetailStoreRequest extends FormRequest
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
        $inventory = Inventory::with('product', 'warehouse', 'color', 'tone', 'size')
            ->whereHas('product', fn($subQuery) => $subQuery->where('id', $this->input('product_id')))
            ->whereHas('warehouse', fn($subQuery) => $subQuery->where('id', $this->input('from_warehouse_id')))
            ->whereHas('color', fn($subQuery) => $subQuery->where('id', $this->input('color_id')))
            ->whereHas('tone', fn($subQuery) => $subQuery->where('id', $this->input('tone_id')))
            ->whereHas('size', fn($subQuery) => $subQuery->where('id', $this->input('size_id')))
            ->first();

        $this->merge([
            'min' => $inventory ? 1 : 0,
            'max' => $inventory ? $inventory->quantity : 0,
            'product_color_tone' => $this->input('product_id'),
            'product_size' => $this->input('product_id')
        ]);
    }
    
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'from_warehouse_id' => ['required', 'exists:warehouses,id'],
            'transfer_id' => ['required', 'exists:transfers,id'],
            'product_id' => ['required', 'exists:products,id', 'unique:transfer_details,product_id,NULL,id,transfer_id,' . $this->input('transfer_id') . ',color_id,' . $this->input('color_id') . ',tone_id,' . $this->input('tone_id') . ',size_id,' . $this->input('size_id') . ',deleted_at,NULL'],
            'color_id' => ['required', 'exists:colors,id'],
            'tone_id' => ['required', 'exists:tones,id'],
            'size_id' => ['required', 'exists:sizes,id'],
            'quantity' => ['required', 'numeric', 'min:' . $this->input('min'), 'max:' . $this->input('max')],
            'product_color_tone' => ['exists:product_color_tone,product_id,color_id,' . $this->input('color_id') . ',tone_id,' . $this->input('tone_id')],
            'product_size' => ['exists:product_sizes,product_id,size_id,' . $this->input('size_id')]
        ];
    }

    public function messages()
    {
        return [
            'from_warehouse_id.required' => 'El Identificador de la Bodega envia es requerido.',
            'from_warehouse_id.exists' => 'El Identificador de la bodega de envio no es valido.',
            'transfer_id.required' => 'El Identificador de la Transferencia es requerido.',
            'transfer_id.exists' => 'El Identificador de la transferencia no es valido.',
            'product_id.required' => 'El Identificador del Producto es requerido.',
            'product_id.exists' => 'El Identificador del producto no es valido.',
            'product_id.unique' => 'El Identificador del Producto, Color, Tono y Talla ya existe en la base de datos.',
            'color_id.required' => 'El Identificador del Color es requerido.',
            'color_id.exists' => 'El Identificador del color no es valido.',
            'tone_id.required' => 'El Identificador del Tono es requerido.',
            'tone_id.exists' => 'El Identificador del tono no es valido.',
            'size_id.required' => 'El Identificador de la Talla es requerido.',
            'size_id.exists' => 'El Identificador de la talla no es valido.',
            'quantity.required' => 'El campo Cantidad es requerido.',
            'quantity.numeric' => 'El campo Cantidad debe ser numerico.',
            'quantity.min' => 'La cantidad minima a tranferir debe ser de :min unidades.',
            'quantity.max' => 'La cantidad maxima disponible a transferir es de :max unidades.',
            'product_color_tone.exists' => 'El color y tono no estan asociados al producto.',
            'product_size.exists' => 'La talla no esta asociada el producto.',
        ];
    }
}
