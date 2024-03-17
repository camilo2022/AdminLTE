@extends('Templates.Dashboard')
@section('content')
<section class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Detalles de la Orden de Despacho</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Orders</li>
                        <li class="breadcrumb-item">Dispatch</li>
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
                                <a class="btn btn-info text-white" id="IndexOrderDispatchDetail" data-id="{{ $orderDispatch->id }}" onclick="IndexOrderDispatchDetail({{ $orderDispatch->id }})" type="button" title="Orden de despacho del pedido.">
                                    ORDEN DE DESPACHO: {{ $orderDispatch->consecutive }}
                                </a>
                            </li>
                            @if($orderDispatch->dispatch_status == 'Pendiente')
                                <li class="nav-item ml-auto">
                                    <a class="btn btn-success" type="button" onclick="ApproveOrderDispatch({{ $orderDispatch->id }}, false)" title="Aprobar orden de despacho del pedido.">
                                        <i class="fas fa-check text-white"></i>
                                    </a>
                                </li>
                                <li class="nav-item ml-2">
                                    <a class="btn btn-warning" type="button" onclick="CancelOrderDispatch({{ $orderDispatch->id }}, false)" title="Cancelar orden de despacho del pedido.">
                                        <i class="fas fa-xmark text-white"></i>
                                    </a>
                                </li>
                                <li class="nav-item ml-2">
                                    <a class="btn btn-danger text-white" type="button" onclick="DeclineOrderDispatch({{ $orderDispatch->id }}, false)" title="Rechazar orden de despacho del pedido.">
                                        <i class="fas fa-ban text-white"></i>
                                    </a>
                                </li>
                            @endif
                            @if($orderDispatch->dispatch_status == 'Aprobado')
                                <li class="nav-item ml-auto">
                                    <a class="btn btn-info text-white" type="button" onclick="PendingOrderDispatch({{ $orderDispatch->id }}, false)" title="Pendiente orden de despacho del pedido.">
                                        <i class="fas fa-arrows-rotate text-white"></i>
                                    </a>
                                </li>
                                <li class="nav-item ml-2">
                                    <a class="btn" style="background: mediumvioletred; color: white;" target="_blank" type="button" href="/Dashboard/Orders/Dispatch/Download/{{ $orderDispatch->id }}" title="PDF orden de despacho del pedido.">
                                        <i class="fas fa-file-pdf text-white"></i>
                                    </a>
                                </li>
                                <!-- <li class="nav-item ml-2">
                                    <a class="btn btn-primary text-white" type="button" onclick="PackingOrderDispatch(({{ $orderDispatch->id }}, false)" title="Empacar orden de despacho del pedido.">
                                        <i class="fas fa-box-open text-white"></i>
                                    </a>
                                </li> -->
                            @endif
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td width="14%" style="font-size:14px;">{{ $orderDispatch->order->client->document_type->code }}:</td>
                                        <td width="24%" style="font-size:14px;">{{ $orderDispatch->order->client->document_number }}</td>
                                        <td width="12%" style="font-size:14px;">CODIGO SUCURSAL:</td>
                                        <td width="17%" style="font-size:14px;">{{ $orderDispatch->order->client_branch->code }}</td>
                                        <td width="13%" style="font-size:14px;">TIPO DESPACHO: </td>
                                        <td width="20%" style="font-size:14px;">
                                            <span class="badge badge-pill badge-info">{{ $orderDispatch->order->dispatch }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:14px;">CLIENTE:</td>
                                        <td style="font-size:14px;">{{ $orderDispatch->order->client->name }}</td>
                                        <td style="font-size:14px;">SUCURSAL:</td>
                                        <td style="font-size:14px;">{{ $orderDispatch->order->client_branch->name }}</td>
                                        <td style="font-size:14px;">FECHA DESPACHO: </td>
                                        <td style="font-size:14px;">
                                            <span class="badge badge-pill bg-dark">{{ $orderDispatch->order->dispatch_date }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:14px;">PAIS:</td>
                                        <td style="font-size:14px;">{{ $orderDispatch->order->client_branch->country->name }}</td>
                                        <td style="font-size:14px;">DIRECCION:</td>
                                        <td style="font-size:14px;">{{ $orderDispatch->order->client_branch->address }}</td>
                                        <td style="font-size:14px;">ESTADO VENDEDOR:</td>
                                        <td style="font-size:14px;">
                                            @switch($orderDispatch->order->seller_status)
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
                                        <td style="font-size:14px;">{{ $orderDispatch->order->client_branch->departament->name }}</td>
                                        <td style="font-size:14px;">BARRIO:</td>
                                        <td style="font-size:14px;">{{ $orderDispatch->order->client_branch->neighborhood }}</td>
                                        <td style="font-size:14px;">ESTADO CARTERA:</td>
                                        <td style="font-size:14px;">
                                            @switch($orderDispatch->order->wallet_status)
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
                                        <td style="font-size:14px;">{{ $orderDispatch->order->client_branch->city->name }}</td>
                                        <td style="font-size:14px;">N° TELEFONO:</td>
                                        <td style="font-size:14px;">{{ $orderDispatch->order->client_branch->telephone_number_first }}</td>
                                        <td style="font-size:14px;">ESTADO DESPACHO:</td>
                                        <td style="font-size:14px;">
                                            @switch($orderDispatch->order->dispatched_status)
                                                @case('Cancelado')
                                                    <span class="badge badge-pill bg-orange text-white" id="dispatched_status" style="color:white !important;"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span>
                                                    @break
                                                @case('Rechazado')
                                                    <span class="badge badge-pill badge-danger text-white" id="dispatched_status"><i class="fas fa-ban mr-2 text-white"></i>Rechazado</span>
                                                    @break
                                                @case('Pendiente')
                                                    <span class="badge badge-pill badge-info" id="dispatched_status"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span>
                                                    @break
                                                @case('Parcialmente Aprobado')
                                                    <span class="badge badge-pill badge-warning text-white" id="dispatched_status"><i class="fas fa-check mr-2 text-white"></i>Parcialmente Aprobado</span>
                                                    @break
                                                @case('Aprobado')
                                                    <span class="badge badge-pill badge-success" id="dispatched_status"><i class="fas fa-check-double mr-2"></i>Aprobado</span>
                                                    @break
                                                @case('Parcialmente Devuelto')
                                                    <span class="badge badge-pill bg-gray" id="dispatched_status"><i class="fas fa-reply mr-2"></i>Parcialmente Devuelto</span>
                                                    @break
                                                @case('Devuelto')
                                                    <span class="badge badge-pill bg-dark text-white" id="dispatched_status"><i class="fas fa-reply-all mr-2 text-white"></i>Devuelto</span>
                                                    @break
                                                @case('Parcialmente Despachado')
                                                    <span class="badge badge-pill bg-purple text-white" id="dispatched_status" style="color:white !important;"><i class="fas fa-share mr-2 text-white"></i>Parcialmente Despachado</span>
                                                    @break
                                                @case('Despachado')
                                                    <span class="badge badge-pill badge-primary" id="dispatched_status"><i class="fas fa-share-all mr-2"></i>Despachado</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-pill badge-info" id="dispatched_status"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:14px;">CORREO:</td>
                                        <td style="font-size:14px;">{{ $orderDispatch->order->client_branch->email }}</td>
                                        <td style="font-size:14px;">N° TELEFONO:</td>
                                        <td style="font-size:14px;">{{ $orderDispatch->order->client_branch->telephone_number_second }}</td>
                                        <td style="font-size:14px;">ESTADO ORDEN:</td>
                                        <td style="font-size:14px;">
                                            @switch($orderDispatch->dispatch_status)
                                                @case('Cancelado')
                                                    <span class="badge badge-pill bg-orange text-white" style="color:white !important;"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span>
                                                    @break
                                                @case('Rechazado')
                                                    <span class="badge badge-pill badge-danger text-white"><i class="fas fa-ban mr-2 text-white"></i>Rechazado</
                                                    @brea
                                                @case('Pendiente')
                                                    <span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span>
                                                    @break
                                                @case('Parcialmente Aprobado')
                                                    <span class="badge badge-pill badge-warning text-white"><i class="fas fa-check mr-2 text-white"></i>Parcialmente Aprobado</span>
                                                    @break
                                                @case('Aprobado')
                                                    <span class="badge badge-pill badge-success"><i class="fas fa-check-double mr-2"></i>Aprobado</span>
                                                    @break
                                                @case('Parcialmente Devuelto')
                                                    <span class="badge badge-pill bg-gray"><i class="fas fa-reply mr-2"></i>Parcialmente Devuelto</span>
                                                    @break
                                                @case('Devuelto')
                                                    <span class="badge badge-pill bg-dark text-white"><i class="fas fa-reply-all mr-2 text-white"></i>Devuelto</span>
                                                    @break
                                                @case('Parcialmente Despachado')
                                                    <span class="badge badge-pill bg-purple text-white" style="color:white !important;"><i class="fas fa-share mr-2 text-white"></i>Parcialmente Despachado</span>
                                                    @break
                                                @case('Despachado')
                                                    <span class="badge badge-pill badge-primary"><i class="fas fa-share-all mr-2"></i>Despachado</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:14px;">OBSERVACION COMERCIAL:</td>
                                        <td style="font-size:14px;">{{ $orderDispatch->order->seller_observation }}</td>
                                        <td style="font-size:14px;">OBSERVACION CARTERA:</td>
                                        <td style="font-size:14px;">{{ $orderDispatch->order->wallet_observation }}</td>
                                        <td style="font-size:14px;">VENDEDOR:</td>
                                        <td style="font-size:14px;">{{ $orderDispatch->order->seller_user->name }} {{ $orderDispatch->order->seller_user->last_name }}</td>
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
                            @if($orderDispatch->order->seller_status == 'Pendiente' && $orderDispatch->order->wallet_status == 'Pendiente' && $orderDispatch->order->dispatched_status == 'Pendiente')
                                <li class="nav-item">
                                    <a class="nav-link active" type="button" onclick="CreateOrderDispatchDetailModal()" title="Agregar detalle de pedido.">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="orderDispatchs" class="table table-bordered table-hover dataTable dtr-inline nowrap w-100">
                                <thead class="thead-dark" id="OrderDispatchDetailHead">
                                </thead>
                                <tbody id="OrderDispatchDetailBody">
                                </tbody>
                                <tfoot class="thead-dark" id="OrderDispatchDetailFoot">
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
<script src="{{ asset('js/Dashboard/OrderDispatchDetails/Index.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderDispatchDetails/Approve.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderDispatchDetails/Cancel.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderDispatchDetails/Decline.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderDispatchDetails/Pending.js') }}"></script>

<script src="{{ asset('js/Dashboard/OrderDispatches/Approve.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderDispatches/Cancel.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderDispatches/Decline.js') }}"></script>
<script src="{{ asset('js/Dashboard/OrderDispatches/Pending.js') }}"></script>
@endsection
