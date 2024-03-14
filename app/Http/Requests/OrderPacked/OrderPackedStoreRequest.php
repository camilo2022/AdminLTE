<?php

namespace App\Http\Requests\OrderPacked;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderPackedStoreRequest extends FormRequest
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
            'order_dispatch_id' => ['required', 'unique:order_packings,order_dispatch_id'],
        ];
    }

    public function messages()
    {
        return [
            'order_dispatch_id.required' => 'El Identificador del detalle de la orden de despacho es requerido.',
            'order_dispatch_id.unique' => 'El Identificador del detalle de la orden de despacho ya fue tomado.',
        ];
    }
}
