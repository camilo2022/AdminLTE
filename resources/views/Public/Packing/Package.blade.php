<!doctype html>
<html class="no-js " lang="en">


    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
        <title>{{ "{$orderPackage->order_packing->order_dispatch->consecutive}-{$orderPackage->package_type->name}-#{$number}" }}</title>
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
        <div style="padding-top:1.8cm !important; padding-right: 1.8cm !important; padding-left: 14cm !important; text-align: center;">
            <b>ORDEN DE DESPACHO <br> {{ $orderPackage->order_packing->order_dispatch->consecutive }} </b> <br>
        </div>
        <table style="padding-top:1.2cm !important; padding-left:1.5cm !important; padding-right:1.5cm !important;" class="table">
            <thead>
                <tr>
                    <th colspan="6" class="cell">
                        INFORMACION DEL DESPACHO
                    </th>
                </tr>
                <tr>
                    <th colspan="3" class="cell">{{ $orderPackage->order_packing->order_dispatch->order->client->name }}</th>
                    <th colspan="3" style="background-color: #d4d4d4;" class="cell">ORDEN DE DESPACHO N° {{ $orderPackage->order_packing->order_dispatch->consecutive }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="cell">{{ "{$orderPackage->order_packing->order_dispatch->order->client->document_type->code}:"  }}</td>
                    <td colspan="2" class="cell">{{ $orderPackage->order_packing->order_dispatch->order->client->document_number }}</td>
                    <td class="cell">TELEFONO:</td>
                    <td class="cell">{{ $orderPackage->order_packing->order_dispatch->order->client->telephone_number_first ?? $orderPackage->order_packing->order_dispatch->order->client_branch->telephone_number_first }}</td>
                    <td class="cell">{{ $orderPackage->order_packing->order_dispatch->order->client->telephone_number_second ?? $orderPackage->order_packing->order_dispatch->order->client_branch->telephone_number_second }}</td>
                </tr>
                <tr>
                    <td class="cell">PAIS:</td>
                    <td colspan="2" class="cell">{{ $orderPackage->order_packing->order_dispatch->order->client_branch->country->name }}</td>
                    <td class="cell">DESPACHAR A:</td>
                    <td colspan="2" class="cell">{{ "{$orderPackage->order_packing->order_dispatch->order->client_branch->name} {$orderPackage->order_packing->order_dispatch->order->client->document_number} - {$orderPackage->order_packing->order_dispatch->order->client_branch->code}" }}</td>
                </tr>
                <tr>
                    <td class="cell">DEPARTAMENTO:</td>
                    <td colspan="2" class="cell">{{ $orderPackage->order_packing->order_dispatch->order->client_branch->departament->name }}</td>
                    <td class="cell">BARRIO:</td>
                    <td colspan="2" class="cell">{{ $orderPackage->order_packing->order_dispatch->order->client_branch->neighborhood }}</td>
                </tr>
                <tr>
                    <td class="cell">CIUDAD:</td>
                    <td colspan="2" class="cell">{{ $orderPackage->order_packing->order_dispatch->order->client_branch->city->name }}</td>
                    <td class="cell">DIRECCION:</td>
                    <td colspan="2" class="cell">{{ $orderPackage->order_packing->order_dispatch->order->client_branch->address }}</td>
                </tr>
            </tbody>
        </table><br>
        <table style="padding-top:0.3cm !important; padding-left:1.5cm !important; padding-right:1.5cm !important;" class="table">
            <thead>
                <tr>
                    <th colspan="7" class="cell">
                        INFORMACION DEL EMPAQUE
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="cell">PESO</th>
                    <td colspan="2" class="cell">{{ $orderPackage->weight }}</td>
                    <th colspan="2" class="cell">CANTIDAD</th>
                    <td colspan="2" class="cell">{{ $orderPackage->order_package_details->pluck('order_package_detail_quantities')->flatten()->pluck('quantity')->sum() . " UNDS" }}</td>
                </tr>
                <tr>
                    <th class="cell">TIPO</th>
                    <td colspan="2" class="cell">{{ $orderPackage->package_type->name }}</td>
                    <th class="cell">N°</th>
                    <td class="cell">{{ $number }}</td>
                    <th class="cell">DE</th>
                    <td class="cell">{{ $orderPackage->order_packing->order_packages->count() }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th class="cell">EMPACADOR</th>
                    <td colspan="6" class="cell">{{ strtoupper($orderPackage->order_packing->packing_user->name . " " . $orderPackage->order_packing->packing_user->last_name) }}</td>
                </tr>
            </tfoot>
        </table><br>
        <table style="padding-top:0.3cm !important; padding-left:1.5cm !important; padding-right:1.5cm !important; padding-bottom:2cm !important;" class="table">
            <thead>
                <tr>
                    <th colspan="{{ $sizes->count() + 2 }}" class="cell">
                        DETALLES DEL EMPAQUE
                    </th>
                </tr>
                <tr>
                    <th class="cell">REFERENCIA</th>
                    @foreach ($sizes as $size)
                    <th class="cell">{{ $size->code }}</th>
                    @endforeach
                    <th class="cell">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderPackage->order_package_details as $orderPackageDetail)
                    <tr>
                        <td class="cell">{{ "{$orderPackageDetail->order_dispatch_detail->order_detail->product->code}-{$orderPackageDetail->order_dispatch_detail->order_detail->color->code}-{$orderPackageDetail->order_dispatch_detail->order_detail->tone->code}" }}</td>
                        @foreach ($sizes as $size)
                        @php
                            $quantity = $orderPackageDetail->order_package_detail_quantities()->whereHas('order_dispatch_detail_quantity.order_detail_quantity', fn($subQuery) => $subQuery->where('size_id', $size->id))->first();
                        @endphp
                        <th class="cell">{{ $quantity ? $quantity->quantity : 0 }}</th>
                        @endforeach
                        <th style="background-color: #d4d4d4;" class="cell">{{ $orderPackageDetail->order_package_detail_quantities->pluck('quantity')->sum() }}</th>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="cell">TOTAL</th>
                    @foreach ($sizes as $size)
                    <th style="background-color: #d4d4d4;" class="cell">{{ $orderPackage->order_package_details->pluck('order_package_detail_quantities')->flatten()->where('order_dispatch_detail_quantity.order_detail_quantity.size_id', $size->id)->pluck('quantity')->sum() }}</th>
                    @endforeach
                    <th style="background-color: #d4d4d4;" class="cell">{{ $orderPackage->order_package_details->pluck('order_package_detail_quantities')->flatten()->pluck('quantity')->sum() }}</th>
                </tr>
            </tfoot>
        </table>
    </body>
</html>
