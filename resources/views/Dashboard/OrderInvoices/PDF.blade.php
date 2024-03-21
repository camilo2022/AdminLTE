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
        td {
            border: 1px solid #a7a7a7;
            text-align: left;
            padding: 8px;
        }
    </style>
</head>
    @foreach($orderDispatch->order_packing->order_packages as $index => $orderPackage)
        <body>
            <div class="col-lg-12">
                <table>
                    <thead>
                        <tr>
                            <td class="text-center" rowspan="3">                           
                                <img src="data:image/png;base64,{{ base64_encode($orderPackage->qrCode) }}">                            
                            </td>
                            <td style="width:10%">FECHA:</td>
                            <td colspan="3">{{Carbon::now()}}</td>
                            <td class="text-center" rowspan="3">
                                <div class="title m-b-md">
                                    <img src="data:image/png;base64,{{ base64_encode($orderPackage->qrCode) }}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>FACTURA:</td>
                            <td colspan="3"></td>
                        </tr>                   
                        <tr>
                            <td>EMPAQUE ({{ $orderPackage->package_type->name }}):</td>
                            <td class="text-center">{{ $index + 1}}</td>
                            <td class="text-center">DE</td>
                            <td class="text-center">{{ $orderDispatch->order_packing->order_packages->count() }}</td>
                        </tr>
                    </thead>
                </table>
            </div>
        </body>
    @endforeach

</html>