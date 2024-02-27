<?php

namespace App\Http\Requests\Business;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BusinessUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:businesses,name,' . $this->route('id') .',id', 'max:255'],
            'person_type_id' => ['required', 'exists:person_types,id'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'document_number' => ['required', 'string', 'min:5', 'max:20', 'unique:businesses,document_number,' . $this->route('id') .',id'],
            'telephone_number' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:businesses,email,' . $this->route('id') .',id', 'max:255'],
            'country_id' => ['required', 'exists:countries,id'],
            'departament_id' => ['required', 'exists:departaments,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'country_departament' => ['exists:departaments,id,country_id,' . $this->input('country_id')],
            'departament_city' => ['exists:cities,id,departament_id,' . $this->input('departament_id')]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre de la empresa es requerido.',
            'name.string' => 'El campo Nombre de la empresa debe ser una cadena de caracteres.',
            'name.unique' => 'El campo Nombre de la empresa ya ha sido tomado.',
            'name.max' => 'El campo Nombre de la empresa no debe exceder los 255 caracteres.',
            'person_type_id.required' => 'El Identificador del tipo de persona es requerido.',
            'person_type_id.exists' => 'El Identificador del tipo de persona del cliente no es valido.',
            'document_type_id.required' => 'El Identificador del tipo de documento del cliente es requerido.',
            'document_type_id.exists' => 'El Identificador del tipo de documento del cliente no es valido.',
            'document_number.required' => 'El campo Numero de documento de la empresa es requerido.',
            'document_number.string' => 'El campo Numero de documento de la empresa debe ser una cadena de caracteres.',
            'document_number.unique' => 'El campo Numero de documento de la empresa ya ha sido tomado.',
            'document_number.min' => 'El campo Numero de documento de la empresa debe contener minimo 5 caracteres.',
            'document_number.max' => 'El campo Numero de documento de la empresa no debe exceder los 20 caracteres.',
            'telephone_number.required' => 'El campo Numero de telefono de la empresa es requerido.',
            'telephone_number.numeric' => 'El campo Numero de telefono de la empresa debe ser una cadena de digitos.',
            'email.required' => 'El campo Correo electronico de la empresa es requerido.',
            'email.email' => 'El campo Correo electronico de la empresa debe ser una dirección de correo electrónico válida.',
            'email.unique' => 'El campo Correo electronico de la empresa ya ha sido tomado.',
            'email.max' => 'El campo Correo electronico de la empresa no debe exceder los 255 caracteres.',
            'country_id.required' => 'El Identificador del Pais de la empresa es requerido.',
            'country_id.exists' => 'El Identificador del Pais de la empresa no existe en la base de datos.',
            'departament_id.required' => 'El Identificador del Departamento de la empresa es requerido.',
            'departament_id.exists' => 'El Identificador del Departamento de la empresa no existe en la base de datos.',
            'city_id.required' => 'El Identificador de la Ciudad de la empresa es requerido.',
            'city_id.exists' => 'El Identificador de la Ciudad de la empresa no existe en la base de datos.',
            'address.required' => 'El campo Direccion de la empresa es requerido.',
            'address.string' => 'El campo Direccion de la empresa debe ser una cadena de caracteres.',
            'address.max' => 'El campo Direccion de la empresa no debe exceder los 255 caracteres.',
            'neighborhood.required' => 'El campo Barrio de la empresa es requerido.',
            'neighborhood.string' => 'El campo Barrio de la empresa debe ser una cadena de caracteres.',
            'neighborhood.max' => 'El campo Barrio de la empresa no debe exceder los 255 caracteres.',
            'description.string' => 'El campo Descripcion de la empresa debe ser una cadena de caracteres.',
            'description.max' => 'El campo Descripcion de la empresa no debe exceder los 255 caracteres.',
            'country_departament.exists' => 'El departamento no pertenece al pais seleccionado.',
            'departament_city.exists' => 'La ciudad no pertenece al departamento seleccionado.',
        ];
    }
}
