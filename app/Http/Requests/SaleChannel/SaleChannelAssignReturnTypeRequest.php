<?php

namespace App\Http\Requests\SaleChannel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaleChannelAssignReturnTypeRequest extends FormRequest
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
            'sale_channel_return_type' => $this->input('sale_channel_id'),
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'return_type_id' => ['required', 'exists:return_types,id'],
            'sale_channel_id' => ['required', 'exists:sale_channels,id'],
            'sale_channel_return_type' => ['unique:sale_channel_return_types,sale_channel_id,NULL,id,return_type_id,' . $this->input('return_type_id')]
        ];
    }

    public function messages()
    {
        return [
            'return_type_id.required' => 'El Identificador del tipo de devolucion es requerido.',
            'return_type_id.exists' => 'El Identificador del tipo de devolucion no es valido.',
            'sale_channel_id.required' => 'El Identificador del canal de venta es requerido.',
            'sale_channel_id.exists' => 'El Identificador del canal de venta no es valido.',
            'sale_channel_return_type.unique' => 'La relacion del Identificador del canal de venta y el Identificador del tipo de devolucion ya ha sido tomado.'
        ];
    }
}
