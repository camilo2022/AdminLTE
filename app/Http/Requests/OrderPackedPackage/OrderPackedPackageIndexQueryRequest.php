<?php

namespace App\Http\Requests\OrderPackedPackage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderPackedPackageIndexQueryRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación.',
            'errors' => $validator->errors()
        ], 422));
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'order_packing_id' => ['required', 'exists:order_packings,id']
        ];
    }

    public function messages()
    {
        return [
            'order_packing_id.required' => 'El Identificador de la orden de alistamiento y empacado de la orden de despacho del pedido es requerido.',
            'order_packing_id.exists' => 'El Identificador de la orden de alistamiento y empacado de la orden de despacho del pedido no es válido.'
        ];
    }
}
