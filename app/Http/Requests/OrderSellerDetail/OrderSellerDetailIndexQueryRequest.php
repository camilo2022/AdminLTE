<?php

namespace App\Http\Requests\OrderSellerDetail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderSellerDetailIndexQueryRequest extends FormRequest
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
            'order_id' => ['nullable', 'exists:orders,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'perPage' => ['nullable', 'numeric'],
        ];
    }

    public function messages()
    {
        return [
            'order_id.required' => 'El Identificador del pedido es requerido.',
            'order_id.exists' => 'El Identificador del pedidp no es válido.',
            'start_date.required' => 'El campo Fecha de inicio es requerido.',
            'start_date.date' => 'El campo Fecha de inicio debe ser una fecha válida.',
            'end_date.date' => 'El campo Fecha de fin debe ser una fecha válida.',
            'end_date.after_or_equal' => 'El campo Fecha de fin debe ser igual o posterior a la Fecha de inicio.',
            'perPage.numeric' => 'El campo Numero de registros por página debe ser un valor numérico.',
            'perPage.required' => 'El campo Numero de registros por página es requerido.'
        ];
    }
}
