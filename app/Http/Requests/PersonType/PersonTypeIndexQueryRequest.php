<?php

namespace App\Http\Requests\PersonType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonTypeIndexQueryRequest extends FormRequest
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
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'perPage' => ['required', 'numeric'],
        ];
    }


    public function messages()
    {
        return [
            'start_date.required' => 'El campo Fecha de inicio es requerido.',
            'start_date.date' => 'El campo Fecha de inicio debe ser una fecha válida.',
            'end_date.date' => 'El campo Fecha de fin debe ser una fecha válida.',
            'end_date.after_or_equal' => 'El campo Fecha de fin debe ser igual o posterior a la Fecha de inicio.',
            'perPage.numeric' => 'El campo Numero de registros por página debe ser un valor numérico.',
            'perPage.required' => 'El campo Numero de registros por página es requerido.'
        ];
    }
}
