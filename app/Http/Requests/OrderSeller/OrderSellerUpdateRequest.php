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
            'dispatch' => ['required', 'string', 'max:255'],
            'dispatch_date' => ['required', 'date', 'after_or_equal:now'],
            'seller_observation' => ['nullable', 'string', 'max:255'],
            'client_clientBranch' => ['exists:client_branches,id,client_id,' . $this->input('client_id')]
        ];
    }

    public function messages()
    {
        return [
            'client_id.required' => 'El campo Cliente es requerido.',
            'client_id.exists' => 'El Identificador del cliente no es valido.',
            'client_branch_id.required' => 'El campo Sucursal del Cliente es requerido.',
            'client_branch_id.exists' => 'El Identificador de la sucursal del cliente no es valido.',
            'dispatch.required' => 'El campo Despacho es requerido.',
            'dispatch.string' => 'El campo Despacho debe ser una cadena de caracteres.',
            'dispatch.max' => 'El campo Despacho no debe exceder los 255 caracteres.',
            'dispatch_date.required' => 'El campo Fecha de despacho es requerido.',
            'dispatch_date.date' => 'El campo Fecha de despacho debe ser una fecha valida.',
            'dispatch_date.after_or_equal' => 'El campo Fecha de despacho debe ser posterior o igual a la fecha actual :now.',
            'seller_observation.string' => 'El campo Observacion del asesor debe ser una cadena de caracteres.',
            'seller_observation.max' => 'El campo Observacion del asesor no debe exceder los 255 caracteres.',
            'client_clientBranch.exists' => 'La sucursal no pertenece al cliente seleccionado.'
        ];
    }
}
