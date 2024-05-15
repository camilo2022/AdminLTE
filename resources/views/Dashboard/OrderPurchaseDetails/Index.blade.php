@extends('Templates.Dashboard')
@section('content')
<section class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Detalles de la orden de compra</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Orders</li>
                        <li class="breadcrumb-item">Return</li>
                        <li class="breadcrumb-item">Details</li>
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
                                <i class="fas fa-xmark"></i>
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
                                <a class="btn btn-info text-white" id="IndexOrderPurchaseDetail" data-id="{{ $orderPurchase->id }}" onclick="IndexOrderPurchaseDetail({{ $orderPurchase->id }})" type="button" title="Orden de compra.">
                                    ORDEN DE COMPRA: {{ $orderPurchase->id }}
                                </a>
                            </li>
                            @if($orderPurchase->purchase_status == 'Pendiente')
                                <li class="nav-item ml-auto">
                                    <a class="btn btn-success text-white" type="button" onclick="ApproveOrderPurchase({{ $orderPurchase->id }}, false)" title="Aprobar orden de compra.">
                                        <i class="fas fa-check"></i>
                                    </a>
                                </li>
                                <li class="nav-item ml-2">
                                    <a class="btn btn-danger text-white" type="button" onclick="CancelOrderPurchase({{ $orderPurchase->id }}, false)" title="Cancelar orden de compra.">
                                        <i class="fas fa-xmark"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td width="14%" style="font-size:14px;">{{ $orderPurchase->workshop->document_type->code }}:</td>
                                        <td width="24%" style="font-size:14px;">{{ $orderPurchase->workshop->document_number }}</td>
                                        <td width="12%" style="font-size:14px;">CORREO:</td>
                                        <td width="17%" style="font-size:14px;">{{ $orderPurchase->workshop->email }}</td>
                                        <td width="13%" style="font-size:14px;">ESTADO: </td>
                                        <td width="20%" style="font-size:14px;">
                                            <span class="badge badge-pill badge-info">{{ $orderPurchase->purchase_status }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:14px;">TALLER:</td>
                                        <td style="font-size:14px;">{{ $orderPurchase->workshop->name }}</td>
                                        <td style="font-size:14px;">DIRECCION:</td>
                                        <td style="font-size:14px;">{{ $orderPurchase->workshop->address }}</td>
                                        <td style="font-size:14px;">FECHA: </td>
                                        <td style="font-size:14px;">
                                            <span class="badge badge-pill bg-dark">{{ $orderPurchase->purchase_date }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:14px;">PAIS:</td>
                                        <td style="font-size:14px;">{{ $orderPurchase->workshop->country->name }}</td>
                                        <td style="font-size:14px;">BARRIO:</td>
                                        <td style="font-size:14px;">{{ $orderPurchase->workshop->neighborhood }}</td>
                                        <td style="font-size:14px;">PAGO:</td>
                                        <td style="font-size:14px;">
                                            <span class="badge badge-pill badge-info">{{ $orderPurchase->payment_status }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:14px;">DEPARTAMENTO:</td>
                                        <td style="font-size:14px;">{{ $orderPurchase->workshop->departament->name }}</td>
                                        <td style="font-size:14px;">N° TELEFONO:</td>
                                        <td style="font-size:14px;">{{ $orderPurchase->workshop->telephone_number_first }}</td>
                                        <td style="font-size:14px;">FECHA:</td>
                                        <td style="font-size:14px;">
                                            <span class="badge badge-pill bg-dark">{{ $orderPurchase->payment_date ?? '-' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:14px;">CIUDAD:</td>
                                        <td style="font-size:14px;">{{ $orderPurchase->workshop->city->name }}</td>
                                        <td style="font-size:14px;">N° TELEFONO:</td>
                                        <td style="font-size:14px;">{{ $orderPurchase->workshop->telephone_number_second }}</td>
                                        <td style="font-size:14px;">USUARIO:</td>
                                        <td style="font-size:14px;">{{ $orderPurchase->purchase_user->name . ' ' . $orderPurchase->purchase_user->last_name }}</td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:14px;">OBSERVACION:</td>
                                        <td style="font-size:14px;" colspan="5">{{ $orderPurchase->purchase_observation }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            @if($orderPurchase->return_status == 'Pendiente')
                                <li class="nav-item">
                                    <a class="nav-link active" type="button" onclick="CreateOrderPurchaseDetailModal()" title="Agregar detalle de la orden de compra.">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="orderPurchases" class="table table-bordered table-hover dataTable dtr-inline nowrap w-100">
                                <thead class="thead-dark" id="OrderPurchaseDetailHead">
                                </thead>
                                <tbody id="OrderPurchaseDetailBody">
                                </tbody>
                                <tfoot class="thead-dark" id="OrderPurchaseDetailFoot">
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Dashboard.OrderPurchaseDetails.Create')
    @include('Dashboard.OrderPurchaseDetails.Edit')
</section>
@endsection
@section('script')
<script src="{{ asset('js/Dashboard/OrderPurchaseDetails/Index.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderPurchaseDetails/Create.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderPurchaseDetails/Edit.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderPurchaseDetails/Cancel.js') }}"></script>

<script src="{{ asset('js/Dashboard/OrderPurchases/Approve.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderPurchases/Cancel.js') }}"></script>
@endsection
