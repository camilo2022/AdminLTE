<?php

namespace App\Http\Requests\ModulesAndSubmodules;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ModulesAndSubmodulesUpdateRequest extends FormRequest
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
            'module' => ['required', 'string', 'max:255', 'unique:modules,name,' . $this->route('id') . ',id'],
            'icon' => ['required', 'string', 'max:255', 'unique:modules,icon,' . $this->route('id') . ',id'],
            'roles' => ['required', 'array'],
            'roles.*' => ['numeric', 'exists:roles,id', 'unique:model_has_roles,role_id,' . $this->route('id') . ',model_id,model_type,App\Models\Module'],
            'submodules' => ['required', 'array'],
            'submodules.*' => ['required', 'array'],
            'submodules.*.submodule' => ['required', 'string', 'max:255', 'unique:submodules,name,' . $this->route('id') . ',module_id'],
            'submodules.*.url' => ['required', 'string', 'max:255', 'unique:submodules,url,' . $this->route('id') . ',module_id'],
            'submodules.*.icon' => ['required', 'string', 'max:255', 'unique:submodules,icon,' . $this->route('id') . ',module_id'],
            'submodules.*.permission_id' => ['required', 'max:255', 'exists:permissions,id', 'unique:submodules,permission_id,' . $this->route('id') . ',module_id'],
        ];
    }

    public function messages()
    {
        return [
            'module.required' => 'El campo Nombre del modulo es requerido.',
            'module.string' => 'El campo Nombre del modulo debe ser una cadena de caracteres.',
            'module.max' => 'El campo Nombre del modulo no debe exceder los 255 caracteres.',
            'module.unique' => 'El campo Nombre del modulo ya existe en la base de datos.',
            'icon.required' => 'El campo Icono del modulo es requerido.',
            'icon.string' => 'El campo Icono del modulo debe ser una cadena de caracteres.',
            'icon.max' => 'El campo Icono del modulo no debe exceder los 255 caracteres.',
            'icon.unique' => 'El campo Icono del modulo ya existe en la base de datos.',
            'roles.required' => 'El campo Roles de acceso es requerido.',
            'roles.array' => 'El campo Roles de acceso debe ser un arreglo.',
            'roles.*.numeric' => 'El identificador del rol de acceso debe ser numerico.',
            'roles.*.exists' => 'El identificador del rol de acceso no es valido.',
            'roles.*.unique' => 'El identificador del rol de acceso ya ha sido tomado.',
            'submodules.required' => 'El campo Submodulos es requerido.',
            'submodules.array' => 'El campo Submodulos debe ser un arreglo.',
            'submodules.*.required' => 'La Informacion del submodulo es requerido.',
            'submodules.*.array' => 'La Informacion del submodulo debe ser un arreglo.',
            'submodules.*.submodule.required' => 'El campo Nombre del submodulo es requerido.',
            'submodules.*.submodule.string' => 'El campo Nombre del submodulo debe ser una cadena de caracteres.',
            'submodules.*.submodule.max' => 'El campo Nombre del submodulo no debe exceder los 255 caracteres.',
            'submodules.*.submodule.unique' => 'El campo Nombre del submodulo ya ha sido tomado.',
            'submodules.*.url.required' => 'El campo Url del submodulo es requerido.',
            'submodules.*.url.string' => 'El campo Url del submodulo debe ser una cadena de caracteres.',
            'submodules.*.url.max' => 'El campo Url del submodulo no debe exceder los 255 caracteres.',
            'submodules.*.url.unique' => 'El campo Url del submodulo ya ha sido tomado.',
            'submodules.*.icon.required' => 'El campo Icono del submodulo es requerido.',
            'submodules.*.icon.string' => 'El campo Icono del submodulo debe ser una cadena de caracteres.',
            'submodules.*.icon.max' => 'El campo Icono del submodulo no debe exceder los 255 caracteres.',
            'submodules.*.icon.unique' => 'El campo Icono del submodulo ya ha sido tomado.',
            'submodules.*.permission_id.required' => 'El campo Permiso de acceso del submodulo es requerido.',
            'submodules.*.permission_id.max' => 'El campo Permiso de acceso del submodulo no debe exceder los 255 caracteres.',
            'submodules.*.permission_id.exists' => 'El Identificador del Permiso de acceso del submodulo no es valido.',
            'submodules.*.permission_id.unique' => 'El campo Permiso de acceso del submodulo ya ha sido tomado.',
        ];
    }
}
