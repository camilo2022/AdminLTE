<?php

namespace App\Http\Requests\TransferDetail;

use App\Models\Inventory;
use App\Models\TransferDetail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransferDetailUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function failedValidation(Validator $validator)
    {
        // Lanzar una excepción de validación con los errores de validación obtenidos
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación.',
            'errors' => $validator->errors()
        ], 422));
    }

    protected function prepareForValidation()
    {
        $tranferDetail = TransferDetail::findOrFail($this->route('id'));

        $inventory = Inventory::with('product', 'warehouse', 'color', 'tone', 'size')
            ->whereHas('product', fn($subQuery) => $subQuery->where('id', $this->input('product_id')))
            ->whereHas('warehouse', fn($subQuery) => $subQuery->where('id', $this->input('from_warehouse_id')))
            ->whereHas('color', fn($subQuery) => $subQuery->where('id', $this->input('color_id')))
            ->whereHas('tone', fn($subQuery) => $subQuery->where('id', $this->input('tone_id')))
            ->whereHas('size', fn($subQuery) => $subQuery->where('id', $this->input('size_id')))
            ->first();

        $this->merge([
            'min' => $inventory ? 1 : 0,
            'max' => $inventory ? $inventory->quantity + $tranferDetail->quantity : 0,
            'product_color_tone' => $this->input('product_id'),
            'product_size' => $this->input('product_id')
        ]);
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'from_warehouse_id' => ['required', 'exists:warehouses,id'],
            'transfer_id' => ['required', 'exists:transfers,id'],
            'product_id' => ['required', 'exists:products,id', 'unique:transfer_details,product_id,transfer_id,' . $this->input('transfer_id') . ',color_id,' . $this->input('color_id') . ',tone_id,' . $this->input('tone_id') . ',size_id,' . $this->input('size_id') . 'deleted_at,NULL,' . $this->route('id') . ',id'],
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
            'from_warehouse_id.required' => 'El campo Bodega envia es requerido.',
            'from_warehouse_id.exists' => 'El Identificador de la bodega de envio no es valido.',
            'transfer_id.required' => 'El campo Transferencia es requerido.',
            'transfer_id.exists' => 'El Identificador de la transferencia no es valido.',
            'product_id.required' => 'El campo Producto es requerido.',
            'product_id.exists' => 'El Identificador del producto no es valido.',
            'product_id.unique' => 'El Producto, Color, Tono y Talla ya existe en la base de datos.',
            'color_id.required' => 'El campo Color es requerido.',
            'color_id.exists' => 'El Identificador del color no es valido.',
            'tone_id.required' => 'El campo Tono es requerido.',
            'tone_id.exists' => 'El Identificador del tono no es valido.',
            'size_id.required' => 'El campo Talla es requerido.',
            'size_id.exists' => 'El Identificador de la talla no es valido.',
            'quantity.required' => 'El campo Cantidad es requerido.',
            'quantity.numeric' => 'El campo Cantidad debe ser numerico.',
            'quantity.min' => 'El valor minimo del campo Cantidad debe ser :min.',
            'quantity.max' => 'El valor maximo del campo Cantidad debe ser :max.',
            'product_color_tone.exists' => 'El Producto, Color y Tono no son validos.',
            'product_size.exists' => 'El Producto y Talla no son validos.',
        ];
    }
}
