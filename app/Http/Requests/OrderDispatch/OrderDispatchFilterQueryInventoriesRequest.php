<?php

namespace App\Http\Requests\OrderDispatch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderDispatchFilterQueryInventoriesRequest extends FormRequest
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
            'product_id' => ['required', 'exists:products,id'],
            'color_id' => ['required', 'exists:colors,id'],
            'tone_id' => ['required', 'exists:tones,id'],
            'size_ids' => ['required', 'array'],
            'size_ids.*' => ['required', 'exists:sizes,id']
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
            'size_ids.required' => 'El campo Identificadores de las tallas es requerido.',
            'size_ids.array' => 'El campo Identificadores de las tallas debe ser un arreglo.',
            'size_ids.*.required' => 'El Identificador de la talla es requerido.',
            'size_ids.*.exists' => 'El Identificador de la talla no es válido.',
        ];
    }
}
