@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Reporte de Produccion</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item">Reports</li>
                            <li class="breadcrumb-item">Productions</li>
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
                                <table id="productions" class="table table-bordered table-hover dataTable dtr-inline nowrap w-100">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>PEDIDO ID</th>
                                            <th>CLIENTE</th>
                                            <th>TIPO PERSONA</th>
                                            <th>TIPO CLIENTE</th>
                                            <th>TIPO DOCUMENTO</th>
                                            <th>DOCUMENTO</th>
                                            <th>SUCURSAL</th>
                                            <th>CODIGO</th>
                                            <th>PAIS</th>
                                            <th>DEPARTAMENTO</th>
                                            <th>ZONA</th>
                                            <th>CIUDAD</th>
                                            <th>DIRECCION</th>
                                            <th>BARRIO</th>
                                            <th>TRANSPORTADORA</th>
                                            <th>CANAL DE VENTA</th>
                                            <th>TIPO DESPACHO</th>
                                            <th>CUANDO DESPACHAR</th>
                                            <th>VENDEDOR</th>
                                            <th>ESTADO VENDEDOR</th>
                                            <th>FECHA VENDEDOR</th>
                                            <th>OBSERVACION VENDEDOR</th>
                                            <th>CARTERA</th>
                                            <th>ESTADO CARTERA</th>
                                            <th>FECHA CARTERA</th>
                                            <th>OBSERVACION CARTERA</th>
                                            <th>ESTADO DESPACHO</th>
                                            <th>FECHA DESPACHO</th>
                                            <th>CORRERIA</th>
                                            <th>COLECCION</th>
                                            <th>ORDEN DESPACHO ID</th>
                                            <th>FILTRADOR</th>
                                            <th>ESTADO ORDEN DESPACHO</th>
                                            <th>FECHA ORDEN DESPACHO</th>
                                            <th>CONSECUTIVO ORDEN DESPACHO</th>
                                            <th>ESTADO DE PAGO ORDEN DESPACHO</th>
                                            <th>FACTURADOR</th>
                                            <th>FECHA FACTURACION ORDEN DESPACHO</th>
                                            <th>DETALLE ID</th>
                                            <th>DETALLE DESPACHO ID</th>
                                            <th>PRODUCTO</th>
                                            <th>COLOR</th>
                                            <th>TONO</th>
                                            <th>PRECIO</th>
                                            @foreach($sizes as $size)
                                            <th>{{ $size->code }}</th>
                                            @endforeach
                                            <th>FECHA DETALLE VENDEDOR</th>
                                            <th>OBSERVACION DETALLE VENDEDOR</th>
                                            <th>DETALLE CARTERA</th>
                                            <th>FECHA DETALLE CARTERA</th>
                                            <th>DETALLE DESPACHADOR</th>
                                            <th>FECHA DETALLE DESPACHADOR</th>
                                            <th>DETALLE ESTADO</th>
                                            <th>DETALLE DESPACHO ESTADO</th>
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
    <script src="{{ asset('js/Dashboard/Reports/DataTableIndexProductions.js') }}"></script>
@endsection
