<?php

namespace App\Http\Requests\OrderPackedPackage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderPackedPackageDeleteRequest extends FormRequest
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
            'id' => ['required', 'exists:order_packages,id']
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El Identificador del empaque de la orden de alistamiento y empacado es requerido.',
            'id.exists' => 'El Identificador del empaque de la orden de alistamiento y empacado no es válido.',
        ];
    }
}
