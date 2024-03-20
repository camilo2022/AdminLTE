@extends('Templates.Dashboard')
@section('content')
<section class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Empaque de la orden {{ $orderPackage->order_packing->order_dispatch->consecutive }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Orders</li>
                        <li class="breadcrumb-item">Packed</li>
                        <li class="breadcrumb-item">Show</li>
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
                                <a class="btn btn-info text-white" id="ShowOrderPackage" data-id="{{ $orderPackage->id }}" onclick="ShowOrderPackage({{ $orderPackage->id }})" type="button" title="Empaque de la orden de alistamiento y empaque de la orden de despacho {{ $orderPackage->order_packing->order_dispatch->consecutive }}.">
                                    #{{ $orderPackage->id }} | {{ $orderPackage->package_type->name }}
                                </a>
                            </li>
                            <li class="nav-item ml-auto">
                                <a class="btn btn-primary" type="button" onclick="CloseOrderPackedPackage({{ $orderPackage->id }})" title="Cerrar empaque.">
                                    <i class="fas fa-box-taped text-white"></i>
                                </a>
                            </li>
                            <li class="nav-item ml-2">
                                <a class="btn btn-danger" type="button" onclick="DeleteOrderPackedPackage({{ $orderPackage->id }})" title="Eliminar empaque.">
                                    <i class="fas fa-trash text-white"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body" id="orderPackageDetails">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Dashboard.OrderPackedPackages.Detail')
</section>
@endsection
@section('script')
<script src="{{ asset('js/Dashboard/OrderPackedPackages/Show.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderPackedPackages/Close.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderPackedPackages/Delete.js') }}"></script>
@endsection