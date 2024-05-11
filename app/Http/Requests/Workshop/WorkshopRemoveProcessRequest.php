<?php

namespace App\Http\Requests\Workshop;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class WorkshopRemoveProcessRequest extends FormRequest
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
            'process_id' => ['required', 'exists:processes,id']
        ];
    }

    public function messages()
    {
        return [
            'workshop_id.required' => 'El campo Taller es requerido.',
            'workshop_id.exists' => 'El Identificador del taller no es valido.',
            'process_id.required' => 'El campo Proceso del taller es requerido.',
            'process_id.exists' => 'El Identificador del proceso no es valido.'
        ];
    }
}
