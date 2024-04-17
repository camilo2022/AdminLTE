let tableOrderReturns = $('#orderReturns').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: `/Dashboard/Orders/Return/Index/Query`,
        type: 'POST',
        data: function (request) {
            var columnMappings = {
                0: 'id',
                1: 'id',
                2: 'client_id',
                3: 'client_branch_id',
                9: 'seller_user_id',
                10: 'dispatched_status',
                11: 'correria_id'
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
            return response.data.orderReturns;
        },
        error: function (xhr, error, thrown) {
            toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        }
    },
    columns: [
        {
            data: 'order_returns',
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
                return row.client.document_number;
            }
        },
        {
            data: 'client_branch_id',
            render: function (data, type, row) {
                return row.client_branch.code;
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
        { data: 'dispatched_status' },
        {
            data: 'dispatched_status',
            render: function (data, type, row) {
                switch (data) {
                    case 'Pendiente':
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                    case 'Cancelado':
                        return `<h5><span class="badge badge-pill badge-orange text-white"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span></h5>`;
                        break;
                    case 'Rechazado':
                        return `<h5><span class="badge badge-pill badge-danger text-white"><i class="fas fa-ban mr-2 text-white"></i>Rechazado</span></h5>`;
                        break;
                    case 'Parcialmente Aprobado':
                        return `<h5><span class="badge badge-pill badge-warning text-white"><i class="fas fa-check mr-2 text-white"></i>Parcialmente Aprobado</span></h5>`;
                        break;
                    case 'Aprobado':
                        return `<h5><span class="badge badge-pill badge-success"><i class="fas fa-check-double mr-2"></i>Aprobado</span></h5>`;
                        break;
                    case 'Parcialmente Empacado':
                        return `<h5><span class="badge badge-pill bg-gray"><i class="fas fa-box-open mr-2 text-white"></i>Empacado</span></h5>`;
                        break;
                    case 'Empacado':
                        return `<h5><span class="badge badge-pill bg-darkgray"><i class="fas fa-box mr-2 text-white"></i>Empacado</span></h5>`;
                        break;
                    case 'Parcialmente Despachado':
                        return `<h5><span class="badge badge-pill bg-purple text-white" style="color:white !important;"><i class="fas fa-share mr-2 text-white"></i>Parcialmente Despachado</span></h5>`;
                        break;
                    case 'Despachado':
                        return `<h5><span class="badge badge-pill badge-primary"><i class="fas fa-share-all mr-2"></i>Despachado</span></h5>`;
                        break;
                    case 'Parcialmente Devuelto':
                        return `<h5><span class="badge badge-pill text-white" style="background:saddlebrown !important;"><i class="fas fa-reply mr-2 text-white"></i>Parcialmente Devuelto</span></h5>`;
                        break;
                    case 'Devuelto':
                        return `<h5><span class="badge badge-pill bg-dark text-white"><i class="fas fa-reply-all mr-2 text-white"></i>Devuelto</span></h5>`;
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
            data: 'order_returns',
            render: function (data, type, row) {
                let btn = `<div class="text-center" style="width: 100%;">`;

                if ((row.order_details.map(item => item.status).includes('Despachado') || row.order_details.map(item => item.status).includes('Parcialmente Despachado')) && !row.order_returns.map(item => item.return_status).includes('Pendiente')) {
                    btn += `<a onclick="CreateOrderReturnModal(${row.id})" type="button"
                    class="btn btn-primary btn-sm mr-2" title="Crear devolucion al pedido.">
                        <i class="fas fa-plus text-white"></i>
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
            targets: [0, 1, 2, 3, 9, 10, 11]
        },
        {
            orderable: false,
            targets: [4, 5, 6, 7, 8, 12]
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

tableOrderReturns.on('click', 'button.dt-expand', function (e) {
    let tr = e.target.closest('tr');
    let row = tableOrderReturns.row(tr);

    let iconButton = $(this);

    if (row.child.isShown()) {
        row.child.hide();
        iconButton.html('<i class="fas fa-plus"></i>').removeClass('btn-danger').addClass('btn-success');
    } else {
        row.child(tableOrderReturned(row.data())).show();
        iconButton.html('<i class="fas fa-minus"></i>').removeClass('btn-success').addClass('btn-danger');
        $(`#orderReturns${row.data().id}`).DataTable({});
    }
});

function tableOrderReturned(row) {
    let table = `<table class="table table-bordered table-hover dataTable dtr-inline nowrap w-100" id="orderReturns${row.id}">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Usuario</th>
                            <th>Tipo de Devolucion</th>
                            <th>Fecha de Devolucion</th>
                            <th>Observacion</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>`;

    $.each(row.order_returns, function(index, order_return) {
        table += `<tr>
            <td> ${order_return.id} </td>
            <td> ${order_return.return_user.name + ' ' + order_return.return_user.last_name} </td>
            <td> ${order_return.return_type.name} </td>
            <td> ${order_return.return_date} </td>
            <td> ${order_return.return_observation} </td>`;

        switch (order_return.return_status) {
            case 'Pendiente':
                table += `<td><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></td>`;
                break;
            case 'Cancelado':
                table += `<td><span class="badge badge-pill badge-warning" style="color:white !important;"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span></td>`;
                break;
            case 'Aprobado':
                table += `<td><span class="badge badge-pill badge-success"><i class="fas fa-check mr-2"></i>Aprobado</span></td>`;
                break;
            default:
                table += `<td><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></td>`;
                break;
        };

        table += `<td class="text-center">
            <a href="/Dashboard/Orders/Return/Details/Index/${order_return.id}" type="button"
            class="btn btn-secondary btn-sm mr-2" title="Visualizar detalles de la orden de devolucion del pedido.">
                <i class="fas fa-eye text-white"></i>
            </a>`;

        switch (order_return.return_status) {
            case 'Pendiente':
                table += `<a onclick="EditOrderReturnModal(${order_return.id})" type="button"
                class="btn btn-primary btn-sm mr-2" title="Editar orden de devolucion del pedido.">
                    <i class="fas fa-pen text-white"></i>
                </a>`;

                table += `<a onclick="ApproveOrderReturn(${order_return.id})" type="button"
                class="btn btn-success btn-sm mr-2" title="Aprobar orden de devolucion del pedido.">
                    <i class="fas fa-check text-white"></i>
                </a>`;

                table += `<a onclick="CancelOrderReturn(${order_return.id})" type="button"
                class="btn btn-danger btn-sm mr-2" title="Cancelar orden de devolucion del pedido.">
                    <i class="fas fa-xmark text-white"></i>
                </a>`;
                break;
            case 'Cancelado':
                table += `<a onclick="PendingOrderReturn(${order_return.id})" type="button"
                class="btn btn-info btn-sm mr-2 text-white" title="Pendiente orden de devolucion del pedido.">
                    <i class="fas fa-arrows-rotate text-white"></i>
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
