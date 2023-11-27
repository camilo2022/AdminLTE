@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Productos</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item">Products</li>
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
                                    <a class="nav-link active" type="button" onclick="CreateProductModal()" title="Agregar producto">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </li>
                                <li class="nav-item ml-auto">
                                    <a class="nav-link active" type="button" onclick="UploadProductModal()" title="Cargar productos">
                                        <i class="fas fa-upload"></i>
                                    </a>
                                </li>
                                <li class="nav-item ml-2">
                                    <a class="nav-link active" type="button" onclick="DownloadProduct()" title="Descargar productos">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="products" class="table table-bordered table-hover dataTable dtr-inline">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Codigo</th>
                                            <th>Descripcion</th>
                                            <th>Precio</th>
                                            <th>Linea</th>
                                            <th>Categoria</th>
                                            <th>Subcategoria</th>
                                            <th>Modelo</th>
                                            <th>Marca</th>
                                            <th>Correria</th>
                                            <th>Colores</th>
                                            <th>Tallas</th>
                                            <th>Estado</th>
                                            <th style="width: 200px !important;">Acciones</th>
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
        @include('Dashboard.Products.Create')
        @include('Dashboard.Products.Edit')
        @include('Dashboard.Products.Upload')
    </section>
@endsection
@section('script')
    <script src="{{ asset('js/Dashboard/Products/DataTableIndex.js') }}"></script>
    <script src="{{ asset('js/Dashboard/Products/Create.js') }}"></script>
    <script src="{{ asset('js/Dashboard/Products/Edit.js') }}"></script>
    <script src="{{ asset('js/Dashboard/Products/Delete.js') }}"></script>
    <script src="{{ asset('js/Dashboard/Products/Restore.js') }}"></script>
    <script src="{{ asset('js/Dashboard/Products/Upload.js') }}"></script>
@endsection
