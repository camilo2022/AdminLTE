let tableOrderInvoices = $('#orderInvoices').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: `/Dashboard/Orders/Invoice/Index/Query`,
        type: 'POST',
        data: function (request) {
            var columnMappings = {
                0: 'id',
                1: 'id',
                4: 'dispatch_user_id',
                5: 'consecutive',
                6: 'dispatch_status',
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
            return response.data.orderInvoices;
        },
        error: function (xhr, error, thrown) {
            toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        }
    },
    columns: [
        {
            data: 'invoices',
            render: function (data, type, row) {
                let btn = '';
                if(data.length > 0 && row.dispatch_status == 'Despachado') {
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
            data: 'dispatch_status',
            render: function (data, type, row) {
                switch (data) {
                    case 'Pendiente':
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                    case 'Rechazado':
                        return `<h5><span class="badge badge-pill badge-danger text-white"><i class="fas fa-ban mr-2 text-white"></i>Rechazado</span></h5>`;
                        break;
                    case 'Cancelado':
                        return `<h5><span class="badge badge-pill badge-warning text-white"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span></h5>`;
                        break;
                    case 'Aprobado':
                        return `<h5><span class="badge badge-pill badge-success"><i class="fas fa-check mr-2"></i>Aprobado</span></h5>`;
                        break;
                    case 'Empacado':
                        return `<h5><span class="badge badge-pill bg-gray" style="color:white !important;"><i class="fas fa-box mr-2 text-white"></i>Empacado</span></h5>`;
                        break;
                    case 'Despachado':
                        return `<h5><span class="badge badge-pill badge-primary"><i class="fas fa-share mr-2 text-white"></i>Despachado</span></h5>`;
                        break;
                    default:
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                };
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

                if(row.dispatch_status == 'Empacado') {
                    btn += `<a onclick="CreateOrderInvoiceModal(${row.id})" type="button"
                    class="btn btn-primary btn-sm mr-2 text-white" title="Agregar facturas a la orden de despacho.">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </a>`;
                } else if(row.dispatch_status == 'Despachado') {
                    btn += `<a href="/Dashboard/Orders/Invoice/Download/${row.id}" target="_blank" type="button"
                    class="btn btn-sm mr-2" style="background: mediumvioletred; color: white;" title="PDF rotulos de la orden de despacho del pedido.">
                        <i class="fas fa-file-pdf text-white"></i>
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
            targets: [1, 4, 5, 6]
        },
        {
            orderable: false,
            targets: [0, 2, 3, 7]
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

tableOrderInvoices.on('click', 'button.dt-expand', function (e) {
    let tr = e.target.closest('tr');
    let row = tableOrderInvoices.row(tr);

    let iconButton = $(this);

    if (row.child.isShown()) {
        row.child.hide();
        iconButton.html('<i class="fas fa-plus"></i>').removeClass('btn-danger').addClass('btn-success');
    } else {
        row.child(tableOrderInvoicesFiles(row.data())).show();
        iconButton.html('<i class="fas fa-minus"></i>').removeClass('btn-success').addClass('btn-danger');
    }
});

function tableOrderInvoicesFiles(row) {
    let table = `<table class="table table-bordered table-hover dataTable dtr-inline nowrap w-100" id="orderInvoices${row.id}">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Monto</th>
                            <th>Codigo</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Nombre</th>
                            <th>Extension</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>`;

    $.each(row.invoices, function(index, invoice) {
        let css = `<tr class="row-${index}${invoice.id}" onmouseenter="CssRowOrderDispatch(true, 'row-${index}${invoice.id}')" onmouseleave="CssRowOrderDispatch(false, 'row-${index}${invoice.id}')">`;
        table += `${css}
            <td rowspan="${invoice.files.length == 0 ? '1' : invoice.files.length }"> ${invoice.id} </td>
            <td rowspan="${invoice.files.length == 0 ? '1' : invoice.files.length }"> ${invoice.value.toLocaleString('es-CO', { style: 'currency', currency: 'COP' })} COP </td>
            <td rowspan="${invoice.files.length == 0 ? '1' : invoice.files.length }"> ${invoice.reference} </td>
            <td rowspan="${invoice.files.length == 0 ? '1' : invoice.files.length }"> ${invoice.date} </td>
            <td rowspan="${invoice.files.length == 0 ? '1' : invoice.files.length }"> ${row.invoice_user.name} ${row.invoice_user.last_name}</td>`;

        $.each(invoice.files, function(index, file) {
            table +=  `${index > 0 ? css : ''} <td>${file.name}</td>
                <td>${file.extension}</td>
                <td>
                    <a href="${file.path}" target="_blank"
                    class="btn btn-info btn-sm mr-2" title="Ver soporte de pago.">
                        <i class="fas fa-eye text-white"></i>
                    </a>
                </td>
            </tr>`;        
        });
    });

    table += `</tbody></table>`;


    return table;
}

function CssRowOrderDispatch(boolean, className) {
    $(`.${className}`).css({'background': boolean ? '#f2f2f2' : '#fff'});
}