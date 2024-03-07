let tableOrderDispatches = $('#orderDispatches').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: `/Dashboard/Orders/Dispatch/Index/Query`,
        type: 'POST',
        data: function (request) {
            var columnMappings = {
                0: 'id',
                1: 'id',
                2: 'client_id',
                3: 'client_branch_id',
                9: 'seller_date',
                10: 'seller_user_id',
                11: 'seller_status',
                12: 'wallet_status',
                13: 'dispatched_status',
                14: 'correria_id'
            };
            request._token = $('meta[name="csrf-token"]').attr('content');
            request.perPage = request.length;
            request.page = (request.start / request.length) + 1;
            request.search = request.search.value;
            request.column = columnMappings[request.order[0].column];
            request.dir = request.order[0].dir;
        },
        dataSrc: function (response) {
            response.recordsTotal = response.data.meta.pagination.count;
            response.recordsFiltered = response.data.meta.pagination.total;
            return response.data.orderDispatches;
        },
        error: function (xhr, error, thrown) {
            toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        }
    },
    columns: [
        {
            data: 'order_dispatches',
            render: function (data, type, row) {
                let btn = '';
                if(data.length > 0) {
                    btn += '<button class="btn btn-sm btn-success dt-expand rounded-circle"><i class="fas fa-plus"></i</button>';
                }
                return btn;
            },
        },
        { data: 'id' },
        {
            data: 'client_id',
            render: function (data, type, row) {
                return `${row.client.document_number} - ${row.client_branch.code}`;
            }
        },
        {
            data: 'client_branch_id',
            render: function (data, type, row) {
                return `${row.client.name} - ${row.client_branch.name}`;
            }
        },
        {
            data: 'client_branch_id',
            render: function (data, type, row) {
                return row.client_branch.country.name;
            }
        },
        {
            data: 'client_branch_id',
            render: function (data, type, row) {
                return row.client_branch.departament.name;
            }
        },
        {
            data: 'client_branch_id',
            render: function (data, type, row) {
                return row.client_branch.city.name;
            }
        },
        {
            data: 'client_branch_id',
            render: function (data, type, row) {
                return row.client_branch.address;
            }
        },
        {
            data: 'client_branch_id' ,
            render: function (data, type, row) {
                return row.client_branch.neighborhood;
            }
        },
        { data: 'seller_date' },
        {
            data: 'seller_user_id' ,
            render: function (data, type, row) {
                return `${row.seller_user.name} ${row.seller_user.last_name}`;
            }
        },
        {
            data: 'seller_status',
            render: function (data, type, row) {
                switch (data) {
                    case 'Cancelado':
                        return `<h5><span class="badge badge-pill badge-danger text-white"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span></h5>`;
                        break;
                    case 'Pendiente':
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                    case 'Aprobado':
                        return `<h5><span class="badge badge-pill badge-success"><i class="fas fa-check mr-2"></i>Aprobado</span></h5>`;
                        break;
                    default:
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                }
            }
        },
        {
            data: 'wallet_status',
            render: function (data, type, row) {
                switch (data) {
                    case 'Cancelado':
                        return `<h5><span class="badge badge-pill badge-danger text-white"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span></h5>`;
                        break;
                    case 'Pendiente':
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                    case 'Parcialmente Aprobado':
                        return `<h5><span class="badge badge-pill badge-warning text-white"><i class="fas fa-check mr-2 text-white"></i>Parcialmente Aprobado</span></h5>`;
                        break;
                    case 'Aprobado':
                        return `<h5><span class="badge badge-pill badge-success"><i class="fas fa-check-double mr-2"></i>Aprobado</span></h5>`;
                        break;
                    default:
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                }
            }
        },
        {
            data: 'dispatched_status',
            render: function (data, type, row) {
                switch (data) {
                    case 'Cancelado':
                        return `<h5><span class="badge badge-pill badge-danger text-white"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span></h5>`;
                        break;
                    case 'Pendiente':
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                    case 'Parcialmente Aprobado':
                        return `<h5><span class="badge badge-pill badge-warning text-white"><i class="fas fa-check mr-2 text-white"></i>Parcialmente Aprobado</span></h5>`;
                        break;
                    case 'Aprobado':
                        return `<h5><span class="badge badge-pill badge-success"><i class="fas fa-check-double mr-2"></i>Aprobado</span></h5>`;
                        break;
                    case 'Parcialmente Devuelto':
                        return `<h5><span class="badge badge-pill bg-gray"><i class="fas fa-reply mr-2"></i>Parcialmente Devuelto</span></h5>`;
                        break;
                    case 'Devuelto':
                        return `<h5><span class="badge badge-pill bg-dark text-white"><i class="fas fa-reply-all mr-2 text-white"></i>Devuelto</span></h5>`;
                        break;
                    case 'Parcialmente Despachado':
                        return `<h5><span class="badge badge-pill bg-purple text-white"><i class="fas fa-share mr-2 text-white"></i>Parcialmente Despachado</span></h5>`;
                        break;
                    case 'Despachado':
                        return `<h5><span class="badge badge-pill badge-primary"><i class="fas fa-share-all mr-2"></i>Despachado</span></h5>`;
                        break;
                    default:
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                }
            }
        },
        {
            data: 'correria_id',
            render: function (data, type, row) {
                return row.correria.code;
            }
        },
        {
            data: 'order_dispatches',
            render: function (data, type, row) {
                let btn = `<div class="text-center" style="width: 100%;">`;

                if(data.length > 0) {
                    btn += `<a href="/Dashboard/Orders/Dispatch/Details/Index/${row.id}" type="button"
                    class="btn btn-info btn-sm mr-2" title="Visualizar ordenes de despacho del pedido.">
                        <i class="fas fa-eye text-white"></i>
                    </a>`;
                }

                if(row.order_details.length > 0){
                    btn += `<a href="/Dashboard/Orders/Dispatch/Filter/${row.id}" type="button"
                    class="btn btn-primary btn-sm mr-2" title="Filtrar pedido.">
                        <i class="fas fa-filter text-white"></i>
                    </a>`;
                }

                btn += `</div>`;
                return btn;
            }
        }
    ],
    columnDefs: [
        {
            orderable: true,
            targets: [1, 2, 3, 9, 10, 11, 12, 13, 14]
        },
        {
            orderable: false,
            targets: [0, 4, 5, 6, 7, 8, 15]
        }
    ],
    pagingType: 'full_numbers',
    language: {
        oPaginate: {
            sFirst: 'Primero',
            sLast: 'Último',
            sNext: 'Siguiente',
            sPrevious: 'Anterior',
        },
        info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
        infoEmpty: 'No hay registros para mostrar',
        infoFiltered: '(filtrados de _MAX_ registros en total)',
        emptyTable: 'No hay datos disponibles.',
        lengthMenu: 'Mostrar _MENU_ registros por página.',
        search: 'Buscar:',
        zeroRecords: 'No se encontraron registros coincidentes.',
        decimal: ',',
        thousands: '.',
        sEmptyTable: 'No se ha llamado información o no está disponible.',
        sZeroRecords: 'No se encuentran resultados.',
        sProcessing: 'Procesando...'
    },
    pageLength: 10,
    lengthMenu: [10, 25, 50, 100],
    paging: true,
    info: true,
    searching: true,
    autoWidth: true
});

