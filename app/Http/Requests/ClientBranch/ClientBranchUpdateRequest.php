<?php

namespace App\Http\Requests\ClientBranch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClientBranchUpdateRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'country_departament' => $this->input('departament_id'),
            'departament_city' => $this->input('city_id'),
        ]);
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
            'client_id' => ['required', 'exists:clients,id'],
            'code' => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'exists:countries,id'],
            'departament_id' => ['required', 'exists:departaments,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'telephone_number_first' => ['required', 'string', 'size:10'],
            'telephone_number_second' => ['nullable', 'string', 'size:10'],
            'country_departament' => ['exists:departaments,id,country_id,' . $this->input('country_id')],
            'departament_city' => ['exists:cities,id,departament_id,' . $this->input('departament_id')]
        ];
    }


    public function messages()
    {
        return [
            'client_id.required' => 'El campo Cliente es requerido.',
            'client_id.exists' => 'El Identificador del cliente no es valido.',
            'code.required' => 'El campo Codigo de la sucursal del cliente es requerido.',
            'code.string' => 'El campo Codigo de la sucursal del cliente debe ser una cadena de caracteres.',
            'code.max' => 'El campo Codigo de la sucursal del cliente no debe exceder los 255 caracteres.',
            'country_id.required' => 'El campo Pais de la sucursal del cliente es requerido.',
            'country_id.exists' => 'El Identificador del pais no es valido.',
            'departament_id.required' => 'El campo Departamento de la sucursal del cliente es requerido.',
            'departament_id.exists' => 'El Identificador del departamento no es valido.',
            'city_id.required' => 'El campo Ciudad de la sucursal del cliente es requerido.',
            'city_id.exists' => 'El Identificador de la ciudad es valido.',
            'address.required' => 'El campo Direccion de la sucursal del cliente es requerido.',
            'address.string' => 'El campo Direccion de la sucursal del cliente debe ser una cadena de caracteres.',
            'address.max' => 'El campo Direccion de la sucursal del cliente no debe exceder los 255 caracteres.',
            'neighbourhood.required' => 'El campo Barrio de la sucursal del cliente es requerido.',
            'neighbourhood.string' => 'El campo Barrio de la sucursal del cliente debe ser una cadena de caracteres.',
            'neighbourhood.max' => 'El campo Barrio de la sucursal del cliente no debe exceder los 255 caracteres.',
            'description.string' => 'El campo Descripcion de la sucursal del cliente debe ser una cadena de caracteres.',
            'description.max' => 'El campo Descripcion de la sucursal del cliente no debe exceder los 255 caracteres.',
            'email.required' => 'El campo Correo electronico de la sucursal del cliente es requerido.',
            'email.string' => 'El campo Correo electronico de la sucursal del cliente debe ser una cadena de caracteres.',
            'email.email' => 'El campo Correo electronico de la sucursal del cliente debe ser una dirección de correo electrónico válida.',
            'email.max' => 'El campo Correo electronico de la sucursal del cliente no debe exceder los 255 caracteres.',
            'telephone_number_first.required' => 'El campo Numero de telefono de la sucursal del cliente es requerido.',
            'telephone_number_first.numeric' => 'El campo Numero de telefono de la sucursal del cliente debe ser una cadena de digitos.',
            'telephone_number_first.size' => 'El campo Numero de telefono de la sucursal del cliente debe tener 10 caracteres.',
            'telephone_number_second.numeric' => 'El campo Numero de telefono de la sucursal del cliente debe ser una cadena de digitos.',
            'telephone_number_second.size' => 'El campo Numero de telefono de la sucursal del cliente debe tener 10 caracteres.',
            'country_departament.exists' => 'El departamento no pertenece al pais seleccionado.',
            'departament_city.exists' => 'La ciudad no pertenece al departamento seleccionado.',
        ];
    }
}
