<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductChargeRequest extends FormRequest
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
            'product_color_tone_id' => ['required', 'exists:product_color_tone,id'],
            'files' => ['required', 'array'],
            'files.*' => ['file', 'mimes:jpeg,jpg,png,gif,mp4,m4v,avi,wmv,mov,mkv,flv,webm,mpeg,mpg']
        ];
    }

    public function messages()
    {
        return [
            'product_color_tone_id.required' => 'El campo de la relacion Producto, Color y Tono es requerido.',
            'product_color_tone_id.exists' => 'El Identificador de la relacion Producto, Color y Tono no es valido.',
            'files.required' => 'El campo Archivos del producto es requerido.',
            'files.array' => 'El campo Archivos del producto debe ser un arreglo.',
            'files.*.mimes' => 'El Archivo debe tener una extensión válida (jpeg, jpg, png, gif, mp4, m4v, avi, wmv, mov, mkv, flv, webm, mpeg, mpg).',
        ];
    }
}
