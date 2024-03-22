<?php

namespace App\Http\Requests\OrderInvoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderInvoiceStoreRequest extends FormRequest
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
            'order_dispatch_id' => ['required', 'exists:order_dispatches,id', 'exists:order_packings,order_dispatch_id'],
            'invoices' => ['required', 'array'],
            'invoices.*.value' => ['required', 'numeric'],
            'invoices.*.reference' => ['required', 'string', 'max:255'],
            'invoices.*.date' => ['required', 'date_format:Y-m-d H:i:s'],
            'invoices.*.supports' => ['required', 'array'],
            'invoices.*.supports.*' => ['file', 'mimes:jpeg,jpg,png,gif,pdf,txt,docx,xlsx,xlsm,xlsb,xltx'],
        ];
    }

    public function messages()
    {
        return [
            'order_dispatch_id.required' => 'El Identificador del detalle de la orden de despacho es requerido.',
            'order_dispatch_id.exists' => 'El Identificador del detalle de la orden de despacho ya fue tomado.',
            'invoices.required' => 'El campo Facturas de la orden de despacho es requerido.',
            'invoices.array' => 'El campo Facturas de la orden de despacho debe ser un arreglo.',
            'invoices.*.value.required' => 'El campo Valor de la factura de la orden de despacho es requerido.',
            'invoices.*.value.numeric' => 'El campo Valor de la factura de la orden de despacho debe ser numerico.',
            'invoices.*.reference.required' => 'El campo Referencia de la factura de la orden de despacho es requerido.',
            'invoices.*.reference.string' => 'El campo Referencia de la factura debe ser una cadena de caracteres.',
            'invoices.*.reference.max' => 'El campo Referencia de la factura de la orden de despacho no debe exceder los 255 caracteres.',
            'invoices.*.date.required' => 'El campo Fecha de la facturacion de la factura de la orden de despacho es requerido.',
            'invoices.*.date.date_format' => 'El campo Fecha de la facturacion de la factura de la orden de despacho debe tener un formato de fecha valido.',
            'invoices.*.supports.required' => 'El campo Soporte de la Facturas de la orden de despacho es requerido.',
            'invoices.*.supports.array' => 'El campo Soporte de la Facturas de la orden de despacho debe ser un arreglo.',
            'invoices.*.supports.*.file' => 'El Soporte de la Factura #:position debe ser un archivo.',
            'invoices.*.supports.*.mimes' => 'El Soporte de la Factura #:position debe tener una extensión válida (jpeg, jpg, png, gif, pdf, txt, docx, xlsx, xlsm, xlsb, xltx).'
        ];
    }
}
