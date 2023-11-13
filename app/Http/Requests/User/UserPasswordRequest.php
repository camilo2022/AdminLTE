<?php
namespace App\Http\Requests\User;

use App\Rules\Equals;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class UserPasswordRequest extends FormRequest
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
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:6', new Equals($this->input('password_confirmation'), $this->input('password'), 'Contraseña del usuario')]
        ];
    }
    // Mensajes de error personalizados para cada regla de validación
    public function messages()
    {
        return [
            'password.required' => 'El campo Contraseña del usuario es requerido.',
            'password.string' => 'El campo Contraseña del usuario debe ser una cadena de caracteres.',
            'password.min' => 'El campo Contraseña del usuario debe tener al menos 6 caracteres.',
            'password.confirmed' => 'El campo Contraseña del usuario no coincide con el campo Confirmación de contraseña del usuario.',
            'password_confirmation.required' => 'El campo Confirmacion de Contraseña del usuario es requerido.',
            'password_confirmation.string' => 'El campo Confirmacion de Contraseña del usuario debe ser una cadena de caracteres.',
            'password_confirmation.min' => 'El campo Confirmacion de contraseña del usuario debe tener al menos 6 caracteres.'
        ];
    }
    
    public function attributes()
    {
        return [
            'password' => 'Contraseña del usuario',
            'password_confirmation' => 'Confirmacion de contraseña del usuario'
        ];
    }
}
