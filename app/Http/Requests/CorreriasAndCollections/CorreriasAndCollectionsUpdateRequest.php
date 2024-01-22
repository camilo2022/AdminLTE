<?php

namespace App\Http\Requests\CorreriasAndCollections;

use App\Rules\DateNotBetween;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CorreriasAndCollectionsUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:correrias,name,' . $this->route('id') .',id'],
            'code' => ['required', 'string', 'unique:correrias,code,' . $this->route('id') .',id'],
            'start_date' => ['required', 'date', new DateNotBetween('correrias', 'start_date', 'end_date', $this->input('start_date'), $this->route('id'))],
            'end_date' => ['required', 'date', 'after_or_equal:start_date', new DateNotBetween('correrias', 'start_date', 'end_date', $this->input('end_date'), $this->route('id'))],
            'date_definition_start_pilots' => ['required', 'date'],
            'date_definition_start_samples' => ['required', 'date'],
            'proyection_stop_warehouse' => ['required', 'numeric', 'min:1', 'max:100'],
            'number_samples_include_suitcase' => ['required', 'numeric', 'min:1'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre de la correria es requerido.',
            'name.string' => 'El campo Nombre de la correria debe ser una cadena de texto.',
            'name.unique' => 'El campo Nombre de la correria ya existe en la base de datos.',
            'code.required' => 'El campo Codigo de la correria es requerido.',
            'code.string' => 'El campo Codigo de la correria debe ser una cadena de texto.',
            'code.unique' => 'El campo Codigo de la correria ya existe en la base de datos.',
            'start_date.required' => 'El campo Fecha de inicio es requerido.',
            'start_date.date' => 'El campo Fecha de inicio debe ser una fecha válida.',
            'end_date.date' => 'El campo Fecha de fin debe ser una fecha válida.',
            'end_date.after_or_equal' => 'El campo Fecha de fin debe ser igual o posterior a la Fecha de inicio.',
            'date_definition_start_pilots.required' => 'El campo Fecha definicion e inicio de pilotos de la coleccion es requerido.',
            'date_definition_start_pilots.date' => 'El campo Fecha definicion e inicio de pilotos debe ser una fecha válida.',
            'date_definition_start_samples.required' => 'El campo Fecha definicion e inicio de muestras de la coleccion es requerido.',
            'date_definition_start_samples.date' => 'El campo Fecha definicion e inicio de muestras debe ser una fecha válida.',
            'proyection_stop_warehouse.required' => 'El campo Porcentaje proyeccion bodega de la coleccion es requerido.',
            'proyection_stop_warehouse.numeric' => 'El campo Porcentaje proyeccion bodega debe ser una cadena de digitos.',
            'proyection_stop_warehouse.max' => 'El campo Porcentaje proyeccion bodega debe superar el 1%.',
            'proyection_stop_warehouse.max' => 'El campo Porcentaje proyeccion bodega no debe exceder el 100%.',
            'number_samples_include_suitcase.required' => 'El campo Numero de muestras a incluir en la maleta de la coleccion es requerido.',
            'number_samples_include_suitcase.numeric' => 'El campo Numero de muestras a incluir en la maleta debe ser una cadena de digitos.',
            'number_samples_include_suitcase.max' => 'El campo Numero de muestras a incluir en la maleta debe superar el 1.',
        ];
    }

    public function attributes()
    {
        return [
            'start_date' => 'Fecha Inicial',
            'end_date' => 'Fecha Final'
        ];
    }
}
