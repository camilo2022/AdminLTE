<?php

namespace App\Http\Requests\Workshop;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class WorkshopRemoveAccountRequest extends FormRequest
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
            'account_id' => ['required', 'exists:accounts,id'],
        ];
    }

    public function messages()
    {
        return [
            'account_id.required' => 'El campo Cuenta del taller es requerido.',
            'account_id.exists' => 'El Identificador de la cuenta del taller no es valido.'
        ];
    }
}
