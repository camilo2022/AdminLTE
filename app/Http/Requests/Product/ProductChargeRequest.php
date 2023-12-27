<?php

namespace App\Http\Requests\Product;

use App\Rules\ImageDimension;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductChargeRequest extends FormRequest
{
    /**
     * Maneja una solicitud fallida de validación.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Validation\ValidationException
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
            'photos' => json_decode($this->input('photos')),
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
            'product_color_tone_id' => ['required', 'exists:product_color_tone,id'],
            'photos' => ['required', 'array'],
            'photos.*' => ['mimes:jpeg,jpg,png,gif', 'max:10000',  new ImageDimension(100, 1920, 100, 1080, function($attr, $value){ return $value->getClientOriginalName(); })]
        ];
    }

    public function messages()
    {
        return [
            'product_color_tone_id.required' => 'El campo de la relacion Producto, Color y Tono es requerido.',
            'product_color_tone_id.exists' => 'El Identificador de la relacion Producto, Color y Tono no es valido.',
            'photos.array' => 'El campo Fotos del producto debe ser un arreglo.',
            'photos.*.mimes' => 'El Archivo debe tener una extensión válida (jpeg, jpg, png, gif).',
            'photos.*.max' => 'El Archivo no debe superar los 10 MB.'
        ];
    }
}
