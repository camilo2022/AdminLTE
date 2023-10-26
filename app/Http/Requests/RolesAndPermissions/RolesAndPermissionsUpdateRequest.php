<?php
namespace App\Http\Requests\RolesAndPermissions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class RolesAndPermissionsUpdateRequest extends FormRequest
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
            'role' => 'required|string|max:255|unique:roles,name,' . $this->route('id') .',id',
            'permissions' => 'required|array',
            'permissions.*' => 'string|max:255',
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
        ];
    }

    public function attributes()
    {
        return [
            'role' => 'Rol',
            'permissions' => 'Permisos',
            'permissions.*' => 'Elemento en Permisos',
        ];
    }
}
