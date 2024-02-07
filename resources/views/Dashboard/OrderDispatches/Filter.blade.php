@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Filtro de Pedidos</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item">Orders</li>
                            <li class="breadcrumb-item">Dispatch</li>
                            <li class="breadcrumb-item">Filter</li>
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
                                    <a class="btn btn-success text-white" id="FilterReferenceOrder" type="button" title="Filtrar referencia.">
                                        <i class="fas fa-check"></i>
                                    </a>
                                </li>
                                <li class="nav-item ml-2">
                                    <a class="btn btn-warning text-white" id="SearchReferenceOrder" type="button" title="Buscar referencia.">
                                        <i class="fas fa-magnifying-glass"></i>
                                    </a>
                                </li>
                                <li class="nav-item ml-2">
                                    <a class="btn btn-primary text-white" id="PreviousReferenceOrder" type="button" title="Anterior referencia.">
                                        <i class="fas fa-circle-caret-left"></i>
                                    </a>
                                </li>
                                <li class="nav-item ml-2">
                                    <a class="btn btn-danger text-white" id="NextReferenceOrder" type="button" title="Siguiente referencia.">
                                        <i class="fas fa-circle-caret-right"></i>
                                    </a>
                                </li>
                                <li class="nav-item ml-auto mr-auto">
                                    <a class="btn btn-info text-white w-100" id="Reference" type="button" title="Referencia." data-product_id="" data-color_id="" data-tone_id="">
                                        
                                    </a>
                                </li>
                                <li class="nav-item ml-auto mr-1">
                                    <span class="badge badge-info" id="PositionReference">0</span> | <span class="badge badge-info" id="QuantityReference">0</span>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="Inventory" class="table table-bordered table-hover dataTable dtr-inline nowrap w-100">
                                    <thead class="thead-dark" id="InventoryHead">
                                    </thead>
                                    <tbody id="InventoryBody">
                                        <tr id="InventoryBodyWarehouseNoDiscount">
                                        
                                        </tr>
                                        <tr id="InventoryBodyWarehouseDiscount">
                                            
                                        </tr>
                                    </tbody>
                                    <tfoot class="thead-dark" id="InventoryFoot">
                                        <tr id="InventoryFootQuentityDiscount">
                                        
                                        </tr>
                                    </tfoot>
                                </table>
                                <br>
                                <table id="OrdersReference" class="table table-bordered table-hover dataTable dtr-inline w-100">
                                    <thead class="thead-dark" id="OrdersReferenceHead">
                                    </thead>
                                    <tbody id="OrdersReferenceBody">
                                    </tbody>
                                    <tfoot class="thead-dark" id="OrdersReferenceFoot">
                                    </tfoot>
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
<script src="{{ asset('js/Dashboard/OrderDispatches/Filter.js') }}"></script>
@endsection
