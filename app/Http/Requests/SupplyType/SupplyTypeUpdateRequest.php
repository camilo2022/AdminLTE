<?php

namespace App\Http\Requests\SupplyType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class SupplyTypeUpdateRequest extends FormRequest
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
        $this->merge([
            'is_cloth' => $this->input('is_cloth') === 'true',
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:supply_types,name,' . $this->route('id') .',id', 'max:255'],
            'code' => ['required', 'string', 'unique:supply_types,code,' . $this->route('id') .',id', 'max:255'],
            'is_cloth' => ['required', 'boolean', Rule::when($this->input('is_cloth'), 'unique:supply_types,is_cloth,' . $this->route('id') . ',id')]
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre del tipo de insumo es requerido.',
            'name.string' => 'El campo Nombre del tipo de insumo debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre del tipo de insumo ya existe en la base de datos.',
            'name.max' => 'El campo Nombre del tipo de insumo no debe exceder los 255 caracteres.',
            'code.required' => 'El campo Codigo del tipo de insumo es requerido.',
            'code.string' => 'El campo Codigo del tipo de insumo debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo del tipo de insumo ya existe en la base de datos.',
            'code.max' => 'El campo Codigo del tipo de insumo no debe exceder los 255 caracteres.',
            'is_cloth.required' => 'El campo Es tela del tipo de insumo es requerido.',
            'is_cloth.boolean' => 'El campo Es tela del tipo de insumo debe ser true o false.',
            'is_cloth.unique' => 'El campo Es tela del tipo de insumo ya fue tomado.',
        ];
    }
}
