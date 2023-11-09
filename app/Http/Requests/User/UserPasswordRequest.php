<?php
namespace App\Http\Requests\User;
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
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string'
        ];
    }
    // Mensajes de error personalizados para cada regla de validación
    public function messages()
    {
        return [
            'required' => 'El campo :attribute es requerido.',
            'string' => 'El campo :attribute debe ser una cadena de caracteres.',
            'min' => 'El campo :attribute debe tener al menos :min caracteres.',
            'confirmed' => 'El campo :attribute no coincide con la confirmación de contraseña.',
        ];
    }
    /**
     * Obtiene los atributos personalizados de los campos.
     *
     * @return array
     */
    public function attributes()
    {
        // Nombres personalizados para cada campo de la solicitud
        return [
            'password' => 'contraseña',
            'password_confirmation' => 'confirmación de contraseña',
        ];
    }
}
