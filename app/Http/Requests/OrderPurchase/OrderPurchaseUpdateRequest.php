<?php

namespace App\Http\Requests\OrderPurchase;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderPurchaseUpdateRequest extends FormRequest
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
            'workshop_id' => ['required', 'exists:workshops,id'],
            'purchase_observation' => ['nullable', 'string', 'max:255'],
            'payment_type_ids' => ['required', 'array'],
            'payment_type_ids.*' => ['exists:payment_types,id']
        ];
    }

    public function messages()
    {
        return [
            'workshop_id.required' => 'El Identificador del Taller es requerido.',
            'workshop_id.exists' => 'El Identificador del Taller no es valido.',
            'purchase_observation.string' => 'El campo Observacion debe ser una cadena de caracteres.',
            'purchase_observation.max' => 'El campo Observacion no debe exceder los 255 caracteres.',
            'payment_type_ids.required' => 'El campo Metodos de pago es requerido.',
            'payment_type_ids.array' => 'El campo Metodos de pago debe ser un arreglo.',
            'payment_type_ids.*' => 'El Identificador del Metodo de pago #:position no es valido.'
        ];
    }
}
