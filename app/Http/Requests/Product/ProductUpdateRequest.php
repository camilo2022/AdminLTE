<?php

namespace App\Http\Requests\Product;

use App\Rules\ImageDimension;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductUpdateRequest extends FormRequest
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
            'sizes' => json_decode($this->input('sizes')),
            'colors' => json_decode($this->input('colors')),
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
            'code' => ['required', 'string', 'unique:products,code,' . $this->route('id') .',id', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'between:0,999999.99'],
            'clothing_line_id' => ['required', 'exists:clothing_lines,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['required', 'exists:subcategories,id'],
            'model_id' => ['required', 'exists:models,id'],
            'trademark_id' => ['required', 'exists:trademarks,id'],
            'correria_id' => ['required', 'exists:correrias,id'],
            'colors' => ['required', 'array'],
            'colors.*' => ['exists:colors,id'],
            'sizes' => ['required', 'array'],
            'sizes.*' => ['exists:sizes,id'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['mimes:jpeg,jpg,png,gif', 'max:10000', new ImageDimension(100, 1920, 100, 1080, function($attr, $value){ return $value->getClientOriginalName(); })]
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
            'price.string' => 'El campo Precio del producto debe ser numerico.',
            'price.between' => 'El campo Precio del producto debe estar en un rango de 0 a 999999.99.',
            'clothing_line_id.required' => 'El campo Linea del producto es requerido.',
            'clothing_line_id.exists' => 'El Identificador de la linea del producto no es valido.',
            'category_id.required' => 'El campo Categoria del producto es requerido.',
            'category_id.exists' => 'El Identificador de la categoria del producto no es valido.',
            'subcategory_id.required' => 'El campo Subcategoria del producto es requerido.',
            'subcategory_id.exists' => 'El Identificador de la subcategoria del producto no es valido.',
            'model_id.required' => 'El campo Modelo del producto es requerido.',
            'model_id.exists' => 'El Identificador del modelo del producto no es valido.',
            'trademark_id.required' => 'El campo Marca del producto es requerido.',
            'trademark_id.exists' => 'El Identificador de la marca del producto no es valido.',
            'correria_id.required' => 'El campo Correria del producto es requerido.',
            'correria_id.exists' => 'El Identificador de la correria del producto no es valido.',
            'colors.array' => 'El campo Colores del producto debe ser un arreglo.',
            'colors.*.exists' => 'El Identificador del color no es valido.',
            'sizes.array' => 'El campo Tallas del producto debe ser un arreglo.',
            'sizes.*.exists' => 'El Identificador de la talla no es valido.',
            'photos.array' => 'El campo Fotos del producto debe ser un arreglo.',
            'photos.*.mimes' => 'El Archivo debe tener una extensión válida (jpeg, jpg, png, gif).',
            'photos.*.max' => 'El Archivo no debe superar los 10 MB.',
        ];
    }
}
