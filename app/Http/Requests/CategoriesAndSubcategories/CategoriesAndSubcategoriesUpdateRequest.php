<?php

namespace App\Http\Requests\CategoriesAndSubcategories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoriesAndSubcategoriesUpdateRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación',
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
            'clothing_line_id' => ['required', 'exists:clothing_lines,id'],
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $this->route('id') . ',id'],
            'code' => ['required', 'string', 'max:255', 'unique:categories,code,' . $this->route('id') . ',id'],
            'description' => ['nullable', 'string', 'max:255'],
            'subcategories' => ['required', 'array'],
            'subcategories.*.name' => ['required', 'string', 'max:255'],
            'subcategories.*.code' => ['required', 'string', 'max:255'],
            'subcategories.*.description' => ['nullable', 'string', 'max:255'],
            'subcategories.*.status' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'clothing_line_id.required' => 'El campo Linea del prodcuto es requerido.',
            'clothing_line_id.exists' => 'El campo Linea del prodcuto no es valido.',
            'name.required' => 'El campo Nombre de la categoria es requerido.',
            'name.string' => 'El campo Nombre de la categoria debe ser una cadena de caracteres.',
            'name.max' => 'El campo Nombre de la categoria no debe exceder los 255 caracteres.',
            'code.required' => 'El campo Codigo de la categoria es requerido.',
            'code.string' => 'El campo Codigo de la categoria debe ser una cadena de caracteres.',
            'code.max' => 'El campo Codigo de la categoria no debe exceder los 255 caracteres.',
            'description.string' => 'El campo Descripcion de la categoria debe ser una cadena de caracteres.',
            'description.max' => 'El campo Descripcion de la categoria no debe exceder los 255 caracteres.',
            'subcategories.required' => 'El campo Subcategorias de la categoria es requerido.',
            'subcategories.array' => 'El campo Subcategorias de la categoria debe ser un arreglo.',
            'subcategories.*.name.required' => 'El campo Nombre de la subcategoria es requerido.',
            'subcategories.*.name.string' => 'El campo Nombre de la subcategoria debe ser una cadena de caracteres.',
            'subcategories.*.name.unique' => 'El campo Nombre de la subcategoria ya ha sido tomado.',
            'subcategories.*.name.max' => 'El campo Nombre de la subcategoria no debe exceder los 255 caracteres.',
            'subcategories.*.code.required' => 'El campo Codigo de la subcategoria es requerido.',
            'subcategories.*.code.string' => 'El campo Codigo de la subcategoria debe ser una cadena de caracteres.',
            'subcategories.*.code.unique' => 'El campo Codigo de la subcategoria ya ha sido tomado.',
            'subcategories.*.code.max' => 'El campo Codigo de la subcategoria no debe exceder los 255 caracteres.',
            'subcategories.*.description.string' => 'El campo Descripcion de la subcategoria debe ser una cadena de caracteres.',
            'subcategories.*.description.max' => 'El campo Descripcion de la subcategoria no debe exceder los 255 caracteres.',
            'subcategories.*.status.required' => 'El campo Estado de la subcategoria es requerido.'
        ];
    }
}
