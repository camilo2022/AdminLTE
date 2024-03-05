<!doctype html>
<html class="no-js " lang="en">


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
    <title>Redsuelva</title>
    <link rel="icon" href="{{ asset('img/rued.png') }}" type="image/x-icon"> <!-- Favicon-->

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/jvectormap/jquery-jvectormap-2.0.3.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('plugins/charts-c3/plugin.css') }}" />

    <link rel="stylesheet" href="{{ asset('plugins/morrisjs/morris.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('plugins/jquery-datatable/dataTables.bootstrap4.min.css') }}">


    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
    <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset('css/style.min.css') }}">

    <link rel="stylesheet" src="{{ asset('css/app.css') }}">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

</head>


<body>

    <header>
        <img src="{{ asset('./images/encabezado.png') }}" />
    </header>





    <div class="container">

        <center>
            <h5 style="position: relative; font-family:'Courier New', Courier, monospace;font-size:19px;bottom:10%;">
                COGUASIMALES SERVICE S.A.S
            </h5>

            <h5 style="position:relative;font-family:'Courier New', Courier, monospace;font-size:18px;bottom:10%;">CON
                NIT 900469704-5</h5>
            <br>
            <br>
            <h5 style="position:relative;font-family:'Times New Roman', Times, serif;font-size:17px;bottom:5%;">HACE
                CONSTAR QUE</h5>
        </center>

        <br><br>

        <p style="position: relative;bottom:1%;text-align:justify;">
            El señor <label
                style="position: relative;top:14px;font-weight:bold;font-size:14px;">{{ $queryic->nombrecompleto }}</label>
            &nbsp;<label style="position: relative;top:14px;font-weight:bold;font-size:14px;">{{ $queryic->lastname }}</label>
            Identificado con la
            Cédula de Ciudadanía No.{{ $resultadocedul }}de Cúcuta,
            labora en esta Empresa con un contrato a término <font style="font-weight:bold;font-size:14px;">
                {{ $queryic->name_type_contract }}</font> desde el <font style="font-weight:bold;font-size:14px;">
                {{ $fechaAdmision }}</font> desempeñando el cargo
            de <font style="font-weight:bold;font-size:14px;">{{ $queryic->cargo }}</font>. Devengando un salario mensual de <font
                style="font-weight:bold;font-size:14px;">{{ $numero_en_palabras }} </font> (M/CTE) (${{ $resultadovamor }}), Más
            auxilio de transporte por el
            valor de <font style="font-weight:bold;font-size:14px;">${{ $resultadoauxiliotrasporte }}.</font>
        </p>

        <br>

        <p>

            Se expide la presente solicitud a quien pueda interesar el dia {{-- {{ $carbon1 }} --}}.

        </p>

        <br>
        <br>

        <img src="{{ asset('./images/firma.png') }}" style="position:absolute;width:16%;height:8vh;">
        <h5 class="mt-5" style="font-weight:bold;font-size:15px;">
            LUZ MARITZA CASTRO MUÑOZ<br>
            DIRECTORA DE TALENTO HUMANO.
        </h5>

    </div>

    <footer>

        <img src="{{ asset('./images/piePagina.png') }}" style="position: absolute;width:100%">

    </footer>

</body>

</html>
