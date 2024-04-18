@extends('Templates.Dashboard')
@section('content')
<section class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Detalles de la Orden de Devolucion</h1>
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
                                <a class="btn btn-info text-white" id="IndexOrderReturnDetail" data-id="{{ $orderReturn->id }}" onclick="IndexOrderReturnDetail({{ $orderReturn->id }})" type="button" title="Orden de devolucion del pedido.">
                                    ORDEN DE PEDIDO: {{ $orderReturn->id }}
                                </a>
                            </li>
                            @if($orderReturn->return_status == 'Pendiente')
                                <li class="nav-item ml-auto">
                                    <a class="btn btn-success text-white" type="button" onclick="ApproveOrderReturn({{ $orderReturn->order->id }}, false)" title="Aprobar orden de devolucion.">
                                        <i class="fas fa-check"></i>
                                    </a>
                                </li>
                                <li class="nav-item ml-2">
                                    <a class="btn btn-danger text-white" type="button" onclick="CancelOrderReturn({{ $orderReturn->order->id }}, false)" title="Cancelar orden de devolucion.">
                                        <i class="fas fa-xmark"></i>
                                    </a>
                                </li>
                            @endif
                            @if($orderReturn->return_status == 'Cancelado')
                                <li class="nav-item ml-auto">
                                    <a class="btn btn-success text-white" type="button" onclick="PendingOrderReturn({{ $orderReturn->order->id }}, false)" title="Pendiente orden de devolucion.">
                                        <i class="fas fa-check"></i>
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
                                        <td width="14%" style="font-size:14px;">{{ $orderReturn->order->client->document_type->code }}:</td>
                                        <td width="24%" style="font-size:14px;">{{ $orderReturn->order->client->document_number }}</td>
                                        <td width="12%" style="font-size:14px;">CODIGO SUCURSAL:</td>
                                        <td width="17%" style="font-size:14px;">{{ $orderReturn->order->client_branch->code }}</td>
                                        <td width="13%" style="font-size:14px;">TIPO DESPACHO: </td>
                                        <td width="20%" style="font-size:14px;">
                                            <span class="badge badge-pill badge-info">{{ $orderReturn->order->dispatch }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:14px;">CLIENTE:</td>
                                        <td style="font-size:14px;">{{ $orderReturn->order->client->name }}</td>
                                        <td style="font-size:14px;">SUCURSAL:</td>
                                        <td style="font-size:14px;">{{ $orderReturn->order->client_branch->name }}</td>
                                        <td style="font-size:14px;">FECHA DESPACHO: </td>
                                        <td style="font-size:14px;">
                                            <span class="badge badge-pill bg-dark">{{ $orderReturn->order->dispatch_date }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:14px;">PAIS:</td>
                                        <td style="font-size:14px;">{{ $orderReturn->order->client_branch->country->name }}</td>
                                        <td style="font-size:14px;">DIRECCION:</td>
                                        <td style="font-size:14px;">{{ $orderReturn->order->client_branch->address }}</td>
                                        <td style="font-size:14px;">ESTADO VENDEDOR:</td>
                                        <td style="font-size:14px;">
                                            @switch($orderReturn->order->seller_status)
                                                @case('Cancelado')
                                                    <span class="badge badge-pill badge-danger text-white" id="seller_status"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span>
                                                    @break
                                                @case('Pendiente')
                                                    <span class="badge badge-pill badge-info" id="seller_status"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span>
                                                    @break
                                                @case('Aprobado')
                                                    <span class="badge badge-pill badge-success" id="seller_status"><i class="fas fa-check mr-2"></i>Aprobado</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-pill badge-info" id="seller_status"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:14px;">DEPARTAMENTO:</td>
                                        <td style="font-size:14px;">{{ $orderReturn->order->client_branch->departament->name }}</td>
                                        <td style="font-size:14px;">BARRIO:</td>
                                        <td style="font-size:14px;">{{ $orderReturn->order->client_branch->neighborhood }}</td>
                                        <td style="font-size:14px;">ESTADO CARTERA:</td>
                                        <td style="font-size:14px;">
                                            @switch($orderReturn->order->wallet_status)
                                                @case('Cancelado')
                                                    <span class="badge badge-pill badge-danger text-white" id="wallet_status"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span>
                                                    @break
                                                @case('Pendiente')
                                                    <span class="badge badge-pill badge-info" id="wallet_status"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span>
                                                    @break
                                                @case('Parcialmente Aprobado')
                                                    <span class="badge badge-pill badge-warning text-white" id="seller_status"><i class="fas fa-check mr-2 text-white"></i>Parcialmente Aprobado</span>
                                                    @break
                                                @case('Aprobado')
                                                    <span class="badge badge-pill badge-success" id="wallet_status"><i class="fas fa-check-double mr-2"></i>Aprobado</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-pill badge-info" id="wallet_status"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:14px;">CIUDAD:</td>
                                        <td style="font-size:14px;">{{ $orderReturn->order->client_branch->city->name }}</td>
                                        <td style="font-size:14px;">N° TELEFONO:</td>
                                        <td style="font-size:14px;">{{ $orderReturn->order->client_branch->telephone_number_first }}</td>
                                        <td style="font-size:14px;">ESTADO DESPACHO:</td>
                                        <td style="font-size:14px;">
                                            @switch($orderReturn->order->dispatched_status)
                                                @case('Pendiente')
                                                    <span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span>
                                                    @break
                                                @case('Cancelado')
                                                    <span class="badge badge-pill badge-orange text-white"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span>
                                                    @break
                                                @case('Rechazado')
                                                    <span class="badge badge-pill badge-danger text-white"><i class="fas fa-ban mr-2 text-white"></i>Rechazado</span>
                                                    @break
                                                @case('Parcialmente Aprobado')
                                                    <span class="badge badge-pill badge-warning text-white"><i class="fas fa-check mr-2 text-white"></i>Parcialmente Aprobado</span>
                                                    @break
                                                @case('Aprobado')
                                                    <span class="badge badge-pill badge-success"><i class="fas fa-check-double mr-2"></i>Aprobado</span>
                                                    @break
                                                @case('Parcialmente Empacado')
                                                    <span class="badge badge-pill bg-darkgray"><i class="fas fa-box-open mr-2 text-white"></i>Empacado</span>
                                                    @break
                                                @case('Empacado')
                                                    <span class="badge badge-pill bg-gray"><i class="fas fa-box mr-2 text-white"></i>Empacado</span>
                                                    @break
                                                @case('Parcialmente Despachado')
                                                    <span class="badge badge-pill bg-purple text-white" style="color:white !important;"><i class="fas fa-share mr-2 text-white"></i>Parcialmente Despachado</span>
                                                    @break
                                                @case('Despachado')
                                                    <span class="badge badge-pill badge-primary"><i class="fas fa-share-all mr-2"></i>Despachado</span>
                                                    @break
                                                @case('Parcialmente Devuelto')
                                                    <span class="badge badge-pill text-white" style="background:saddlebrown !important;"><i class="fas fa-reply mr-2 text-white"></i>Parcialmente Devuelto</span>
                                                    @break
                                                @case('Devuelto')
                                                    <span class="badge badge-pill bg-dark text-white"><i class="fas fa-reply-all mr-2 text-white"></i>Devuelto</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:14px;">CORREO:</td>
                                        <td style="font-size:14px;">{{ $orderReturn->order->client_branch->email }}</td>
                                        <td style="font-size:14px;">N° TELEFONO:</td>
                                        <td style="font-size:14px;">{{ $orderReturn->order->client_branch->telephone_number_second }}</td>
                                        <td style="font-size:14px;">ESTADO DEVOLUCION:</td>
                                        <td style="font-size:14px;">
                                            @switch($orderReturn->return_status)
                                                @case('Pendiente')
                                                    <span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span>
                                                    @break
                                                @case('Cancelado')
                                                    <span class="badge badge-pill badge-danger text-white"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span>
                                                    @break
                                                @case('Aprobado')
                                                    <span class="badge badge-pill badge-success"><i class="fas fa-check-double mr-2"></i>Aprobado</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:14px;">VENDEDOR:</td>
                                        <td style="font-size:14px;">{{ $orderReturn->order->seller_user->name }} {{ $orderReturn->order->seller_user->last_name }}</td>
                                        <td style="font-size:14px;">CARTERA:</td>
                                        <td style="font-size:14px;">{{ $orderReturn->order->wallet_user->name }} {{ $orderReturn->order->wallet_user->last_name }}</td>
                                        <td style="font-size:14px;">CORRERIA:</td>
                                        <td style="font-size:14px;">{{ $orderReturn->order->correria->code }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:14px;">OBSERVACION COMERCIAL:</td>
                                        <td style="font-size:14px;">{{ $orderReturn->order->seller_observation }}</td>
                                        <td style="font-size:14px;">OBSERVACION CARTERA:</td>
                                        <td style="font-size:14px;" colspan="3">{{ $orderReturn->order->wallet_observation }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:14px;">FECHA VENDEDOR:</td>
                                        <td style="font-size:14px;">{{ $orderReturn->order->seller_date }}</td>
                                        <td style="font-size:14px;">FECHA CARTERA:</td>
                                        <td style="font-size:14px;">{{ $orderReturn->order->wallet_date }}</td>
                                        <td style="font-size:14px;">FECHA DESPACHO:</td>
                                        <td style="font-size:14px;">{{ $orderReturn->order->dispatched_date }}</td>
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
                            @if($orderReturn->return_status == 'Pendiente')
                                <li class="nav-item">
                                    <a class="nav-link active" type="button" onclick="CreateOrderReturnDetailModal()" title="Agregar detalle de pedido.">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="orderReturns" class="table table-bordered table-hover dataTable dtr-inline nowrap w-100">
                                <thead class="thead-dark" id="OrderReturnDetailHead">
                                </thead>
                                <tbody id="OrderReturnDetailBody">
                                </tbody>
                                <tfoot class="thead-dark" id="OrderReturnDetailFoot">
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Dashboard.OrderReturnDetails.Create')
    @include('Dashboard.OrderReturnDetails.Edit')
</section>
@endsection
@section('script')
<script src="{{ asset('js/Dashboard/OrderReturnDetails/Index.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderReturnDetails/Create.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderReturnDetails/Edit.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderReturnDetails/Pending.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderReturnDetails/Cancel.js') }}"></script>

<script src="{{ asset('js/Dashboard/OrderReturns/Approve.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderReturns/Pending.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderReturns/Cancel.js') }}"></script>
@endsection
