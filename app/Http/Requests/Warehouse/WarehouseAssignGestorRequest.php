<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class WarehouseAssignGestorRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'warehouse_user' => $this->input('warehouse_id'),
        ]);
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
            'user_id' => ['required', 'exists:users,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'warehouse_user' => ['unique:warehouse_users,warehouse_id,NULL,id,user_id,' . $this->input('user_id')]
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'El Identificador del usuario es requerido.',
            'user_id.exists' => 'El Identificador del usuario no es valido.',
            'warehouse_id.required' => 'El Identificador de la bodega es requerido.',
            'warehouse_id.exists' => 'El Identificador de la bodega no es valido.',
            'warehouse_user.unique' => 'La relacion del Identificador de la bodega y el Identificador del usuario ya ha sido tomado.'
        ];
    }
}
