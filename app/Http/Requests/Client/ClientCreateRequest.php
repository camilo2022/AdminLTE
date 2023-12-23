<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClientCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function failedValidation(Validator $validator)
    {
        // Lanzar una excepción de validación con los errores de validación obtenidos
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación.',
            'errors' => $validator->errors()
        ], 422));
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
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
