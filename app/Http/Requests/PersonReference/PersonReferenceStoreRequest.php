<?php

namespace App\Http\Requests\PersonReference;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonReferenceStoreRequest extends FormRequest
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
            'person_id' => ['required', 'exists:people,id'],
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'document_number' => ['required', 'string', 'min:5', 'max:20', 'unique:people,document_number'],
            'country_id' => ['required', 'exists:countries,id'],
            'departament_id' => ['required', 'exists:departaments,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'telephone_number_first' => ['required', 'numeric', 'size:10'],
            'telephone_number_second' => ['nullable', 'numeric', 'size:10'],
            'country_departament' => ['exists:departaments,id,country_id,' . $this->input('country_id')],
            'departament_city' => ['exists:cities,id,departament_id,' . $this->input('departament_id')]
        ];
    }

    public function messages()
    {
        return [
            'person_id.required' => 'El Identificador de la Persona es requerido.',
            'person_id.exists' => 'El Identificador de la persona no es valido.',
            'name.required' => 'El campo Nombres de la referencia personal es requerido.',
            'name.string' => 'El campo Nombres de la referencia personal debe ser una cadena de caracteres.',
            'name.max' => 'El campo Nombres de la referencia personal no debe exceder los 255 caracteres.',
            'last_name.required' => 'El campo Apellidos de la referencia personal es requerido.',
            'last_name.string' => 'El campo Apellidos de la referencia personal debe ser una cadena de caracteres.',
            'last_name.max' => 'El campo Apellidos de la referencia personal no debe exceder los 255 caracteres.',
            'document_type_id.required' => 'El Identificador del Tipo de documento de la referencia personal es requerido.',
            'document_type_id.exists' => 'El Identificador del tipo de documento de la referencia personal no es valido.',
            'document_number.required' => 'El campo Numero de documento de la referencia personal es requerido.',
            'document_number.string' => 'El campo Numero de documento de la referencia personal debe ser una cadena de caracteres.',
            'document_number.unique' => 'El Numero de documento ya ha sido tomado.',
            'document_number.min' => 'El campo Numero de documento de la referencia personal debe contener minimo 5 caracteres.',
            'document_number.max' => 'El campo Numero de documento de la referencia personal no debe exceder los 20 caracteres.',
            'country_id.required' => 'El Identificador del Pais de la referencia personal es requerido.',
            'country_id.exists' => 'El Identificador del pais no es valido.',
            'departament_id.required' => 'El Identificador del Departamento de la referencia personal es requerido.',
            'departament_id.exists' => 'El Identificador del departamento no es valido.',
            'city_id.required' => 'El Identificador de la Ciudad de la referencia personal es requerido.',
            'city_id.exists' => 'El Identificador de la ciudad es valido.',
            'address.required' => 'El campo Direccion de la referencia personal es requerido.',
            'address.string' => 'El campo Direccion de la referencia personal debe ser una cadena de caracteres.',
            'address.max' => 'El campo Direccion de la referencia personal no debe exceder los 255 caracteres.',
            'neighborhood.required' => 'El campo Barrio de la referencia personal es requerido.',
            'neighborhood.string' => 'El campo Barrio de la referencia personal debe ser una cadena de caracteres.',
            'neighborhood.max' => 'El campo Barrio de la referencia personal no debe exceder los 255 caracteres.',
            'email.required' => 'El campo Correo electronico de la referencia personal es requerido.',
            'email.email' => 'El campo Correo electronico de la referencia personal debe ser una dirección de correo electrónico válida.',
            'email.max' => 'El campo Correo electronico de la referencia personal no debe exceder los 255 caracteres.',
            'telephone_number_first.required' => 'El campo Numero de telefono de la referencia personal es requerido.',
            'telephone_number_first.numeric' => 'El campo Numero de telefono de la referencia personal debe ser una cadena de digitos.',
            'telephone_number_first.size' => 'El campo Numero de telefono de la referencia personal debe tener 10 caracteres.',
            'telephone_number_second.numeric' => 'El campo Numero de telefono de la referencia personal debe ser una cadena de digitos.',
            'telephone_number_second.size' => 'El campo Numero de telefono de la referencia personal debe tener 10 caracteres.',
            'country_departament.exists' => 'El departamento no pertenece al pais seleccionado.',
            'departament_city.exists' => 'La ciudad no pertenece al departamento seleccionado.',
        ];
    }
}
