<?php

namespace App\Http\Requests\ClientBranch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClientBranchDeleteRequest extends FormRequest
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
            'id' => ['required', 'exists:client_branches,id'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El Identificador de la sucursal del cliente es requerido.',
            'id.exists' => 'El Identificador de la sucursal del cliente no es válido.',
        ];
    }
}
