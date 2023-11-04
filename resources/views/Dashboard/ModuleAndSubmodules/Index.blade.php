@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Modulos y Submodulos</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item">ModulesAndSubmodules</li>
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
                                    <a class="nav-link active" type="button" onclick="CreateModuleAndSubmodulesModal()" title="Agregar modulo y submodulos">
                                        <i class="fas fa-shield-plus"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="modulesAndSubmodules" class="table table-bordered table-hover dataTable dtr-inline">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th colspan="8">Información</th>
                                            <th colspan="2">Gestionar</th>
                                        </tr>
                                        <tr>
                                            <th>#</th>
                                            <th>Modulo</th>
                                            <th>Icono</th>
                                            <th>Rol de Accesso</th>
                                            <th>Sub modulos</th>
                                            <th>Rutas</th>
                                            <th>Iconos</th>
                                            <th>Permisos de Accesso</th>
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
        @include('Dashboard.ModuleAndSubmodules.Create')
        @include('Dashboard.ModuleAndSubmodules.Edit')
    </section>
@endsection
@section('script')
    <script src="{{ asset('js/Dashboard/ModuleAndSubmodules/DataTableIndex.js') }}"></script>
    <script src="{{ asset('js/Dashboard/ModuleAndSubmodules/Create.js') }}"></script>
    <script src="{{ asset('js/Dashboard/ModuleAndSubmodules/Edit.js') }}"></script>
    <script src="{{ asset('js/Dashboard/ModuleAndSubmodules/Delete.js') }}"></script>
    <script src="{{ asset('js/Dashboard/ModuleAndSubmodules/Suggestion.js') }}"></script>
@endsection
