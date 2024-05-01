<?php

namespace App\Http\Requests\Supply;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SupplyChargeRequest extends FormRequest
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
            'files' => json_decode($this->input('files')),
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => ['required', 'exists:supplies,id'],
            'files' => ['required', 'array'],
            'files.*' => ['file', 'mimes:jpeg,jpg,png,gif,mp4,m4v,avi,wmv,mov,mkv,flv,webm,mpeg,mpg']
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El campo Insumo es requerido.',
            'id.exists' => 'El Identificador del insumo no es valido.',
            'files.required' => 'El campo Archivos del producto es requerido.',
            'files.array' => 'El campo Archivos del producto debe ser un arreglo.',
            'files.*.mimes' => 'El Archivo :file debe tener una extensión válida (jpeg, jpg, png, gif, mp4, m4v, avi, wmv, mov, mkv, flv, webm, mpeg, mpg).',
        ];
    }
}
