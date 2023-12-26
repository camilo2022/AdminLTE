<?php
namespace App\Http\Requests\User;

use App\Rules\Equals;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserStoreRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación.',
            'errors' => $validator->errors()
        ], 422));
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'area_charge' => $this->input('charge_id'),
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'document_number' => ['required', 'string', 'min:5', 'max:20', 'unique:users'],
            'phone_number' => ['required', 'string', 'size:10'],
            'address' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:6', new Equals($this->input('password_confirmation'), $this->input('password'), 'Contraseña')],
            'area_id' => ['required', 'exists:areas,id'],
            'charge_id' => ['required', 'exists:charges,id'],
            'area_charge' => ['exists:charges,id,charge_id,' . $this->input('charge_id')],
        ];
    }

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
            'exists' => 'El campo :attribute no es valido.',
            'confirmed' => 'El campo :attribute no coincide con la confirmación de contraseña.',
            'country_departament.exists' => 'El cargo no pertenece al area seleccionada.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre',
            'last_name' => 'apellido',
            'document_number' => 'numero de documento',
            'phone_number' => 'numero de telefono',
            'address' => 'direccion',
            'email' => 'correo electrónico',
            'area_id' => 'Identificador del area',
            'charge_id' => 'Identificador del cargo',
            'password' => 'contraseña',
            'password_confirmation' => 'confirmación de contraseña',
        ];
    }
}
