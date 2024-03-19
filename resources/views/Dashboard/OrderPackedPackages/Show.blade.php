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
                                <a class="btn btn-info text-white" id="IndexOrderPackage" data-id="{{ $orderPackage->id }}" onclick="IndexOrderPackage({{ $orderPackage->id }})" type="button" title="Empaque de la orden de alistamiento y empaque de la orden de despacho {{ $orderPackage->order_packing->order_dispatch->consecutive }}.">
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
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="button" class="mb-2 btn w-100 collapsed" style="background-color:#23282e; color:white;" data-toggle="collapse" data-target="#collapseExample1" aria-expanded="false" aria-controls="#collapseExample1">
                                    <b>
                                        <div class="table-responsive">
                                            <span>REF:2</span> | <span class="badge badge-light" id="2-faltan">0</span> de <span class="badge badge-warning" id="2-total">22</span> | <span class="badge badge-danger" id="2-badge"> Hace falta</span><br>
                                            <span>REF:2</span> | <span class="badge badge-light" id="2-faltan">22</span> de <span class="badge badge-warning" id="2-total">22</span> | <span class="badge badge-success" id="2-badge"> Completado</span>
                                        </div>
                                    </b>
                                </button>
                                <div class="table-responsive collapse" id="collapseExample1">

                                    <div class="col-12">
                                        <input id="" onkeyup="2" type="text" class="mb-2 w-100 form-control" style="border: 1px solid black !important;" value="">
                                    </div>
                                    <table id="2-table" class="table text-center">
                                        <thead>
                                            <tr>
                                                <th scope="col">TALLA</th>
                                                <th scope="col">CP</th>
                                                <th scope="col">CD</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="04">
                                                <th scope="col">T04</th>
                                                <td><input type="number" id="2-04-CP" value="0" disabled></td>
                                                <td><input type="number" id="2-04-CD" value="12" disabled></td>
                                            </tr>
                                            <tr class="06">
                                                <th scope="col">T06</th>
                                                <td><input type="number" id="2-06-CP" value="0" disabled></td>
                                                <td><input type="number" id="2-06-CD" value="10" disabled></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <button type="button" class="mb-2 btn w-100 collapsed" style="background-color:#23282e; color:white;" data-toggle="collapse" data-target="#collapseExample1" aria-expanded="false" aria-controls="#collapseExample1">
                                    <b>
                                        <div class="table-responsive">
                                            <span>REF:2</span> | <span class="badge badge-light" id="2-faltan">0</span> de <span class="badge badge-warning" id="2-total">22</span> | <span class="badge badge-danger" id="2-badge"> Hace falta</span><br>
                                            <span>REF:2</span> | <span class="badge badge-light" id="2-faltan">22</span> de <span class="badge badge-warning" id="2-total">22</span> | <span class="badge badge-success" id="2-badge"> Completado</span>
                                        </div>
                                    </b>
                                </button>
                                <div class="table-responsive collapse" id="collapseExample1">

                                    <div class="col-12">
                                        <input id="" onkeyup="2" type="text" class="mb-2 w-100 form-control" style="border: 1px solid black !important;" value="">
                                    </div>
                                    <table id="2-table" class="table text-center">
                                        <thead>
                                            <tr>
                                                <th scope="col">TALLA</th>
                                                <th scope="col">CP</th>
                                                <th scope="col">CD</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="04">
                                                <th scope="col">T04</th>
                                                <td><input type="number" id="2-04-CP" value="0" disabled></td>
                                                <td><input type="number" id="2-04-CD" value="12" disabled></td>
                                            </tr>
                                            <tr class="06">
                                                <th scope="col">T06</th>
                                                <td><input type="number" id="2-06-CP" value="0" disabled></td>
                                                <td><input type="number" id="2-06-CD" value="10" disabled></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="{{ asset('js/Dashboard/OrderPackedPackages/Detail.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderPackedPackages/Close.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderPackedPackages/Delete.js') }}"></script>
@endsection