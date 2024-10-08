<?php

namespace App\Http\Requests\Transfer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransferUpdateRequest extends FormRequest
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
            'to_warehouse_id' => ['required', 'exists:warehouses,id'],
            'from_observation' => ['nullable', 'string', 'max:255']
        ];
    }


    public function messages()
    {
        return [
            'to_warehouse_id.required' => 'El Identificador de la Bodega recibe es requerido.',
            'to_warehouse_id.exists' => 'El Identificador de la bodega que recibe no es valido.',
            'from_observation.string' => 'El campo Observacion del usuario que envia debe ser una cadena de texto.',
            'from_observation.max' => 'El campo Observacion del usuario que envia no debe tener mas de 255 caracteres.',
        ];
    }
}
