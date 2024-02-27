<?php

namespace App\Http\Requests\ClientBranch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClientBranchStoreRequest extends FormRequest
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
            'client_id' => ['required', 'exists:clients,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:client_branches,code,NULL,id,client_id,' . $this->input('client_id')],
            'country_id' => ['required', 'exists:countries,id'],
            'departament_id' => ['required', 'exists:departaments,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
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
            'client_id.required' => 'El Identificador del Cliente es requerido.',
            'client_id.exists' => 'El Identificador del cliente no es valido.',
            'name.required' => 'El campo Nombre de la sucursal del cliente es requerido.',
            'name.string' => 'El campo Nombre de la sucursal del cliente debe ser una cadena de caracteres.',
            'name.max' => 'El campo Nombre de la sucursal del cliente no debe exceder los 255 caracteres.',
            'code.required' => 'El campo Codigo de la sucursal del cliente es requerido.',
            'code.string' => 'El campo Codigo de la sucursal del cliente debe ser una cadena de caracteres.',
            'code.max' => 'El campo Codigo de la sucursal del cliente no debe exceder los 255 caracteres.',
            'code.unique' => 'El Codigo de sucursal ya ha sido tomado.',
            'country_id.required' => 'El Identificador del Pais de la sucursal del cliente es requerido.',
            'country_id.exists' => 'El Identificador del pais no es valido.',
            'departament_id.required' => 'El Identificador del Departamento de la sucursal del cliente es requerido.',
            'departament_id.exists' => 'El Identificador del departamento no es valido.',
            'city_id.required' => 'El Identificador de la Ciudad de la sucursal del cliente es requerido.',
            'city_id.exists' => 'El Identificador de la ciudad es valido.',
            'address.required' => 'El campo Direccion de la sucursal del cliente es requerido.',
            'address.string' => 'El campo Direccion de la sucursal del cliente debe ser una cadena de caracteres.',
            'address.max' => 'El campo Direccion de la sucursal del cliente no debe exceder los 255 caracteres.',
            'neighborhood.required' => 'El campo Barrio de la sucursal del cliente es requerido.',
            'neighborhood.string' => 'El campo Barrio de la sucursal del cliente debe ser una cadena de caracteres.',
            'neighborhood.max' => 'El campo Barrio de la sucursal del cliente no debe exceder los 255 caracteres.',
            'description.string' => 'El campo Descripcion de la sucursal del cliente debe ser una cadena de caracteres.',
            'description.max' => 'El campo Descripcion de la sucursal del cliente no debe exceder los 255 caracteres.',
            'email.required' => 'El campo Correo electronico de la sucursal del cliente es requerido.',
            'email.email' => 'El campo Correo electronico de la sucursal del cliente debe ser una dirección de correo electrónico válida.',
            'email.max' => 'El campo Correo electronico de la sucursal del cliente no debe exceder los 255 caracteres.',
            'telephone_number_first.required' => 'El campo Numero de telefono de la sucursal del cliente es requerido.',
            'telephone_number_first.numeric' => 'El campo Numero de telefono de la sucursal del cliente debe ser una cadena de digitos.',
            'telephone_number_second.numeric' => 'El campo Numero de telefono de la sucursal del cliente debe ser una cadena de digitos.',
            'country_departament.exists' => 'El departamento no pertenece al pais seleccionado.',
            'departament_city.exists' => 'La ciudad no pertenece al departamento seleccionado.',
        ];
    }
}
