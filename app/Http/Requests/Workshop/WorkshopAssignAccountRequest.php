<?php

namespace App\Http\Requests\Workshop;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class WorkshopAssignAccountRequest extends FormRequest
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
            'account' => ['required', 'string', 'min:5', 'max:40', 'unique:accounts,account'],
            'bank_id' => ['required', 'exists:banks,id'],
        ];
    }

    public function messages()
    {
        return [
            'workshop_id.required' => 'El campo Taller es requerido.',
            'workshop_id.exists' => 'El Identificador del taller no es valido.',
            'account.required' => 'El campo Numero de cuenta del taller es requerido.',
            'account.string' => 'El campo Numero de cuenta del taller debe ser una cadena de caracteres.',
            'account.unique' => 'El Numero de cuenta del taller ya ha sido tomado.',
            'account.min' => 'El campo Numero de cuenta del taller debe contener minimo 5 caracteres.',
            'account.max' => 'El campo Numero de cuenta del taller no debe exceder los 40 caracteres.',
            'bank_id.required' => 'El campo Banco del taller es requerido.',
            'bank_id.exists' => 'El Identificador del banco no es valido.',
        ];
    }
}
