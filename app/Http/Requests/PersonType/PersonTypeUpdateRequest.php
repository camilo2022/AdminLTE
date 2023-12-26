<?php

namespace App\Http\Requests\PersonType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonTypeUpdateRequest extends FormRequest
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
        $this->request->set('require_references', $this->input('departament_id') == 'true');
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
            'name' => ['required', 'string', 'unique:person_types,name,' . $this->route('id') .',id', 'max:255'],
            'code' => ['required', 'string', 'unique:person_types,code,' . $this->route('id') .',id', 'max:255'],
            'require_references' => ['required', 'boolean']
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre del tipo de Persona es requerido.',
            'name.string' => 'El campo Nombre del tipo de Persona debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre del tipo de Persona ya existe en la base de datos.',
            'name.max' => 'El campo Nombre del tipo de Persona no debe exceder los 255 caracteres.',
            'code.required' => 'El campo Codigo del tipo de Persona es requerido.',
            'code.string' => 'El campo Codigo del tipo de Persona debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo del tipo de Persona ya existe en la base de datos.',
            'code.max' => 'El campo Codigo del tipo de Persona no debe exceder los 255 caracteres.',
            'require_references.required' => 'El campo Referencias del tipo de Persona es requerido.',
            'require_references.boolean' => 'El campo Referencias del tipo de Persona debe ser true o false.',
        ];
    }
}
