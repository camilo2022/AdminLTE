<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClientUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'person_type_id' => ['required', 'exists:person_types,id'],
            'client_type_id' => ['required', 'exists:client_types,id'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'document_number' => ['required', 'string', 'min:5', 'max:20', 'unique:clients,document_number,' . $this->route('id') .',id'],
            'country_id' => ['required', 'exists:countries,id'],
            'departament_id' => ['required', 'exists:departaments,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'telephone_number_first' => ['required', 'string'],
            'telephone_number_second' => ['nullable', 'string'],
            'country_departament' => ['exists:departaments,id,country_id,' . $this->input('country_id')],
            'departament_city' => ['exists:cities,id,departament_id,' . $this->input('departament_id')]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre del cliente es requerido.',
            'name.string' => 'El campo Nombre del cliente debe ser una cadena de caracteres.',
            'name.max' => 'El campo Nombre del cliente no debe exceder los 255 caracteres.',
            'person_type_id.required' => 'El Identificador del Tipo de persona es requerido.',
            'person_type_id.exists' => 'El Identificador del tipo de persona del cliente no es valido.',
            'client_type_id.required' => 'El Identificador del Tipo de cliente es requerido.',
            'client_type_id.exists' => 'El Identificador del tipo de cliente no es valido.',
            'document_type_id.required' => 'El Identificador del Tipo de documento del cliente es requerido.',
            'document_type_id.exists' => 'El Identificador del tipo de documento del cliente no es valido.',
            'document_number.required' => 'El campo Numero de documento del cliente es requerido.',
            'document_number.string' => 'El campo Numero de documento del cliente debe ser una cadena de caracteres.',
            'document_number.unique' => 'El Numero de documento ya ha sido tomado.',
            'document_number.min' => 'El campo Numero de documento del cliente debe contener minimo 5 caracteres.',
            'document_number.max' => 'El campo Numero de documento del cliente no debe exceder los 20 caracteres.',
            'country_id.required' => 'El campo Pais del cliente es requerido.',
            'country_id.exists' => 'El Identificador del pais no es valido.',
            'departament_id.required' => 'El Identificador del Departamento del cliente es requerido.',
            'departament_id.exists' => 'El Identificador del departamento no es valido.',
            'city_id.required' => 'El Identificador de la Ciudad del cliente es requerido.',
            'city_id.exists' => 'El Identificador de la ciudad es valido.',
            'address.required' => 'El campo Direccion del cliente es requerido.',
            'address.string' => 'El campo Direccion del cliente debe ser una cadena de caracteres.',
            'address.max' => 'El campo Direccion del cliente no debe exceder los 255 caracteres.',
            'neighborhood.required' => 'El campo Barrio del cliente es requerido.',
            'neighborhood.string' => 'El campo Barrio del cliente debe ser una cadena de caracteres.',
            'neighborhood.max' => 'El campo Barrio del cliente no debe exceder los 255 caracteres.',
            'email.required' => 'El campo Correo electronico del cliente es requerido.',
            'email.email' => 'El campo Correo electronico del cliente debe ser una dirección de correo electrónico válida.',
            'email.max' => 'El campo Correo electronico del cliente no debe exceder los 255 caracteres.',
            'telephone_number_first.required' => 'El campo Numero de telefono del cliente es requerido.',
            'telephone_number_first.string' => 'El campo Numero de telefono del cliente debe ser una cadena de digitos.',
            'telephone_number_second.string' => 'El campo Numero de telefono del cliente debe ser una cadena de digitos.',
            'country_departament.exists' => 'El departamento no pertenece al pais seleccionado.',
            'departament_city.exists' => 'La ciudad no pertenece al departamento seleccionado.',
        ];
    }
}
