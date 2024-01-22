<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class WarehouseStoreRequest extends FormRequest
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
        $this->merge([
            'to_discount' => $this->input('to_discount') === 'true',
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:warehouses,name', 'max:255'],
            'code' => ['required', 'string', 'unique:warehouses,code', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'to_discount' => ['required', 'boolean', Rule::when($this->input('to_discount'), 'unique:warehouses,to_discount')]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre de la bodega es requerido.',
            'name.string' => 'El campo Nombre de la bodega debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre de la bodega ya existe en la base de datos.',
            'name.max' => 'El campo Nombre de la bodega no debe exceder los 255 caracteres.',
            'code.required' => 'El campo Codigo de la bodega es requerido.',
            'code.string' => 'El campo Codigo de la bodega debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo de la bodega ya existe en la base de datos.',
            'code.max' => 'El campo Codigo de la bodega no debe exceder los 255 caracteres.',
            'description.string' => 'El campo Descripcion de la bodega debe ser una cadena de texto.',
            'description.max' => 'El campo Descripcion de la bodega no debe exceder los 255 caracteres.',
            'to_discount.required' => 'El campo Descontar de la bodega es requerido.',
            'to_discount.boolean' => 'El campo Descontar de la bodega debe ser true o false.',
            'to_discount.unique' => 'El campo Descontar de la bodega ya fue tomado.',
        ];
    }
}
