<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductMasiveRequest extends FormRequest
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
            'message' => 'Error de validación',
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
            'products.*.code' => ['required', 'string', 'max:255', 'unique:products,code'],
            'products.*.description' => ['nullable', 'string', 'max:255'],
            'products.*.price' => ['required', 'numeric', 'between:0,999999.99'],
            'products.*.clothing_line_id' => ['required'],
            'products.*.category_id' => ['required', 'exists:categories,id'],
            'products.*.subcategory_id' => ['required', 'exists:subcategories,id'],
            'products.*.model_id' => ['required', 'exists:models,id'],
            'products.*.trademark_id' => ['required', 'exists:trademarks,id'],
            'products.*.collection_id' => ['required', 'exists:collections,id'],
            'products.*.color_id' => ['required', 'exists:colors,id'],
            'products.*.size_id' => ['required', 'exists:sizes,id'],
        ];
    }

    public function messages()
    {
        return [
            'products.*.code.required' => 'El campo Codigo del producto es requerido.',
            'products.*.code.string' => 'El campo Codigo del producto debe ser una cadena de texto.',
            'products.*.code.max' => 'El campo Codigo del producto no debe tener mas de 255 caracteres.',
            'products.*.code.unique' => 'El campo Codigo del producto ya fue tomado.',
            'products.*.description.string' => 'El campo Descripcion del producto debe ser una cadena de texto.',
            'products.*.description.max' => 'El campo Descripcion del producto no debe tener mas de 255 caracteres.',
            'products.*.price.required' => 'El campo Precio del producto es requerido.',
            'products.*.price.string' => 'El campo Precio del producto debe ser numerico.',
            'products.*.price.between' => 'El campo Precio del producto debe estar en un rango de 0 a 999999.99.',
            'products.*.clothing_line_id.required' => 'El campo Linea del producto es requerido.',
            'products.*.clothing_line_id.exists' => 'El Identificador de la linea del producto no es valido.',
            'products.*.category_id.required' => 'El campo Categoria del producto es requerido.',
            'products.*.category_id.exists' => 'El Identificador de la categoria del producto no es valido.',
            'products.*.subcategory_id.required' => 'El campo Subcategoria del producto es requerido.',
            'products.*.subcategory_id.exists' => 'El Identificador de la subcategoria del producto no es valido.',
            'products.*.model_id.required' => 'El campo Modelo del producto es requerido.',
            'products.*.model_id.exists' => 'El Identificador del modelo del producto no es valido.',
            'products.*.trademark_id.required' => 'El campo Marca del producto es requerido.',
            'products.*.trademark_id.exists' => 'El Identificador de la marca del producto no es valido.',
            'products.*.collection_id.required' => 'El campo Correria del producto es requerido.',
            'products.*.collection_id.exists' => 'El Identificador de la correria del producto no es valido.',
            'products.*.color_id.required' => 'El campo Colores del producto es requerido.',
            'products.*.color_id.exists' => 'El Identificador del color no es valido.',
            'products.*.size_id.required' => 'El campo Tallas del producto es requerido.',
            'products.*.size_id.exists' => 'El Identificador de la talla no es valido.',
        ];
    }
}
