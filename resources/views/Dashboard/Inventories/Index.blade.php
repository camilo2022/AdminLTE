@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Inventarios</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item">Inventories</li>
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
                                <li class="nav-item ml-auto">
                                    <a class="nav-link active" type="button" onclick="UploadInventoryModal()" title="Cargar inventarios">
                                        <i class="fas fa-upload"></i>
                                    </a>
                                </li>
                                <li class="nav-item ml-2">
                                    <form action="{{ route('Dashboard.Inventories.Download') }}" method="POST" name="DownloadInventories">
                                        @csrf
                                        <a class="nav-link active" type="button" onclick="DownloadInventory()" title="Descargar inventarios">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="inventories" class="table table-bordered table-hover dataTable dtr-inline w-100">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Producto</th>
                                            <th>Talla</th>
                                            <th>Bodega</th>
                                            <th>Color</th>
                                            <th>Tono</th>
                                            <th>Cantidad</th>
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
        @include('Dashboard.Inventories.Upload')
    </section>
@endsection
@section('script')
    <script src="{{ asset('js/Dashboard/Inventories/DataTableIndex.js') }}"></script>
    <script src="{{ asset('js/Dashboard/Inventories/Upload.js') }}"></script>
    <script src="{{ asset('js/Dashboard/Inventories/Download.js') }}"></script>
@endsection
