<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductStoreRequest extends FormRequest
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
            'clothingLine_category' => $this->input('category_id'),
            'category_subcategory' => $this->input('subcategory_id'),
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', 'unique:products,code', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'between:0,999999.99'],
            'cost' => ['required', 'numeric', 'between:0,999999.99'],
            'clothing_line_id' => ['required', 'exists:clothing_lines,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['required', 'exists:subcategories,id'],
            'model_id' => ['required', 'exists:models,id'],
            'trademark_id' => ['required', 'exists:trademarks,id'],
            'correria_id' => ['required', 'exists:correrias,id'],
            'clothingLine_category' => ['exists:categories,id,clothing_line_id,' . $this->input('clothing_line_id')],
            'category_subcategory' => ['exists:subcategories,id,category_id,' . $this->input('category_id')]
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'El campo Codigo del producto es requerido.',
            'code.string' => 'El campo Codigo del producto debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo del producto ya existe en la base de datos.',
            'code.max' => 'El campo Codigo del producto no debe tener mas de 255 caracteres.',
            'description.string' => 'El campo Descripcion del producto debe ser una cadena de texto.',
            'description.max' => 'El campo Descripcion del producto no debe tener mas de 255 caracteres.',
            'price.required' => 'El campo Precio del producto es requerido.',
            'price.numeric' => 'El campo Precio del producto debe ser numerico.',
            'price.between' => 'El campo Precio del producto debe estar en un rango de 0 a 999999.99.',
            'cost.required' => 'El campo Costo del producto es requerido.',
            'cost.numeric' => 'El campo Costo del producto debe ser numerico.',
            'cost.between' => 'El campo Costo del producto debe estar en un rango de 0 a 999999.99.',
            'clothing_line_id.required' => 'El Identificador de la Linea del producto es requerido.',
            'clothing_line_id.exists' => 'El Identificador de la linea del producto no es valido.',
            'category_id.required' => 'El Identificador de la Categoria del producto es requerido.',
            'category_id.exists' => 'El Identificador de la categoria del producto no es valido.',
            'subcategory_id.required' => 'El Identificador de la Subcategoria del producto es requerido.',
            'subcategory_id.exists' => 'El Identificador de la subcategoria del producto no es valido.',
            'model_id.required' => 'El Identificador del Modelo del producto es requerido.',
            'model_id.exists' => 'El Identificador del modelo del producto no es valido.',
            'trademark_id.required' => 'El Identificador de la Marca del producto es requerido.',
            'trademark_id.exists' => 'El Identificador de la marca del producto no es valido.',
            'correria_id.required' => 'El Identificador de la Correria del producto es requerido.',
            'correria_id.exists' => 'El Identificador de la correria del producto no es valido.',
            'clothingLine_category.exists' => 'La categoria no pertenece a la linea de producto seleccionada.',
            'category_subcategory.exists' => 'La subcategoria no pertenece a la categoria seleccionada.',
        ];
    }
}
