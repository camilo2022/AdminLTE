<?php

namespace App\Http\Requests\AreasAndCharges;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AreasAndChargesDeleteRequest extends FormRequest
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
            'id' => ['required', 'exists:areas,id']
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El Identificador del area es requerido.',
            'id.integer' => 'El Identificador del area no es valido.'
        ];
    }
}
