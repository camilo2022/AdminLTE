<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class InventoryMasiveRequest extends FormRequest
{
    /**
     * Maneja una solicitud fallida de validación.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        // Lanzar una excepción de validación con los errores de validación obtenidos
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación',
            'errors' => $validator->errors()
        ], 422));
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
            'inventories.*.product_id' => ['required', 'exists:products,id'],
            'inventories.*.size_id' => ['required', 'exists:sizes,id'],
            'inventories.*.warehouse_id' => ['required', 'exists:warehouses,id'],
            'inventories.*.color_id' => ['required', 'exists:colors,id'],
            'inventories.*.tone_id' => ['required', 'exists:tones,id'],
            'inventories.*.quantity' => ['required', 'numeric'],
        ];
    }

    public function messages()
    {
        return [
            'inventories.*.product_id.required' => 'El campo Identificador del producto es requerido.',
            'inventories.*.product_id.exists' => 'El Identificador del producto no es valido.',
            'inventories.*.size_id.required' => 'El campo Identificador de la talla es requerido.',
            'inventories.*.size_id.exists' => 'El Identificador de la talla no es valido.',
            'inventories.*.warehouse_id.required' => 'El campo Identificador de la bodega es requerido.',
            'inventories.*.warehouse_id.exists' => 'El Identificador de la bodega no es valido.',
            'inventories.*.color_id.required' => 'El campo Identificador del color es requerido.',
            'inventories.*.color_id.exists' => 'El Identificador del color no es valido.',
            'inventories.*.tone_id.required' => 'El campo Identificador del tono es requerido.',
            'inventories.*.tone_id.exists' => 'El Identificador del tono no es valido.',
            'inventories.*.quantity.required' => 'El campo Cantidad de inventario es requerido.',
            'inventories.*.quantity.numeric' => 'El campo Cantidad de inventario debe ser un valor numerico.',
        ];
    }
}
