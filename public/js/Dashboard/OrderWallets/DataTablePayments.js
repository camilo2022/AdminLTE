let tableOrderWalletPayments = $('#orderWalletPayments').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: `/Dashboard/Orders/Wallet/Payments/Query`,
        type: 'POST',
        data: function (request) {
            var columnMappings = {
                0: 'id',
                1: 'value',
                2: 'reference',
                3: 'date',
                4: 'payment_type_id',
                5: 'bank_id',
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
        { data: 'id' },
        { data: 'value' },
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
            data: 'files',
            render: function(data, type, row) {
                var table = `<table border="1" class="w-100">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Extension</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>`;

                $.each(data, function(index, file) {
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

                return data.length > 0 ? table : '';
            }
        },
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
