<!doctype html>
<html class="no-js " lang="en">


    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
        <title>{{ $order->id }}</title>
        <link rel="icon" href="" type="image/x-icon"> <!-- Favicon-->

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Tempusdominus Bbootstrap 4 -->
        <link rel="stylesheet" href="{{ asset('css/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="{{ asset('css/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
        <!-- Custom Css -->
        <link rel="stylesheet" href="{{ asset('css/plugins/pdf/style.min.css') }}">

        <style>
            .table {
                width: 100% !important;
                border-collapse: collapse;
            }
            .cell {
                border: 1px solid #a7a7a7;
                padding: 8px;
                font-size: 12px;
                text-align: center;
            }
            body {
                background-image: url("{{ asset('images/membrete.jpg') }}");
                background-repeat: no-repeat;
                background-size: cover;
                margin: 0;
                padding: 0;
            }
            html{
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-position: center center;
                background-size: 100%;
                margin: 0px;
            }
        </style>
    </head>
    <body>
        <div style="padding-top:1.8cm !important; padding-right: 1.8cm !important; padding-left: 15cm !important; text-align: center;">
            <b>ORDEN DE PEDIDO <br> N° {{ $order->id }}</b>
        </div>
        <table style="padding-top:2cm !important; padding-left:1.5cm !important; padding-right:1.5cm !important;" class="table">
            <thead>
                <tr>
                    <th colspan="6" class="cell">
                        INFORMACION DESPACHO DEL PEDIDO
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left;" class="cell">
                        SEÑORES:
                    </th>
                    <td style="text-align: left;" class="cell">
                        {{ $order->client->name . ' - ' . $order->client_branch->name }}
                    </td>
                    <th style="text-align: left;" class="cell">
                        {{ strtoupper($order->client->document_type->code . ': ') }}
                    </th>
                    <td style="text-align: left;" class="cell">
                        {{ $order->client->document_number }}
                    </td>
                    <th style="text-align: left;" class="cell">
                        SUCURSAL:
                    </th>
                    <td style="text-align: left;" class="cell">
                        {{ $order->client_branch->code }}
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;" class="cell">
                        UBICACION:
                    </th>
                    <td style="text-align: left;" class="cell" colspan="5">
                        {{ $order->client_branch->departament->name . ' - ' . $order->client_branch->city->name }}
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;" class="cell">
                        DIRECCION:
                    </th>
                    <td style="text-align: left;" class="cell" colspan="2">
                        {{ $order->client_branch->address }}
                    </td>
                    <th style="text-align: left;" class="cell">
                        BARRIO:
                    </th>
                    <td style="text-align: left;" class="cell" colspan="2">
                        {{ $order->client_branch->neighborhood }}
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;" class="cell">
                        TELEFONOS:
                    </th>
                    <td style="text-align: left;" class="cell" colspan="2">
                        {{ $order->client->telephone_number_first . ' - ' . $order->client->telephone_number_second . ' ' . $order->client_branch->telephone_number_first . ' - ' . $order->client_branch->telephone_number_second }}
                    </td>
                    <th style="text-align: left;" class="cell">
                        CORREO:
                    </th>
                    <td style="text-align: left;" class="cell" colspan="2">
                        {{ $order->client->email . ' ' . $order->client_branch->email }}
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;" class="cell">
                        VENDEDOR:
                    </th>
                    <td style="text-align: left;" class="cell" colspan="2">
                        {{ $order->seller_user->name . ' ' . $order->seller_user->last_name }}
                    </td>
                    <th style="text-align: left;" class="cell">
                        FECHA:
                    </th>
                    <td style="text-align: left;" class="cell" colspan="2">
                        {{ $order->seller_date }}
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;" class="cell">
                        OBSERVACION:
                    </th>
                    <td style="text-align: left;" class="cell" colspan="2">
                        {{ $order->seller_observation }}
                    </td>
                    <th style="text-align: left;" class="cell">
                        DESPACHO:
                    </th>
                    <td style="text-align: left;" class="cell" colspan="2">
                        {{  $order->dispatch == 'De inmediato' ? $order->dispatch : $order->dispatch . ' ' . $order->dispatch_date }}
                    </td>
                </tr>
            </thead>
        </table>
        <table style="padding-top:1cm !important; padding-left:1.5cm !important; padding-right:1.5cm !important;" class="table">
            <thead>
                <tr>
                    <th colspan="{{ $sizes->count() + 4 }}" class="cell">
                        DETALLES DEL PEDIDO
                    </th>
                </tr>
                <tr>
                    <th class="cell">REFERENCIA</th>
                    @foreach ($sizes as $size)
                    <th class="cell">{{ $size->code }}</th>
                    @endforeach
                    <th class="cell">TOTAL</th>
                    <th class="cell">V.U.</th>
                    <th class="cell">V.T.</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $priceTotal = 0;
                @endphp
                @foreach ($order->order_details as $detail)
                    <tr>
                        <td class="cell">{{ "{$detail->product->code}-{$detail->color->code}-{$detail->tone->code}" }}</td>
                        @foreach ($sizes as $size)
                        @php
                            $quantity = $detail->order_detail_quantities->where('size_id', $size->id)->first();
                        @endphp
                        <th class="cell">{{ $quantity ? $quantity->quantity : 0 }}</th>
                        @endforeach
                        @php
                            $priceTotal += ($detail->product->price * $detail->order_detail_quantities->pluck('quantity')->sum());
                        @endphp
                        <th style="background-color: #d4d4d4;" class="cell">{{ $detail->order_detail_quantities->pluck('quantity')->sum() }}</th>
                        <th style="background-color: #d4d4d4;" class="cell">{{ '$ ' . number_format($detail->product->price, 0, ',', '.') }}</th>
                        <th style="background-color: #d4d4d4;" class="cell">{{ '$ ' . number_format(($detail->product->price * $detail->order_detail_quantities->pluck('quantity')->sum()), 0, ',', '.') }}</th>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="cell">TOTAL</th>
                    @foreach ($sizes as $size)
                    <th style="background-color: #d4d4d4;" class="cell">{{ $order->order_details->pluck('order_detail_quantities')->flatten()->where('size_id', $size->id)->pluck('quantity')->sum() }}</th>
                    @endforeach
                    <th style="background-color: #d4d4d4;" class="cell">{{ $order->order_details->pluck('order_detail_quantities')->flatten()->pluck('quantity')->sum() }}</th>
                    <th style="background-color: #d4d4d4;" class="cell">-</th>
                    <th style="background-color: #d4d4d4;" class="cell">-</th>
                </tr>
                <tr>
                    @php
                        $formatter  = new NumeroALetras();
                        $formatter->conector = 'Y';
                        $number = $formatter->toMoney($priceTotal, 2, 'PESOS');
                    @endphp
                    <th colspan="{{$sizes->count() + 2}}" style="text-align: left; background-color: #d4d4d4;" class="cell">{{ "SON: (EN LETRAS)  {$number}" }}</th>
                    <th colspan="2" style="text-align: right; background-color: #d4d4d4;" class="cell">{{ '$ ' . number_format(($priceTotal), 2, ',', '.') . ' COP' }}</th>
                </tr>
            </tfoot>
        </table><br>
        <div style="padding-top:0.5cm !important; padding-left:1.5cm !important; padding-right:1.5cm !important; font-size: 12px;">
            <p>Nota: Puede darle click a la referencia para ver las fotos del producto que encargaste.</p>
        </div>
    </body>
</html>