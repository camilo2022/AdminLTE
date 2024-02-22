<?php

namespace App\Http\Requests\OrderSeller;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderSellerUpdateRequest extends FormRequest
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
            'client_clientBranch' => $this->input('client_branch_id'),
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'client_branch_id' => ['required', 'exists:client_branches,id'],
            'sale_channel_id' => ['required', 'exists:sale_channels,id'],
            'dispatch' => ['required', 'string', 'max:255'],
            'dispatch_date' => ['required', 'date', 'after_or_equal:now'],
            'seller_observation' => ['nullable', 'string', 'max:255'],
            'client_clientBranch' => ['exists:client_branches,id,client_id,' . $this->input('client_id')],
            'payment_type_ids' => ['required', 'array'],
            'payment_type_ids.*' => ['exists:payment_types,id']
        ];
    }

    public function messages()
    {
        return [
            'client_id.required' => 'El Identificador del Cliente es requerido.',
            'client_id.exists' => 'El Identificador del cliente no es valido.',
            'client_branch_id.required' => 'El Identificador de la Sucursal del Cliente es requerido.',
            'client_branch_id.exists' => 'El Identificador de la sucursal del cliente no es valido.',
            'sale_channel_id.required' => 'El Identificador del Canal de venta es requerido.',
            'sale_channel_id.exists' => 'El Identificador del canal de venta no es valido.',
            'dispatch.required' => 'El campo Despacho es requerido.',
            'dispatch.string' => 'El campo Despacho debe ser una cadena de caracteres.',
            'dispatch.max' => 'El campo Despacho no debe exceder los 255 caracteres.',
            'dispatch_date.required' => 'El campo Fecha de despacho es requerido.',
            'dispatch_date.date' => 'El campo Fecha de despacho debe ser una fecha valida.',
            'dispatch_date.after_or_equal' => 'El campo Fecha de despacho debe ser posterior o igual a la fecha actual :now.',
            'seller_observation.string' => 'El campo Observacion del asesor debe ser una cadena de caracteres.',
            'seller_observation.max' => 'El campo Observacion del asesor no debe exceder los 255 caracteres.',
            'client_clientBranch.exists' => 'La sucursal no pertenece al cliente seleccionado.',
            'payment_type_ids.required' => 'El campo Metodos de pago es requerido.',
            'payment_type_ids.array' => 'El campo Metodos de pago debe ser un arreglo.',
            'payment_type_ids.*' => 'El Identificador del Metodo de pago #:position no es valido.'
        ];
    }
}
