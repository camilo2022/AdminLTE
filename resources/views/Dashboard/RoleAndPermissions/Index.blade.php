@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Roles y Permisos</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item">RolesAndPermissions</li>
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
                                    <a class="nav-link active" type="button" onclick="CreateRoleAndPermissionsModal()" title="Agregar rol y permisos">
                                        <i class="fas fa-folder-plus"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="rolesAndPermissions" class="table table-bordered table-hover dataTable dtr-inline">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th colspan="3">Información</th>
                                            <th colspan="2">Gestionar</th>
                                        </tr>
                                        <tr>
                                            <th>#</th>
                                            <th>Rol</th>
                                            <th>Permisos</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>
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
        @include('Dashboard.RoleAndPermissions.Create')
        @include('Dashboard.RoleAndPermissions.Edit')
    </section>
@endsection
@section('script')
    <script src="{{ asset('js/Dashboard/RoleAndPermissions/DataTableIndex.js') }}"></script>
    <script src="{{ asset('js/Dashboard/RoleAndPermissions/Create.js') }}"></script>
    <script src="{{ asset('js/Dashboard/RoleAndPermissions/Edit.js') }}"></script>
    <script src="{{ asset('js/Dashboard/RoleAndPermissions/Delete.js') }}"></script>
    <script src="{{ asset('js/Dashboard/RoleAndPermissions/Suggestion.js') }}"></script>
@endsection