tableOrderDispatches.on('click', 'button.dt-expand', function (e) {
    let tr = e.target.closest('tr');
    let row = tableOrderDispatches.row(tr);

    let iconButton = $(this);

    if (row.child.isShown()) {
        row.child.hide();
        iconButton.html('<i class="fas fa-plus"></i>').removeClass('btn-danger').addClass('btn-success');
    } else {
        row.child(tableOrderDispatchesFilter(row.data())).show();
        iconButton.html('<i class="fas fa-minus"></i>').removeClass('btn-success').addClass('btn-danger');
        $(`#orderDispatches${row.data().id}`).DataTable({});
    }
});

function tableOrderDispatchesFilter(row) {
    let table = `<table class="table table-bordered table-hover dataTable dtr-inline nowrap w-100" id="orderDispatches${row.id}">
        <thead>
        <tr>
        <th>#</th>
        <th>Consecutivo</th>
        <th>Filtrador</th>
        <th>Fecha de Filtrado</th>
        <th>Fecha de Despacho</th>
        <th>Estado</th>
        <th>Acciones</th>
        </tr>
        </thead>
        <tbody>`;
    $.each(row.order_dispatches, function(index, order_dispatch) {
        table += `<tr>
            <td> ${order_dispatch.id} </td>
            <td> ${order_dispatch.consecutive} </td>
            <td> ${order_dispatch.dispatch_user.name + ' ' + order_dispatch.dispatch_user.last_name} </td>
            <td> ${order_dispatch.created_at} </td>
            <td> ${order_dispatch.dispatch_date} </td>`;

        switch (order_dispatch.dispatch_status) {
            case 'Pendiente':
                table += `<td><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></td>`;
                break;
            case 'Rechazado':
                table += `<td><span class="badge badge-pill badge-danger text-white"><i class="fas fa-ban mr-2 text-white"></i>Rechazado</span></td>`;
                break;
            case 'Cancelado':
                table += `<td><span class="badge badge-pill badge-warning text-white"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span></td>`;
                break;
            case 'Aprobado':
                table += `<td><span class="badge badge-pill badge-success"><i class="fas fa-check mr-2"></i>Aprobado</span></td>`;
                break;
            case 'Empacado':
                table += `<td><span class="badge badge-pill bg-gray" style="color:white !important;"><i class="fas fa-box mr-2 text-white"></i>Empacado</span></td>`;
                break;
            case 'Despachado':
                table += `<td><span class="badge badge-pill badge-primary"><i class="fas fa-share mr-2 text-white"></i>Despachado</span></td>`;
                break;
            default:
                table += `<td><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></td>`;
                break;
        };

        table += `<td class="text-center">
            <a href="/Dashboard/Orders/Dispatch/Details/Index/${order_dispatch.id}" type="button"
            class="btn btn-secondary btn-sm mr-2" title="Visualizar detalles de la orden de despacho del pedido.">
                <i class="fas fa-eye text-white"></i>
            </a>`;

        switch (order_dispatch.dispatch_status) {
            case 'Pendiente':
                table += `<a onclick="ApproveOrderDispatch(${order_dispatch.id})" type="button"
                class="btn btn-success btn-sm mr-2" title="Aprobar orden de despacho del pedido.">
                    <i class="fas fa-check text-white"></i>
                </a>`;

                table += `<a onclick="CancelOrderDispatch(${order_dispatch.id})" type="button"
                class="btn btn-warning btn-sm mr-2" title="Cancelar detalle pedido.">
                    <i class="fas fa-xmark text-white"></i>
                </a>`;

                table += `<a onclick="DeclineOrderDispatch(${order_dispatch.id})" type="button"
                class="btn btn-danger btn-sm mr-2 text-white" title="Rechazar orden de despacho del pedido.">
                    <i class="fas fa-ban text-white"></i>
                </a>`;
                break;
            case 'Aprobado':
                table += `<a onclick="PendingOrderDispatch(${order_dispatch.id})" type="button"
                class="btn btn-info btn-sm mr-2 text-white" title="Pendiente orden de despacho del pedido.">
                    <i class="fas fa-arrows-rotate text-white"></i>
                </a>`;

                table += `<a href="/Dashboard/Orders/Dispatch/Details/Index/${order_dispatch.id}" type="button" target="_blank"
                class="btn btn-sm mr-2" style="background: mediumvioletred; color: white;" title="Editar orden de despacho del pedido.">
                    <i class="fas fa-file-pdf text-white"></i>
                </a>`;

                table += `<a onclick="PackingOrderDispatch(${order_dispatch.id})" type="button"
                class="btn btn-primary btn-sm mr-2 text-white" title="Empacar orden de despacho del pedido.">
                    <i class="fas fa-box-open text-white"></i>
                </a>`;
                break;
            case 'Empacado':
                table += `<a onclick="InvoiceOrderDispatchModal(${order_dispatch.id})" type="button"
                class="btn btn-sm mr-2" style="background: slateblue; color: white;" title="Facturar orden de despacho del pedido.">
                    <i class="fas fa-file-pdf text-white"></i>
                </a>`;
            case 'Despachado':
                table += `<a onclick="PdfOrderDispatch(${order_dispatch.id})" type="button"
                class="btn btn-sm mr-2" style="background: mediumvioletred; color: white;" title="Editar orden de despacho del pedido.">
                    <i class="fas fa-pen text-white"></i>
                </a>`;
            default:
                table += ``;
                break;
        };

        table += `</td>
            </tr>`;
    });

    table += `</tbody></table>`;


    return table;
}
