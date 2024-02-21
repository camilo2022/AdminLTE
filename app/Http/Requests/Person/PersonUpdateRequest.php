<?php

namespace App\Http\Requests\Person;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonUpdateRequest extends FormRequest
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
            'country_departament' => $this->input('departament_id'),
            'departament_city' => $this->input('city_id'),
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'client_id' => ['required', 'exists:clients,id', 'unique:people,model_id,' . $this->route('id') .',id,model_type,' . Client::class],
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'document_number' => ['required', 'string', 'min:5', 'max:20', 'unique:people,document_number,' . $this->route('id') .',id'],
            'country_id' => ['required', 'exists:countries,id'],
            'departament_id' => ['required', 'exists:departaments,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'telephone_number_first' => ['required', 'string', 'size:10'],
            'telephone_number_second' => ['nullable', 'string', 'size:10'],
            'country_departament' => ['exists:departaments,id,country_id,' . $this->input('country_id')],
            'departament_city' => ['exists:cities,id,departament_id,' . $this->input('departament_id')]
        ];
    }

    public function messages()
    {
        return [
            'client_id.required' => 'El Identificador del Cliente es requerido.',
            'client_id.exists' => 'El Identificador del cliente no es valido.',
            'client_id.unique' => 'El Identificador del cliente ya ha sido tomado.',
            'name.required' => 'El campo Nombres de la persona es requerido.',
            'name.string' => 'El campo Nombres de la persona debe ser una cadena de caracteres.',
            'name.max' => 'El campo Nombres de la persona no debe exceder los 255 caracteres.',
            'last_name.required' => 'El campo Apellidos de la persona es requerido.',
            'last_name.string' => 'El campo Apellidos de la persona debe ser una cadena de caracteres.',
            'last_name.max' => 'El campo Apellidos de la persona no debe exceder los 255 caracteres.',
            'document_type_id.required' => 'El Identificador del Tipo de documento de la persona es requerido.',
            'document_type_id.exists' => 'El Identificador del tipo de documento de la persona no es valido.',
            'document_number.required' => 'El campo Numero de documento de la persona es requerido.',
            'document_number.string' => 'El campo Numero de documento de la persona debe ser una cadena de caracteres.',
            'document_number.unique' => 'El Numero de documento ya ha sido tomado.',
            'document_number.min' => 'El campo Numero de documento de la persona debe contener minimo 5 caracteres.',
            'document_number.max' => 'El campo Numero de documento de la persona no debe exceder los 20 caracteres.',
            'country_id.required' => 'El Identificador del Pais de la persona es requerido.',
            'country_id.exists' => 'El Identificador del pais no es valido.',
            'departament_id.required' => 'El Identificador del Departamento de la persona es requerido.',
            'departament_id.exists' => 'El Identificador del departamento no es valido.',
            'city_id.required' => 'El Identificador de la Ciudad de la persona es requerido.',
            'city_id.exists' => 'El Identificador de la ciudad es valido.',
            'address.required' => 'El campo Direccion de la persona es requerido.',
            'address.string' => 'El campo Direccion de la persona debe ser una cadena de caracteres.',
            'address.max' => 'El campo Direccion de la persona no debe exceder los 255 caracteres.',
            'neighbourhood.required' => 'El campo Barrio de la persona es requerido.',
            'neighbourhood.string' => 'El campo Barrio de la persona debe ser una cadena de caracteres.',
            'neighbourhood.max' => 'El campo Barrio de la persona no debe exceder los 255 caracteres.',
            'email.required' => 'El campo Correo electronico de la persona es requerido.',
            'email.email' => 'El campo Correo electronico de la persona debe ser una dirección de correo electrónico válida.',
            'email.max' => 'El campo Correo electronico de la persona no debe exceder los 255 caracteres.',
            'telephone_number_first.required' => 'El campo Numero de telefono de la persona es requerido.',
            'telephone_number_first.string' => 'El campo Numero de telefono de la persona debe ser una cadena de digitos.',
            'telephone_number_first.size' => 'El campo Numero de telefono de la persona debe tener 10 caracteres.',
            'telephone_number_second.string' => 'El campo Numero de telefono de la persona debe ser una cadena de digitos.',
            'telephone_number_second.size' => 'El campo Numero de telefono de la persona debe tener 10 caracteres.',
            'country_departament.exists' => 'El departamento no pertenece al pais seleccionado.',
            'departament_city.exists' => 'La ciudad no pertenece al departamento seleccionado.',
        ];
    }
}
