<?php

namespace App\Http\Requests\PersonReference;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonReferenceCreateRequest extends FormRequest
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
            'country_id' => ['nullable', 'exists:countries,id'],
            'departament_id' => ['nullable', 'exists:departaments,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
        ];
    }


    public function messages()
    {
        return [
            'country_id.exists' => 'El Identificador del pais no es valido.',
            'departament_id.exists' => 'El Identificador del departamento no es valido.',
            'city_id.exists' => 'El identificador de la ciudad no es valido.',
        ];
    }
}
