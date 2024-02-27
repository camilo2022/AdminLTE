let tableOrderSellerPayments = $('#orderSellerPayments').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: `/Dashboard/Orders/Seller/Payments/Query`,
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
            request.order_id = $('#IndexOrderSellerDetail').attr('data-id');
            request.perPage = request.length;
            request.page = (request.start / request.length) + 1;
            request.search = request.search.value;
            request.column = columnMappings[request.order[0].column];
            request.dir = request.order[0].dir;
        },
        dataSrc: function (response) {
            response.recordsTotal = response.data.meta.pagination.count;
            response.recordsFiltered = response.data.meta.pagination.total;
            return response.data.orderSellerPayments;
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
            data: 'deleted_at',
            render: function (data, type, row) {
                let btn = `<div class="text-center" style="width: 100%;">`;
                
                btn += `<a class="btn btn-info btn-sm mr-2" title="Visualizar comprobantes de pago del pedido.">
                    <i class="fas fa-eye text-white"></i>
                </a>`;

                btn += `<a onclick="RemovePaymentOrderSeller(${row.id})"
                class="btn btn-danger btn-sm mr-2" title="Eliminar pago del pedido.">
                    <i class="fas fa-trash text-white"></i>
                </a>`;

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
