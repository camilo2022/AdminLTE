<?php

namespace App\Http\Requests\Product;

use App\Rules\ImageDimension;
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
            'photos' => json_decode($this->input('photos')),
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
            'photos' => ['required', 'array'],
            'photos.*' => ['mimes:jpeg,jpg,png,gif']
        ];
    }

    public function messages()
    {
        return [
            'product_color_tone_id.required' => 'El campo de la relacion Producto, Color y Tono es requerido.',
            'product_color_tone_id.exists' => 'El Identificador de la relacion Producto, Color y Tono no es valido.',
            'photos.array' => 'El campo Fotos del producto debe ser un arreglo.',
            'photos.*.mimes' => 'El Archivo debe tener una extensión válida (jpeg, jpg, png, gif).',
        ];
    }
}
