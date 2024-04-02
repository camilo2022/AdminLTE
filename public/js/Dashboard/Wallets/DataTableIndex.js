let tableWallets = $('#wallets').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: `/Dashboard/Wallets/Index/Query`,
        type: 'POST',
        data: function (request) {
            var columnMappings = {
                0: 'id',
                1: 'id',
                2: 'client_id',
                4: 'code',
                5: 'name',
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
            return response.data.wallets;
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
                return row.client.name;
            }
        },
        {
            data: 'client_id',
            render: function (data, type, row) {
                return row.client.document_number;
            }
        },
        {
            data: 'client_branch_id',
            render: function (data, type, row) {
                return row.name;
            }
        },
        {
            data: 'client_branch_id',
            render: function (data, type, row) {
                return row.code;
            }
        },
        {
            data: 'client',
            render: function (data, type, row) {
                return `<strong style="color: rgb(0, 123, 255); font-size: 1.5em !important;">${data.quota.toLocaleString('es-CO', { style: 'currency', currency: 'COP' })}</strong>`;
            }
        },
        {
            data: 'client',
            render: function (data, type, row) {
                return `<strong style="color: rgb(112, 225, 1); font-size: 1.5em !important;">${(data.quota - data.debt).toLocaleString('es-CO', { style: 'currency', currency: 'COP' })}</strong>`;
            }
        },
        {
            data: 'client',
            render: function (data, type, row) {
                return `<strong style="color: rgb(255, 0, 0); font-size: 1.5em !important;">${data.debt.toLocaleString('es-CO', { style: 'currency', currency: 'COP' })}</strong>`;
            }
        },
        {
            data: 'client',
            render: function (data, type, row) {

                let deuda = 0;
                let pago = 0;

                $.each(row.order_dispatches, function(index, order_dispatch) {
                    $.each(order_dispatch.invoices, function(index, invoice) {
                        deuda += invoice.value;
                    });
                    $.each(order_dispatch.payments, function(index, payment) {
                        pago += payment.value;
                    });
                });
                return `<strong style="color: rgb(255, 135, 7); font-size: 1.5em !important;">${(deuda - pago).toLocaleString('es-CO', { style: 'currency', currency: 'COP' })}</strong>`;
            }
        },
    ],
    columnDefs: [
        {
            orderable: true,
            targets: [1, 2, 4, 5]
        },
        {
            orderable: false,
            targets: [0, 3, 6, 7, 8, 9]
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

tableWallets.on('click', 'button.dt-expand', function (e) {
    let tr = e.target.closest('tr');
    let row = tableWallets.row(tr);
console.log(row.data().order_dispatches);
    let iconButton = $(this);

    if (row.child.isShown()) {
        row.child.hide();
        iconButton.html('<i class="fas fa-plus"></i>').removeClass('btn-danger').addClass('btn-success');
    } else {
        row.child(tableWalletOrderDispatches(row.data())).show();
        iconButton.html('<i class="fas fa-minus"></i>').removeClass('btn-success').addClass('btn-danger');
        $(`#orderDispatches${row.data().id}`).DataTable({});
    }
});

function tableWalletOrderDispatches(row) {
    let table = `<table class="table table-bordered table-hover dataTable dtr-inline nowrap w-100" id="orderDispatches${row.id}">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Consecutivo</th>
                            <th>Facturador</th>
                            <th>Fecha Factura</th>
                            <th>Estado de Pago</th>
                            <th>Deuda</th>
                            <th>Pago</th>
                            <th>Pendiente</th>
                            <th>Edad mora</th>
                            <th>Estado Factura</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>`;

    $.each(row.order_dispatches, function(index, order_dispatch) {
        let deuda = 0;
        let pago = 0;

        $.each(order_dispatch.invoices, function(index, invoice) {
            deuda += invoice.value;
        });

        $.each(order_dispatch.payments, function(index, payment) {
            pago += payment.value;
        });

        console.log(order_dispatch);
        table += `<tr>
            <td> ${order_dispatch.id} </td>
            <td> <h5><span class="badge badge-pill bg-info text-white"><i class="fas fa-paperclip mr-2 text-white"></i>${order_dispatch.consecutive}</span></h5> </td>
            <td> ${order_dispatch.invoice_user.name + ' ' + order_dispatch.invoice_user.last_name} </td>
            <td> ${order_dispatch.invoice_date} </td>`;
            
        switch (order_dispatch.payment_status) {
            case 'Pendiente de Pago':
                table += `<td><h5><span class="badge badge-pill badge-warning text-white"><i class="fas fa-circle-exclamation mr-2 text-white"></i>Pendiente de Pago</span></h5></td>`;
                break;
            case 'Parcialmente Pagado':
                table += `<td><h5><span class="badge badge-pill badge-primary text-white"><i class="fas fa-circle-dollar mr-2 text-white"></i>Parcialmente Pagado</span></h5></td>`;
                break;
            case 'Pagado':
                table += `<td><h5><span class="badge badge-pill badge-success text-white"><i class="fas fa-circle-xmark mr-2 text-white"></i>Cancelado</span></h5></td>`;
                break;
            case 'Cancelado':
                table += `<td><h5><span class="badge badge-pill badge-danger"><i class="fas fa-circle-check mr-2"></i>Aprobado</span></h5></td>`;
                break;
            default:
                table += `<td><h5><span class="badge badge-pill badge-warning text-white"><i class="fas fa-circle-exclamation mr-2 text-white"></i>Pendiente de Pago</span></h5></td>`;
                break;
        };

        table += `<td><strong style="color: rgb(255, 0, 0); font-size: 1.5em !important;">${deuda.toLocaleString('es-CO', { style: 'currency', currency: 'COP' })}</strong></td>`;

        table += `<td><strong style="color: rgb(112, 225, 1); font-size: 1.5em !important;">${pago.toLocaleString('es-CO', { style: 'currency', currency: 'COP' })}</strong></td>`;

        table += `<td><strong style="color: rgb(0, 123, 255); font-size: 1.5em !important;">${(deuda - pago).toLocaleString('es-CO', { style: 'currency', currency: 'COP' })}</strong></td>`;

        let fechaFactura = new Date(order_dispatch.invoice_date);

        let fechaActual = new Date();

        let diffDays = Math.ceil(Math.abs(fechaActual - fechaFactura) / (1000 * 60 * 60 * 24));

        switch (true) {
            case diffDays <= 30:
                table += `<td>0 a 30</td>`;
                table += `<td>Vigente</td>`;
                break;
            case diffDays <= 60:
                table += `<td>31 a 60</td>`;
                table += `<td>Vencida</td>`;
                break;
            case diffDays <= 90:
                table += `<td>61 a 90</td>`;
                table += `<td>Vencida</td>`;
                break;
            case diffDays <= 120:
                table += `<td>91 a 120</td>`;
                table += `<td>Vencida</td>`;
                break;
            case diffDays <= 150:
                table += `<td>121 a 150</td>`;
                table += `<td>Vencida</td>`;
                break;
            case diffDays <= 180:
                table += `<td>151 a 180</td>`;
                table += `<td>Vencida</td>`;
                break;
            case diffDays > 180:
                table += `<td>Mayor a 181</td>`;
                table += `<td>Vencida</td>`;
                break;
            default:
                table += `<td>-</td>`;
                table += `<td>Pago</td>`;
                break;
        }

        table += `<td class="text-center">

            <a onclick="AssignPaymentWalletModal(${order_dispatch.id})" type="button"
            class="btn btn-primary btn-sm mr-2" title="Agregar pago a la deuda de la orden de despacho.">
                <i class="fas fa-hand-holding-dollar text-white"></i>
            </a>`;

        table += `</td>
            </tr>`;
    });

    table += `</tbody></table>`;


    return table;
}
