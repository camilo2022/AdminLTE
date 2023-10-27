<?php
namespace App\Http\Requests\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class UserStoreRequest extends FormRequest
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
            'message' => 'Error de validación de los datos para la creacion del usuario.',
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
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'document_number' => 'required|string|min:5|max:20|unique:users',
            'phone_number' => 'required|string|size:10',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
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
            'email' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
            'unique' => 'El campo :attribute ya ha sido tomado.',
            'max' => 'El campo :attribute no debe exceder los :max caracteres.',
            'min' => 'El campo :attribute debe tener al menos :min caracteres.',
            'size' => 'El campo :attribute debe tener :size caracteres.',
            'exists' => 'El campo :attribute no existe en la base de datos.',
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
            'name' => 'nombre',
            'last_name' => 'apellido',
            'document_number' => 'numero de documento',
            'phone_number' => 'numero de telefono',
            'address' => 'direccion',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
            'password_confirmation' => 'confirmación de contraseña',
        ];
    }
}
