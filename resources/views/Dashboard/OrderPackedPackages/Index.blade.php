@extends('Templates.Dashboard')
@section('content')
<section class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Alistamiento y Empacado de Ordenes</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Orders</li>
                        <li class="breadcrumb-item">Packed</li>
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
                                <a class="btn btn-info text-white" id="IndexOrderPackedDetail" data-id="{{ $orderPacked->id }}" onclick="IndexOrderPackedDetail({{ $orderPacked->id }})" type="button" title="Orden de alistamiento y empacado de la orden de despacho {{ $orderPacked->order_dispatch->consecutive }}.">
                                    ORDEN DE DESPACHO: {{ $orderPacked->order_dispatch->consecutive }}
                                </a>
                            </li>
                            <li class="nav-item ml-auto">
                                <a class="btn btn-primary" type="button" onclick="CreateOrderPackedPackage({{ $orderPacked->id }}, {{ json_encode($packageTypes) }})" title="Nuevo empaque para alistar y empacar la orden de despacho.">
                                    <i class="fas fa-box-open-full text-white"></i>
                                </a>
                            </li>
                            <li class="nav-item ml-2">
                                <a class="btn btn-success" type="button" onclick="FinishOrderPacked({{ $orderPacked->id }})" title="Finalizar alistamiento y empacado de la orden de despacho.">
                                    <i class="fas fa-boxes-packing text-white"></i>
                                </a>
                            </li>
                            <li class="nav-item ml-2">
                                <a class="btn btn-danger" type="button" onclick="DeleteOrderPacked({{ $orderPacked->id }})" title="Eliminar alistamiento y empacado de la orden de despacho.">
                                    <i class="fas fa-trash text-white"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body" id="orderPackages">
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="{{ asset('js/Dashboard/OrderPackedPackages/Index.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderPackedPackages/Create.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderPackedPackages/Open.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderPackedPackages/Close.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderPackedPackages/Delete.js') }}"></script>

<script src="{{ asset('js/Dashboard/OrderPackings/Finish.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderPackings/Delete.js') }}"></script>
@endsection