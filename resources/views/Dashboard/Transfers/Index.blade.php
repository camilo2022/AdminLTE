@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Transferencias</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item">Transfers</li>
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
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link active" type="button" onclick="CreateTransferModal()" title="Agregar transferencia.">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="transfers" class="table table-bordered table-hover dataTable dtr-inline nowrap w-100">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Consecutivo</th>
                                            <th>Bodega Envio</th>
                                            <th>Usuario Envio</th>
                                            <th>Fecha Envio</th>
                                            <th>Observacion Envio</th>
                                            <th>Bodega Recibe</th>
                                            <th>Usuario Recibe</th>
                                            <th>Fecha Recibe</th>
                                            <th>Observacion Recibe</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
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
        @include('Dashboard.Transfers.Create')
        @include('Dashboard.Transfers.Edit')
        @include('Dashboard.Transfers.Show')
        @include('Dashboard.TransferDetails.Create')
        @include('Dashboard.TransferDetails.Edit')
    </section>
@endsection
@section('script')
<script src="{{ asset('js/Dashboard/Transfers/DataTableIndex.js') }}"></script>
<script src="{{ asset('js/Dashboard/Transfers/Create.js') }}"></script>
<script src="{{ asset('js/Dashboard/Transfers/Edit.js') }}"></script>
<script src="{{ asset('js/Dashboard/Transfers/Show.js') }}"></script>
<script src="{{ asset('js/Dashboard/Transfers/Delete.js') }}"></script>
<script src="{{ asset('js/Dashboard/Transfers/Approve.js') }}"></script>
<script src="{{ asset('js/Dashboard/Transfers/Cancel.js') }}"></script>

<script src="{{ asset('js/Dashboard/TransferDetails/DataTableIndex.js') }}"></script>
<script src="{{ asset('js/Dashboard/TransferDetails/Create.js') }}"></script>
<!-- <script src="{{ asset('js/Dashboard/TransferDetails/Edit.js') }}"></script>
<script src="{{ asset('js/Dashboard/TransferDetails/Delete.js') }}"></script> -->
<script src="{{ asset('js/Dashboard/TransferDetails/Pending.js') }}"></script>
<script src="{{ asset('js/Dashboard/TransferDetails/Cancel.js') }}"></script>
@endsection
