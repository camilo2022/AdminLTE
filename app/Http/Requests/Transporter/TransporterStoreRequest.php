<?php

namespace App\Http\Requests\Transporter;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransporterStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
            'name' => ['required', 'string', 'unique:transporters,name', 'max:255'],
            'document_number' => ['required', 'string', 'min:5', 'max:20', 'unique:transporters,document_number'],
            'telephone_number' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'unique:transporters,email', 'max:255'],
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre de la transportadora es requerido.',
            'name.string' => 'El campo Nombre de la transportadora debe ser una cadena de caracteres.',
            'name.unique' => 'El campo Nombre de la transportadora ya ha sido tomado.',
            'name.max' => 'El campo Nombre de la transportadora no debe exceder los 255 caracteres.',
            'document_number.required' => 'El campo Numero de documento de la transportadora es requerido.',
            'document_number.string' => 'El campo Numero de documento de la transportadora debe ser una cadena de caracteres.',
            'document_number.unique' => 'El campo Numero de documento de la transportadora ya ha sido tomado.',
            'document_number.min' => 'El campo Numero de documento de la transportadora debe contener minimo 5 caracteres.',
            'document_number.max' => 'El campo Numero de documento de la transportadora no debe exceder los 20 caracteres.',
            'telephone_number.required' => 'El campo Numero de telefono de la transportadora es requerido.',
            'telephone_number.string' => 'El campo Numero de telefono de la transportadora debe ser una cadena de digitos.',
            'email.required' => 'El campo Correo electronico de la transportadora es requerido.',
            'email.string' => 'El campo Correo electronico de la transportadora debe ser una cadena de caracteres.',
            'email.email' => 'El campo Correo electronico de la transportadora debe ser una dirección de correo electrónico válida.',
            'email.unique' => 'El campo Correo electronico de la transportadora ya ha sido tomado.',
            'email.max' => 'El campo Correo electronico de la transportadora no debe exceder los 255 caracteres.'
        ];
    }
}
