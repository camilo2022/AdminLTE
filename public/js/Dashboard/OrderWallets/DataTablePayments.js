let tableOrderWalletPayments = $('#orderWalletPayments').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: `/Dashboard/Orders/Wallet/Payments/Query`,
        type: 'POST',
        data: function (request) {
            var columnMappings = {
                0: 'id',
                1: 'id',
                2: 'value',
                3: 'reference',
                4: 'date',
                5: 'payment_type_id',
                6: 'bank_id',
            };
            request._token = $('meta[name="csrf-token"]').attr('content');
            request.order_id = $('#IndexOrderWalletDetail').attr('data-id');
            request.perPage = request.length;
            request.page = (request.start / request.length) + 1;
            request.search = request.search.value;
            request.column = columnMappings[request.order[0].column];
            request.dir = request.order[0].dir;
        },
        dataSrc: function (response) {
            response.recordsTotal = response.data.meta.pagination.count;
            response.recordsFiltered = response.data.meta.pagination.total;
            return response.data.orderWalletPayments;
        },
        error: function (xhr, error, thrown) {
            toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        }
    },
    columns: [
        {
            data: 'files',
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
            data: 'value',
            render: function(data, type, row) {
                return `${(data).toLocaleString('es-CO', { style: 'currency', currency: 'COP' })} COP`;
            }
        },
        { data: 'reference' },
        { data: 'date' },
        {
            data: 'payment_type_id',
            render: function (data, type, row) {
                return row.payment_type.name;
            }
        },
        {
            data: 'bank_id',
            render: function (data, type, row) {
                return row.bank === null ? '' : row.bank.name;
            }
        },
        {
            data: 'deleted_at',
            render: function (data, type, row) {
                let btn = `<div class="text-center" style="width: 100%;">`;

                if(row.model.wallet_status === 'Pendiente') {
                    btn += `<a onclick="RemovePaymentOrderSeller(${row.id})"
                    class="btn btn-danger btn-sm mr-2" title="Eliminar pago del pedido.">
                        <i class="fas fa-trash text-white"></i>
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
            targets: [0, 1, 2, 3, 4, 5]
        },
        {
            orderable: false,
            targets: [6]
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

tableOrderWalletPayments.on('click', 'button.dt-expand', function (e) {
    let tr = e.target.closest('tr');
    let row = tableOrderWalletPayments.row(tr);

    let iconButton = $(this);

    if (row.child.isShown()) {
        row.child.hide();
        iconButton.html('<i class="fas fa-plus"></i>').removeClass('btn-danger').addClass('btn-success');
    } else {
        row.child(tableOrderWalletPaymentFiles(row.data())).show();
        iconButton.html('<i class="fas fa-minus"></i>').removeClass('btn-success').addClass('btn-danger');
        $(`#files${row.data().id}`).DataTable({});
    }
});

function tableOrderWalletPaymentFiles(row) {
    let table = `<table class="table table-bordered table-hover dataTable dtr-inline nowrap w-100" id="files${row.id}">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Extension</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>`;

    $.each(row.files, function(index, file) {
        table += `<tr>
                        <td>${file.id}</td>
                        <td>${file.name}</td>
                        <td>${file.extension}</td>
                        <td>
                            <a href="${file.path}" target="_blank"
                            class="btn btn-info btn-sm mr-2" title="Ver soporte de pago.">
                                <i class="fas fa-eye text-white"></i>
                            </a>
                        </td>
                    </tr>`;
    });

    table += `</tbody></table>`;

    return table;
}
