<?php

namespace App\Http\Requests\Transfer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransferCancelRequest extends FormRequest
{
    /**
     * Maneja una solicitud fallida de validación.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Validation\ValidationException
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
            'id' => ['required', 'exists:transfers,id'],
            'to_observation' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El Identificador de la transferencia es requerido.',
            'id.exists' => 'El Identificador de la transferencia no es válido.',
            'to_observation.string' => 'El campo Observacion del usuario que recibe debe ser una cadena de texto.',
            'to_observation.max' => 'El campo Observacion del usuario que recibe no debe tener mas de 255 caracteres.',
        ];
    }
}
