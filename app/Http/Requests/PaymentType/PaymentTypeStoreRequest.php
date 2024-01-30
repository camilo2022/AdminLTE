<?php

namespace App\Http\Requests\PaymentType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentTypeStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:payment_types,name'],
            'code' => ['required', 'string', 'unique:payment_types,code']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre del tipo de pago es requerido.',
            'name.string' => 'El campo Nombre del tipo de pago debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre del tipo de pago ya existe en la base de datos.',
            'code.required' => 'El campo Codigo del tipo de pago es requerido.',
            'code.string' => 'El campo Codigo del tipo de pago debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo del tipo de pago ya existe en la base de datos.'
        ];
    }
}
