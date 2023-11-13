<?php

namespace App\Http\Requests\RolesAndPermissions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RolesAndPermissionsDeleteRequest extends FormRequest
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
            'message' => 'Error de validación',
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
            'role_id' => ['required', 'array'],
            'role_id.*' => ['integer', 'exists:roles,id'], // Asegura que los id de roles existan en la tabla roles
            'permission_id' => ['required', 'array'],
            'permission_id.*' => ['integer', 'exists:permissions,id'], // Asegura que los id de permisos existan en la tabla permissions
        ];
    }

    public function messages()
    {
        return [
            'role_id.required' => 'El campo identificador del rol es requerido.',
            'role_id.array' => 'El campo identificador del rol debe ser un arreglo.',
            'role_id.integer' => 'El identificador del rol debe ser un número entero.',
            'role_id.exists' => 'El identificador del rol no existe en la base de datos.',
            'permission_id.required' => 'El campo identificador de los permisos es requerido.',
            'permission_id.array' => 'El campo identificador de los permisos debe ser un arreglo.',
            'permission_id.integer' => 'El identificador del permiso debe ser un número entero.',
            'permission_id.exists' => 'El identificador del permiso no existe en la base de datos.',
        ];
    }
}
