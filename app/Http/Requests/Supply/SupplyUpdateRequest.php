<?php

namespace App\Http\Requests\Supply;

use App\Models\SupplyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class SupplyUpdateRequest extends FormRequest
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
        $supply_type = SupplyType::find($this->input('supply_type_id'));
        $this->merge([
            'is_cloth' => $supply_type ? $supply_type->is_cloth : false,
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'supply_type_id' => ['required', 'exists:supply_types,id'],
            'cloth_type_id' => [$this->input('is_cloth') ? 'required' : 'nullable', 'exists:cloth_types,id'],
            'cloth_composition_id' => [$this->input('is_cloth') ? 'required' : 'nullable', 'exists:cloth_compositions,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'numeric'],
            'quality' => ['required', Rule::in(['N/A', 'BAJO', 'MEDIO', 'ALTO'])],
            'width' => [$this->input('is_cloth') ? 'required' : 'nullable', 'numeric'],
            'length' => [$this->input('is_cloth') ? 'required' : 'nullable', 'numeric'],
            'measurement_unit_id' => ['required', 'exists:measurement_units,id'],
            'color_id' => ['required', 'exists:colors,id'],
            'trademark_id' => ['required', 'exists:trademarks,id'],
            'price_with_vat' => ['required', 'numeric'],
            'price_without_vat' => ['required', 'numeric']
        ];
    }

    public function messages()
    {
        return [
            'supplier_id.required' => 'El campo Proveedor es requerido.',
            'supplier_id.exists' => 'El Identificador del proveedor no es valido.',
            'supply_type_id.required' => 'El campo Tipo de insumo es requerido.',
            'supply_type_id.exists' => 'El Identificador del tipo de insumo no es valido.',
            'cloth_type_id.required' => 'El campo Tipo de tela es requerido.',
            'cloth_type_id.exists' => 'El Identificador del tipo de tela no es valido.',
            'cloth_composition_id.required' => 'El campo Composicion de tela es requerido.',
            'cloth_composition_id.exists' => 'El Identificador de la composicion de tela no es valido.',
            'name.required' => 'El campo Nombre del insumo es requerido.',
            'name.string' => 'El campo Nombre del insumo debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre del insumo ya existe en la base de datos.',
            'name.max' => 'El campo Nombre del insumo no debe tener mas de 255 caracteres.',
            'code.required' => 'El campo Codigo del insumo es requerido.',
            'code.string' => 'El campo Codigo del insumo debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo del insumo ya existe en la base de datos.',
            'code.max' => 'El campo Nombre del insumo no debe tener mas de 255 caracteres.',
            'description.string' => 'El campo Descripcion del insumo debe ser una cadena de texto.',
            'description.max' => 'El campo Descripcion del insumo no debe tener mas de 255 caracteres.',
            'quantity.required' => 'El campo Cantidad del insumo es requerido.',
            'quantity.numeric' => 'El campo Cantidad del insumo debe ser numerico.',
            'quality.required' => 'El campo Calidad del insumo es requerido.',
            'quality.in' => 'El campo Calidad del insumo no es valido.',
            'width.required' => 'El campo Ancho del rollo es requerido.',
            'width.numeric' => 'El campo Ancho del rollo debe ser numerico.',
            'length.required' => 'El campo Largo del rollo es requerido.',
            'length.numeric' => 'El campo Largo del rollo debe ser numerico.',
            'measurement_unit_id.required' => 'El campo Unidad de medida es requerido.',
            'measurement_unit_id.exists' => 'El Identificador de la unidad de medida no es valido.',
            'color_id.required' => 'El campo Color del insumo es requerido.',
            'color_id.exists' => 'El Identificador del Color del insumo no es valido.',
            'trademark_id.required' => 'El campo Marca del insumo es requerido.',
            'trademark_id.exists' => 'El Identificador de la marca del insumo no es valido.',
            'price_with_vat.required' => 'El campo Precio con iva del insumo es requerido.',
            'price_with_vat.numeric' => 'El campo Precio con iva del insumo debe ser numerico.',
            'price_without_vat.required' => 'El campo Precio sin iva del insumo es requerido.',
            'price_without_vat.numeric' => 'El campo Precio sin iva del insumo debe ser numerico.'
        ];
    }
}
