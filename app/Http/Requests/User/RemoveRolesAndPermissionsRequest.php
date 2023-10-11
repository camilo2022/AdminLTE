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
            'id' => 'required|exists:users,id',
            'role' => 'required|string|exists:roles,name',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'string|exists:permissions,name',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El campo identificador unico de usuario es requerido.',
            'rol.required' => 'El campo rol es requerido.',
            'permissions.required' => 'Debe seleccionar los permisos que desea remover.',
            'array' => 'El campo :attribute debe ser un arreglo.',
            'exists' => 'El :attribute especificado no existe.',
            'unique' => 'El :attribute ya existe.',
            'string' => 'El :attribute debe ser una cadena de texto.',
            'min' => 'El :attribute debe tener minimo :min permiso seleccionado.',
        ];
    }

    public function attributes()
    {
        return [
            'id' => 'identificador unico de usuario',
            'role' => 'rol',
            'permissions' => 'permisos',
        ];
    }
}
