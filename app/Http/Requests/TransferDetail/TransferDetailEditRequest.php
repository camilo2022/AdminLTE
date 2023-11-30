<?php

namespace App\Http\Requests\TransferDetail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransferDetailEditRequest extends FormRequest
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
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'product_id' => ['nullable', 'exists:products,id'],
            'color_id' => ['nullable', 'exists:colors,id'],
        ];
    }

    public function messages()
    {
        return [
            'warehouse_id.exists' => 'El Identificador de la bodega no es valido.',
            'product_id.exists' => 'El Identificador del producto no es valido.',
            'color_id.exists' => 'El Identificador del color no es valido.',
        ];
    }
}
