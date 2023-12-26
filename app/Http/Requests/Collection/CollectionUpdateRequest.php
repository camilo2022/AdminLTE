<?php

namespace App\Http\Requests\Collection;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CollectionUpdateRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación.',
            'errors' => $validator->errors()
        ], 422));
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:collections,name,' . $this->route('id') .',id'],
            'code' => ['required', 'string', 'unique:collections,code,' . $this->route('id') .',id']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre de la coleccion es requerido.',
            'name.string' => 'El campo Nombre de la coleccion debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre de la coleccion ya existe en la base de datos.',
            'code.required' => 'El campo Codigo de la coleccion es requerido.',
            'code.string' => 'El campo Codigo de la coleccion debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo de la coleccion ya existe en la base de datos.',
        ];
    }
}
