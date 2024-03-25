<?php

namespace App\Http\Requests\Client;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ClientQuotaQueryRequest extends FormRequest
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
        $client = Client::with('client_type')->findOrFail($this->route('id'));
        $this->merge([
            'require_quota' => $client->client_type->require_quota,
            'country_departament' => $this->input('departament_id'),
            'departament_city' => $this->input('city_id'),
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'quota' => ['required', 'numeric'],
            'require_quota' => [Rule::prohibitedIf(!$this->input('require_quota'))],
        ];
    }

    public function messages()
    {
        return [
            'quota.required' => 'El campo Cupo del cliente es requerido.',
            'quota.numeric' => 'El campo Cupo del cliente debe ser numerico.',
            'require_quota.prohibited' => 'El tipo cliente seleccionado en la creacion del cliente no permite que se le asigne un cupo disponible.',
        ];
    }
}
