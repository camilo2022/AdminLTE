<!doctype html>
<html class="no-js " lang="en">


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
    <title>{{ $orderDispatch->consecutive }}</title>
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
        table {
            width: 100% !important;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #a7a7a7;
            text-align: left;
            padding: 8px;
        }
    </style>
</head>


<body>
    <div class="col-lg-12">
        <table>
            <thead>
                <tr>
                    <th colspan="3" style="text-align: center; font-size: 20px;">{{ $orderDispatch->order->client->name }}</th>
                    <th colspan="3" style="text-align: center; background-color: #d4d4d4; font-size: 20px;">ORDEN DE DESPACHO N° {{ $orderDispatch->consecutive }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ "{$orderDispatch->order->client->document_type->code}:"  }}</td>
                    <td colspan="2">{{ $orderDispatch->order->client->document_number }}</td>
                    <td>TELEFONO:</td>
                    <td>{{ $orderDispatch->order->client->telephone_number_first ?? $orderDispatch->order->client_branch->telephone_number_first }}</td>
                    <td>{{ $orderDispatch->order->client->telephone_number_second ?? $orderDispatch->order->client_branch->telephone_number_second }}</td>
                </tr>
                <tr>
                    <td>PAIS:</td>
                    <td colspan="2">{{ $orderDispatch->order->client_branch->country->name }}</td>
                    <td>DESPACHAR A:</td>
                    <td colspan="2">{{ "{$orderDispatch->order->client_branch->name} {$orderDispatch->order->client->document_number} - {$orderDispatch->order->client_branch->code}" }}</td>
                </tr>
                <tr>
                    <td>DEPARTAMENTO:</td>
                    <td colspan="2">{{ $orderDispatch->order->client_branch->departament->name }}</td>
                    <td>BARRIO:</td>
                    <td colspan="2">{{ $orderDispatch->order->client_branch->neighborhood }}</td>
                </tr>
                <tr>
                    <td>CIUDAD:</td>
                    <td colspan="2">{{ $orderDispatch->order->client_branch->city->name }}</td>
                    <td>DIRECCION:</td>
                    <td colspan="2">{{ $orderDispatch->order->client_branch->address }}</td>
                </tr>
            </tbody>
        </table><br>
        <table>
            <thead>
                <tr>
                    <th style="text-align: center;">REFERENCIA</th>
                    @foreach ($sizes as $size)
                    <th style="text-align: center;">{{ $size->code }}</th>
                    @endforeach
                    <th style="text-align: center;">TOTAL</th>
                    <th style="text-align: center;">V.U.</th>
                    <th style="text-align: center;">V.T.</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $priceTotal = 0;
                @endphp
                @foreach ($orderDispatch->order_dispatch_details as $detail)
                    <tr>
                        <td style="text-align: center;">{{ "{$detail->order_detail->product->code}-{$detail->order_detail->color->code}-{$detail->order_detail->tone->code}" }}</td>
                        @foreach ($sizes as $size)
                        @php
                            $quantity = $detail->order_dispatch_detail_quantities->where('order_detail_quantity.size_id', $size->id)->first();
                        @endphp
                        <th style="text-align: center;">{{ $quantity ? $quantity->quantity : 0 }}</th>
                        @endforeach
                        @php
                            $priceTotal += ($detail->order_detail->product->price * $detail->order_dispatch_detail_quantities->pluck('quantity')->sum());
                        @endphp
                        <th style="text-align: center; background-color: #d4d4d4;">{{ $detail->order_dispatch_detail_quantities->pluck('quantity')->sum() }}</th>
                        <th style="text-align: center; background-color: #d4d4d4;">{{ '$ ' . number_format($detail->order_detail->product->price, 0, ',', '.') }}</th>
                        <th style="text-align: center; background-color: #d4d4d4;">{{ '$ ' . number_format(($detail->order_detail->product->price * $detail->order_dispatch_detail_quantities->pluck('quantity')->sum()), 0, ',', '.') }}</th>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th style="text-align: center;">TOTAL</th>
                    @foreach ($sizes as $size)
                    <th style="text-align: center; background-color: #d4d4d4;">{{ $orderDispatch->order_dispatch_details->pluck('order_dispatch_detail_quantities')->flatten()->where('order_detail_quantity.size_id', $size->id)->pluck('quantity')->sum() }}</th>
                    @endforeach
                    <th style="text-align: center; background-color: #d4d4d4;">{{ $orderDispatch->order_dispatch_details->pluck('order_dispatch_detail_quantities')->flatten()->pluck('quantity')->sum() }}</th>
                    <th style="text-align: center; background-color: #d4d4d4;">-</th>
                    <th style="text-align: center; background-color: #d4d4d4;">-</th>
                </tr>
                <tr>
                    @php
                        $formatter  = new NumeroALetras();
                        $formatter->conector = 'Y';
                        $number = $formatter->toMoney($priceTotal, 2, 'PESOS');
                    @endphp
                    <th colspan="{{$sizes->count() + 2}}" style="text-align: left; background-color: #d4d4d4;">{{ "SON: (EN LETRAS)  {$number}" }}</th>
                    <th colspan="2" style="text-align: right; background-color: #d4d4d4;">{{ '$ ' . number_format(($priceTotal), 2, ',', '.') . ' COP' }}</th>
                </tr>
            </tfoot>
        </table><br>
        <table>
            <thead>
                <tr>
                    <th style="text-align: center;">-</th>
                    <th colspan="3" style="text-align: center;">VENDEDOR</th>
                    <th colspan="3" style="text-align: center;">CARTERA</th>
                    <th colspan="3" style="text-align: center;">DESPACHO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th style="text-align: center;">APROBADO POR:</th>
                    <th colspan="3" style="text-align: center;">{{ "{$orderDispatch->order->seller_user->name} {$orderDispatch->order->seller_user->last_name}" }}</th>
                    <th colspan="3" style="text-align: center;">{{ "{$orderDispatch->order->wallet_user->name} {$orderDispatch->order->wallet_user->last_name}" }}</th>
                    <th colspan="3" style="text-align: center;">{{ "{$orderDispatch->dispatch_user->name} {$orderDispatch->dispatch_user->last_name}" }}</th>
                </tr>
                <tr>
                    <th style="text-align: center;">FECHA:</th>
                    <th colspan="3" style="text-align: center;">{{ $orderDispatch->order->seller_date }}</th>
                    <th colspan="3" style="text-align: center;">{{ $orderDispatch->order->wallet_date }}</th>
                    <th colspan="3" style="text-align: center;">{{ Carbon::parse($orderDispatch->created_at)->format('Y-m-d H:i:s') }}</th>
                </tr>
                <tr>
                    <th style="text-align: center;">OBSERVACIONES:</th>
                    <th colspan="3" style="text-align: center;">{{ $orderDispatch->order->seller_observation }}</th>
                    <th colspan="3" style="text-align: center;">{{ $orderDispatch->order->wallet_observation }}</th>
                    <th colspan="3" style="text-align: center;">{{ "N/A" }}</th>
                </tr>
            </tbody>
        </table>
    </div>

</body>

</html>
