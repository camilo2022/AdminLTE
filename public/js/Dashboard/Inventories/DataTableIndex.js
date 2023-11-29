let tableInventories = $('#inventories').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: `/Dashboard/Inventories/Index/Query`,
        type: 'POST',
        data: function (request) {
            var columnMappings = {
                0: 'id',
                1: 'product_id',
                2: 'size_id',
                3: 'warehouse_id',
                4: 'color_id',
                5: 'quantity',
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
            return response.data.inventories;
        },
        error: function (xhr, error, thrown) {
            toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        }
    },
    columns: [
        { data: 'id' },
        {
            data: 'product',
            render: function (data, type, row) {
                return data.code;
            }
        },
        {
            data: 'size',
            render: function (data, type, row) {
                return data.code;
            }
        },
        {
            data: 'warehouse',
            render: function (data, type, row) {
                return `${data.name} - ${data.code}`;
            }
        },
        {
            data: 'color',
            render: function (data, type, row) {
                return `${data.name} - ${data.code}`;
            }
        },
        {data: 'quantity'}
    ],
    columnDefs: [
        {
            targets: 0,
            orderable: true,
            targets: [0, 1, 2, 3, 4, 5]
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
