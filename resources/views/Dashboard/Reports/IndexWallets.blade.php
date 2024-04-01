@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Reporte de Cartera</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item">Reports</li>
                            <li class="breadcrumb-item">Wallets</li>
                            <li class="breadcrumb-item">Index</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
    </section>

    @if (session('success') || (session('info')) || (session('warning')) || (session('danger')))
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-bell mr-2"></i>Alertas</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                @include('Dashboard.Alerts.Success')
                                @include('Dashboard.Alerts.Info')
                                @include('Dashboard.Alerts.Warning')
                                @include('Dashboard.Alerts.Danger')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header p-2">
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="wallets" class="table table-bordered table-hover dataTable dtr-inline nowrap w-100">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>NOMBRE CLIENTE</th>
                                            <th>TIPO PERSONA</th>
                                            <th>TIPO CLIENTE</th>
                                            <th>TIPO DOCUMENTO</th>
                                            <th>NUMERO DOCUMENTO</th>
                                            <th>PAIS</th>
                                            <th>DEPARTAMENTO</th>
                                            <th>ZONA</th>
                                            <th>CIUDAD</th>
                                            <th>DIRECCION</th>
                                            <th>BARRIO</th>
                                            <th>CORREO</th>
                                            <th>TELEFONO 1</th>
                                            <th>TELEFONO 2</th>
                                            <th>CUPO</th>
                                            <th>DEUDA</th>
                                            <th>DISPONIBLE</th>
                                            <th>SUCURSAL</th>
                                            <th>CODIGO</th>
                                            <th>PAIS</th>
                                            <th>DEPARTAMENTO</th>
                                            <th>ZONA</th>
                                            <th>CIUDAD</th>
                                            <th>DIRECCION</th>
                                            <th>BARRIO</th>
                                            <th>CORREO</th>
                                            <th>TELEFONO 1</th>
                                            <th>TELEFONO 2</th>
                                            <th>DEUDA</th>
                                            <th>PAGO</th>
                                            <th>BALANCE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('js/Dashboard/Reports/DataTableIndexWallets.js') }}"></script>
@endsection
