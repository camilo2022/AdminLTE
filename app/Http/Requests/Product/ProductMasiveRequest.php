<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

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
    
    protected function getIndex()
    {
        return explode('.', Request::route()->parameterNames()[0])[1];
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
            'Products.*.code' => ['required', 'string', 'max:255', 'unique:products,code'],
            'Products.*.description' => ['nullable', 'string', 'max:255'],
            'Products.*.price' => ['required', 'numeric', 'between:0,999999.99'],
            'Products.*.cost' => ['required', 'numeric', 'between:0,999999.99'],
            'Products.*.clothing_line_id' => ['required', 'exists:clothing_lines,id'],
            'Products.*.category_id' => ['required', 'exists:categories,id'],
            'Products.*.subcategory_id' => ['required', 'exists:subcategories,id'],
            'Products.*.model_id' => ['required', 'exists:models,id'],
            'Products.*.trademark_id' => ['required', 'exists:trademarks,id'],
            'Products.*.correria_id' => ['required', 'exists:correrias,id'],
            'Products.*.collection_id' => ['required', 'exists:collections,id'],
            'Products.*.clothingLine_category' => ['exists:categories,id,clothing_line_id,' . $this->input('Products.' . $this->getIndex() . '.clothing_line_id')],
            'Products.*.category_subcategory' => ['exists:subcategories,id,category_id,' . $this->input('Products.' . $this->getIndex() . '.category_id')],
            'ProductsSizes.*.code' => ['required', 'string', 'max:255', Rule::in(collect($this->Products)->pluck('code'))],
            'ProductsSizes.*.size_id' => ['required', 'exists:sizes,id'],
            'ProductsColorsTones.*.code' => ['required', 'string', 'max:255', Rule::in(collect($this->Products)->pluck('code'))],
            'ProductsColorsTones.*.color_id' => ['required', 'exists:colors,id'],
            'ProductsColorsTones.*.tone_id' => ['required', 'exists:tones,id'],
        ];
    }

    public function messages()
    {
        return [
            'Products.*.code.required' => 'Pag. 1 | El campo Codigo del producto es requerido.',
            'Products.*.code.string' => 'Pag. 1 | El campo Codigo del producto debe ser una cadena de texto.',
            'Products.*.code.max' => 'Pag. 1 | El campo Codigo del producto no debe tener mas de 255 caracteres.',
            'Products.*.code.unique' => 'Pag. 1 | El campo Codigo del producto ya fue tomado.',
            'Products.*.description.string' => 'Pag. 1 | El campo Descripcion del producto debe ser una cadena de texto.',
            'Products.*.description.max' => 'Pag. 1 | El campo Descripcion del producto no debe tener mas de 255 caracteres.',
            'Products.*.price.required' => 'Pag. 1 | El campo Precio del producto es requerido.',
            'Products.*.price.numeric' => 'Pag. 1 | El campo Precio del producto debe ser numerico.',
            'Products.*.price.between' => 'Pag. 1 | El campo Precio del producto debe estar en un rango de 0 a 999999.99.',
            'Products.*.cost.required' => 'Pag. 1 | El campo Costo del producto es requerido.',
            'Products.*.cost.numeric' => 'Pag. 1 | El campo Costo del producto debe ser numerico.',
            'Products.*.cost.between' => 'Pag. 1 | El campo Costo del producto debe estar en un rango de 0 a 999999.99.',
            'Products.*.clothing_line_id.required' => 'Pag. 1 | El campo Linea del producto es requerido.',
            'Products.*.clothing_line_id.exists' => 'Pag. 1 | Identificador de la linea del producto no es valido.',
            'Products.*.category_id.required' => 'Pag. 1 | El campo Categoria del producto es requerido.',
            'Products.*.category_id.exists' => 'Pag. 1 | Identificador de la categoria del producto no es valido.',
            'Products.*.subcategory_id.required' => 'Pag. 1 | El campo Subcategoria del producto es requerido.',
            'Products.*.subcategory_id.exists' => 'Pag. 1 | Identificador de la subcategoria del producto no es valido.',
            'Products.*.model_id.required' => 'Pag. 1 | El campo Modelo del producto es requerido.',
            'Products.*.model_id.exists' => 'Pag. 1 | Identificador del modelo del producto no es valido.',
            'Products.*.trademark_id.required' => 'Pag. 1 | El campo Marca del producto es requerido.',
            'Products.*.trademark_id.exists' => 'Pag. 1 | Identificador de la marca del producto no es valido.',
            'Products.*.correria_id.required' => 'Pag. 1 | El campo Correria del producto es requerido.',
            'Products.*.correria_id.exists' => 'Pag. 1 | Identificador de la correria del producto no es valido.',
            'Products.*.collection_id.required' => 'Pag. 1 | El campo Coleccion del producto es requerido.',
            'Products.*.collection_id.exists' => 'Pag. 1 | Identificador de la coleccion del producto no es valido.',
            'Products.*.clothingLine_category.exists' => 'Pag. 1 | La categoria no pertenece a la linea de producto seleccionada.',
            'Products.*.category_subcategory.exists' => 'Pag. 1 | La subcategoria no pertenece a la categoria seleccionada.',
            'ProductsSizes.*.code.required' => 'Pag. 2 | El campo Codigo del producto es requerido.',
            'ProductsSizes.*.code.string' => 'Pag. 2 | El campo Codigo del producto debe ser una cadena de texto.',
            'ProductsSizes.*.code.max' => 'Pag. 2 | El campo Codigo del producto no debe tener mas de 255 caracteres.',
            'ProductsSizes.*.code.in' => 'Pag. 2 | El codigo del producto no aparece en la hoja Productos.',
            'ProductsSizes.*.size_id.required' => 'Pag. 2 | El campo Tallas del producto es requerido.',
            'ProductsSizes.*.size_id.exists' => 'Pag. 2 | Identificador de la talla no es valido.',
            'ProductsColorsTones.*.code.required' => 'Pag. 3 | El campo Codigo del producto es requerido.',
            'ProductsColorsTones.*.code.string' => 'Pag. 3 | El campo Codigo del producto debe ser una cadena de texto.',
            'ProductsColorsTones.*.code.max' => 'Pag. 3 | El campo Codigo del producto no debe tener mas de 255 caracteres.',
            'ProductsColorsTones.*.code.in' => 'Pag. 3 | El codigo del producto no aparece en la hoja Productos.',
            'ProductsColorsTones.*.color_id.required' => 'Pag. 3 | El campo Colores del producto es requerido.',
            'ProductsColorsTones.*.color_id.exists' => 'Pag. 3 | Identificador del color no es valido.',
            'ProductsColorsTones.*.tone_id.required' => 'Pag. 3 | El campo Tonos del producto es requerido.',
            'ProductsColorsTones.*.tone_id.exists' => 'Pag. 3 | Identificador del tono no es valido.',
        ];
    }
}
