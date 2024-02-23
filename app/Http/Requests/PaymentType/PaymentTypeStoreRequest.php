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

    protected function prepareForValidation()
    {
        $this->merge([
            'require_banks' => $this->input('require_banks') === 'true',
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:payment_types,name'],
            'require_banks' => ['required', 'boolean']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre del tipo de pago es requerido.',
            'name.string' => 'El campo Nombre del tipo de pago debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre del tipo de pago ya existe en la base de datos.',
            'require_banks.required' => 'El campo Requiere bancos es requerido.',
            'require_banks.boolean' => 'El campo Requiere bancos debe ser true o false.',
        ];
    }
}
