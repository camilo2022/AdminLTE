@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Clientes</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item">Clients</li>
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
                                    <a class="nav-link active" type="button" onclick="CreateClientModal()" title="Agregar cliente.">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="clients" class="table table-bordered table-hover dataTable dtr-inline nowrap w-100">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Razon social</th>
                                            <th>Tipo de persona</th>
                                            <th>Tipo de cliente</th>
                                            <th>Tipo de documento</th>
                                            <th>N° documento</th>
                                            <th>Pais</th>
                                            <th>Departamento</th>
                                            <th>Ciudad</th>
                                            <th>Direccion</th>
                                            <th>Barrio</th>
                                            <th>Correo electronico</th>
                                            <th>N° telefono</th>
                                            <th>N° telefono</th>
                                            <th>Cupo</th>
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
        @include('Dashboard.Clients.Create')
        @include('Dashboard.Clients.Edit')
        @include('Dashboard.ClientBranches.index')
        @include('Dashboard.ClientBranches.Create')
        @include('Dashboard.ClientBranches.Edit')
        @include('Dashboard.People.Create')
        @include('Dashboard.People.Edit')
    </section>
@endsection
@section('script')
<script src="{{ asset('js/Dashboard/Clients/DataTableIndex.js') }}"></script>
<script src="{{ asset('js/Dashboard/Clients/Create.js') }}"></script>
<script src="{{ asset('js/Dashboard/Clients/Edit.js') }}"></script>
<script src="{{ asset('js/Dashboard/Clients/Delete.js') }}"></script>
<script src="{{ asset('js/Dashboard/Clients/Restore.js') }}"></script>

<script src="{{ asset('js/Dashboard/ClientBranches/DataTableIndex.js') }}"></script>
<script src="{{ asset('js/Dashboard/ClientBranches/Index.js') }}"></script>
<script src="{{ asset('js/Dashboard/ClientBranches/Create.js') }}"></script>
<script src="{{ asset('js/Dashboard/ClientBranches/Edit.js') }}"></script>
<script src="{{ asset('js/Dashboard/ClientBranches/Delete.js') }}"></script>
<script src="{{ asset('js/Dashboard/ClientBranches/Restore.js') }}"></script>

<script src="{{ asset('js/Dashboard/People/Create.js') }}"></script>
<script src="{{ asset('js/Dashboard/People/Edit.js') }}"></script>
@endsection
