<?php

namespace App\Http\Requests\OrderSeller;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderSellerStoreRequest extends FormRequest
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
            'client_clientBranch' => $this->input('client_branch_id'),
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
            'client_branch_id' => ['required', 'exists:client_branches,id'],
            'dispatch' => ['required', 'string', 'max:255'],
            'dispatch_date' => ['required', 'date', 'after_or_equal:now'],
            'seller_observation' => ['nullable', 'string', 'max:255'],
            'correria_id' => ['required', 'exists:correrias,id'],
            'client_clientBranch' => ['exists:client_branches,id,client_id,' . $this->input('client_id')]
        ];
    }


    public function messages()
    {
        return [
            'client_id.required' => 'El campo Cliente es requerido.',
            'client_id.exists' => 'El Identificador del cliente no es valido.',
            'client_branch_id.required' => 'El campo Sucursal del Cliente es requerido.',
            'client_branch_id.exists' => 'El Identificador de la sucursal del cliente no es valido.',
            'dispatch.required' => 'El campo Despacho es requerido.',
            'dispatch.string' => 'El campo Despacho debe ser una cadena de caracteres.',
            'dispatch.max' => 'El campo Despacho no debe exceder los 255 caracteres.',
            
            'client_clientBranch.exists' => 'La sucursal no pertenece al cliente seleccionado.',

            'document_number.required' => 'El campo Numero de documento de la empresa es requerido.',
            'document_number.string' => 'El campo Numero de documento de la empresa debe ser una cadena de caracteres.',
            'document_number.unique' => 'El campo Numero de documento de la empresa ya ha sido tomado.',
            'document_number.min' => 'El campo Numero de documento de la empresa debe contener minimo 5 caracteres.',
            'document_number.max' => 'El campo Numero de documento de la empresa no debe exceder los 20 caracteres.',
            'telephone_number.required' => 'El campo Numero de telefono de la empresa es requerido.',
            'telephone_number.numeric' => 'El campo Numero de telefono de la empresa debe ser una cadena de digitos.',
            'telephone_number.size' => 'El campo Numero de telefono de la empresa debe tener 10 caracteres.',
            'email.required' => 'El campo Correo electronico de la empresa es requerido.',
            'email.email' => 'El campo Correo electronico de la empresa debe ser una dirección de correo electrónico válida.',
            'email.unique' => 'El campo Correo electronico de la empresa ya ha sido tomado.',
            'email.max' => 'El campo Correo electronico de la empresa no debe exceder los 255 caracteres.',
            'country_id.required' => 'El campo Pais de la empresa es requerido.',
            'country_id.exists' => 'El campo Pais de la empresa no existe en la base de datos.',
            'departament_id.required' => 'El campo Departamento de la empresa es requerido.',
            'departament_id.exists' => 'El campo Departamento de la empresa no existe en la base de datos.',
            'city_id.required' => 'El campo Ciudad de la empresa es requerido.',
            'city_id.exists' => 'El campo Ciudad de la empresa no existe en la base de datos.',
            'address.required' => 'El campo Direccion de la empresa es requerido.',
            'address.string' => 'El campo Direccion de la empresa debe ser una cadena de caracteres.',
            'address.max' => 'El campo Direccion de la empresa no debe exceder los 255 caracteres.',
            'neighborhood.required' => 'El campo Barrio de la empresa es requerido.',
            'neighborhood.string' => 'El campo Barrio de la empresa debe ser una cadena de caracteres.',
            'neighborhood.max' => 'El campo Barrio de la empresa no debe exceder los 255 caracteres.',
            'description.string' => 'El campo Descripcion de la empresa debe ser una cadena de caracteres.',
            'description.max' => 'El campo Descripcion de la empresa no debe exceder los 255 caracteres.',
            
        ];
    }
}
