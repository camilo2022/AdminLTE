<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductAssignColorToneRequest extends FormRequest
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
            'product_id' => ['required', 'exists:products,id'],
            'color_id' => ['required', 'exists:colors,id'],
            'tone_id' => ['required', 'exists:tones,id'],
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'El Identificador del producto es requerido.',
            'product_id.exists' => 'El Identificador del producto no es válido.',
            'color_id.required' => 'El Identificador del color es requerido.',
            'color_id.exists' => 'El Identificador del color no es válido.',
            'tone_id.required' => 'El Identificador del tono es requerido.',
            'tone_id.exists' => 'El Identificador del tono no es válido.',
        ];
    }
}
