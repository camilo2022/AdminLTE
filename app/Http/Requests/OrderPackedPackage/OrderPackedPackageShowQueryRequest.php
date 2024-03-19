<?php

namespace App\Http\Requests\OrderPackedPackage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderPackedPackageShowQueryRequest extends FormRequest
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
            'order_package_id' => ['required', 'exists:order_packages,id']
        ];
    }

    public function messages()
    {
        return [
            'order_package_id.required' => 'El Identificador del empaque es requerido.',
            'order_package_id.exists' => 'El Identificador del empaque no es válido.'
        ];
    }
}
