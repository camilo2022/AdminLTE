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

        th,
        th {
            border: 1px solid #a7a7a7;
            text-align: left;
            padding: 8px;
            font-size: 12px;
        }
    </style>
</head>
    @foreach($orderDispatch->order_packing->order_packages as $index => $orderPackage)
        <body>
            <div class="col-lg-12">
                <table>
                    <thead>
                        <tr>
                            <th class="text-center" rowspan="3">   
                                <img src="{{ asset('images/logo.png') }}">
                            </th>
                            <th>FECHA:</th>
                            <th colspan="3">{{Carbon::now()}}</th>
                            <th class="text-center" rowspan="3" style="width:20%">
                                <img src="data:image/png;base64,{{ base64_encode($orderPackage->qrCode) }}">
                            </th>
                        </tr>
                        <tr>
                            <th>FACTURAS:</th>
                            <th colspan="3">
                                {{ implode(' | ', $orderDispatch->invoices->pluck('reference')->toArray()) }}
                            </th>
                        </tr>                   
                        <tr>
                            <th>EMPAQUE ({{ $orderPackage->package_type->name }}):</th>
                            <th class="text-center">{{ $index + 1}}</th>
                            <th class="text-center">DE</th>
                            <th class="text-center">{{ $orderDispatch->order_packing->order_packages->count() }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>DESTINATARIO:</th>
                            <th colspan="2">{{ $orderDispatch->order->client_branch->name }}</th>
                            <th>{{ strtoupper($orderDispatch->order->client->document_type->code) }}:</th>
                            <th colspan="2">{{ $orderDispatch->order->client->document_number }}</th>
                        </tr>
                        <tr>
                            <th>UBICACION:</th>
                            <th colspan="2">{{ strtoupper($orderDispatch->order->client_branch->departament->name . " - " . $orderDispatch->order->client_branch->city->name) }}</th>
                            <th>DIRECCION:</th>
                            <th colspan="2">{{ strtoupper($orderDispatch->order->client_branch->address . ". " . $orderDispatch->order->client_branch->neighborhood) }}</th>
                        </tr>
                        <tr>
                            <th>PESO - PRENDAS:</th>
                            <th colspan="2">{{ $orderPackage->weight . " - " . $orderPackage->order_package_details->pluck('order_package_detail_quantities')->flatten()->pluck('quantity')->sum() . " UNDS" }}</th>
                            <th>N° DESPACHO:</th>
                            <th colspan="2">{{ $orderDispatch->consecutive }}</th>
                        </tr>
                        <tr>
                            <th>TELEFONOS:</th>
                            <th colspan="2">{{ $orderDispatch->order->client->telephone_number_first ?? $orderDispatch->order->client_branch->telephone_number_first . " - " . $orderDispatch->order->client->telephone_number_second ?? $orderDispatch->order->client_branch->telephone_number_second }}</th>
                            <th>VENDEDOR:</th>
                            <th colspan="2">{{ strtoupper($orderDispatch->order->seller_user->name . " " . $orderDispatch->order->seller_user->last_name) }}</th>
                        </tr>
                        <tr>
                            <th colspan="6" style="text-align: center;">
                                Este empaque es propiedad de la MARIANGEL FULL MODA SAS. En caso de perdida por favor comunircase a las lineas Tel: 5834481 Cel: 3118800104 - 3114374088 - 3138092414 o al correo electronico mariangel.indu@hotmail.com.
                            </th>
                        </tr>
                        <tr>
                            <th colspan="6" style="text-align: center;">
                                MARIANGEL FULL MODA SAS | 901.292.098 | CLL 7B 18 87 BARRIO SAN MIGUEL | SAN JOSÉ DE CÚCUTA - NORTE DE SANTANDER
                            </th>
                        </tr>
                        <tr>
                            <th colspan="6">
                                <img style="width:100%;" src="{{asset('images/dian.jpg')}}"> 
                            </th> 
                        </tr>
                    </tbody>
                </table>
            </div>
        </body>
    @endforeach

</html>