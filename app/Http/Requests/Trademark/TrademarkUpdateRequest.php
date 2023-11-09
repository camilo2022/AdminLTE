<?php

namespace App\Http\Requests\Trademark;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TrademarkUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:trademarks,name,' . $this->route('id') .',id', 'max:255'],
            'code' => ['required', 'string', 'unique:trademarks,code,' . $this->route('id') .',id', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre de la marca es requerido.',
            'name.string' => 'El campo Nombre de la marca debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre de la marca ya existe en la base de datos.',
            'name.max' => 'El campo Nombre de la marca no debe tener mas de 255 caracteres.',
            'code.required' => 'El campo Codigo de la marca es requerido.',
            'code.string' => 'El campo Codigo de la marca debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo de la marca ya existe en la base de datos.',
            'code.max' => 'El campo Nombre de la marca no debe tener mas de 255 caracteres.',
            'description.string' => 'El campo Descripcion de la marca ya existe en la base de datos.',
            'description.max' => 'El campo Descripcion de la marca no debe tener mas de 255 caracteres.'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nombre de la marca',
            'code' => 'Codigo de la marca',
            'description' => 'Descripcion de la marca',
            'logo' => 'Logo de la marca'
        ];
    }
}
