<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RemoveRolesAndPermissionsRequest extends FormRequest
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
            'id' => 'required|exists:users,id',
            'role' => 'required|string|exists:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El campo identificador del usuario es requerido.',
            'id.exists' => 'El identificador del usuario no existe en la base de datos.',
            'role.required' => 'El campo rol es requerido.',
            'role.string' => 'El rol debe ser una cadena de texto.',
            'role.exists' => 'El rol no existe en la base de datos.',
            'permissions.required' => 'Debe seleccionar los permisos que desea asignar.',
            'permissions.array' => 'El campo permisos a asignar debe ser un arreglo.',
            'permissions.*.string' => 'El permiso debe ser una cadena de texto.',
            'permissions.*.exists' => 'El permiso no existe en la base de datos.'
        ];
    }
}
