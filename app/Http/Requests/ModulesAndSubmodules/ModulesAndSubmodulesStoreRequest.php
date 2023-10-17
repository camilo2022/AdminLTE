<?php

namespace App\Http\Requests\ModulesAndSubmodules;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ModulesAndSubmodulesStoreRequest extends FormRequest
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
            'module' => 'required|string|max:255|unique:modules,name',
            'icon' => 'required|string|max:255|unique:modules,icon',
            'roles' => 'required|array',
            'roles.*' => 'numeric|exists:roles,id|unique:module_has_roles,role_id',
            'submodules' => 'required|array',
            'submodules.*' => 'required|array',
            'submodules.*.submodule' => 'required|string|max:255|unique:submodules,name',
            'submodules.*.url' => 'required|string|max:255|unique:submodules,url',
            'submodules.*.icon' => 'required|string|max:255|unique:submodules,icon',
            'submodules.*.permission_id' => 'required|string|max:255|exists:permissions,id|unique:submodules,permission_id',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es requerido.',
            'array' => 'El campo :attribute debe ser un arreglo.',
            'string' => 'Cada elemento en :attribute debe ser una cadena de caracteres.',
            'max' => 'Cada elemento en :attribute no debe exceder los :max caracteres.',
            'unique' => 'El :attribute ya existe.',
            'numeric' => 'El campo :attribute debe ser un numero.',
        ];
    }

    public function attributes()
    {
        return [
            'module' => 'modulo',
            'icon' => 'icono del modulo',
            'roles' => 'roles de acceso',
            'roles.*' => 'identificador del rol',
            'submodules' => 'submodulos',
            'submodules.*' => 'informacion del submodulo',
            'submodules.*.submodule' => 'nombre del submodulo',
            'submodules.*.url' => 'url del submodulo',
            'submodules.*.icon' => 'icono del submodulo',
            'submodules.*.permission_id' => 'permiso de acceso',
        ];
    }
}
