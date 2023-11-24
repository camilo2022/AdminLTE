<?php

namespace App\Http\Requests\DocumentType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DocumentTypeStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:document_types,name', 'max:255'],
            'code' => ['required', 'string', 'unique:document_types,code', 'max:255']
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre del tipo de Documento es requerido.',
            'name.string' => 'El campo Nombre del tipo de Documento debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre del tipo de Documento ya existe en la base de datos.',
            'name.max' => 'El campo Nombre del tipo de Documento no debe exceder los 255 caracteres.',
            'code.required' => 'El campo Codigo del tipo de Documento es requerido.',
            'code.string' => 'El campo Codigo del tipo de Documento debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo del tipo de Documento ya existe en la base de datos.',
            'code.max' => 'El campo Codigo del tipo de Documento no debe exceder los 255 caracteres.',
        ];
    }
}
