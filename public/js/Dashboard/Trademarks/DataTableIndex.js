let tableTrademarks = $('#trademarks').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "/Dashboard/Trademarks/Index/Query",
        "type": "POST",
        "data": function (request) {
            var columnMappings = {
                0: 'id',
                1: 'name',
                2: 'code',
                3: 'description',
                4: 'logo',
                5: 'deleted_at'
            };
            request._token = $('meta[name="csrf-token"]').attr('content');
            request.perPage = request.length;
            request.page = (request.start / request.length) + 1;
            request.search = request.search.value;
            request.column = columnMappings[request.order[0].column];
            request.dir = request.order[0].dir;
        },
        "dataSrc": function (response) {
            response.recordsTotal = response.data.meta.pagination.count;
            response.recordsFiltered = response.data.meta.pagination.total;
            return response.data.trademarks;
        },
        "error": function (xhr, error, thrown) {
            if(xhr.responseJSON.error) {
                toastr.error(xhr.responseJSON.error.message);
            }
    
            if(xhr.responseJSON.message) {
                toastr.error(xhr.responseJSON.message);
            }
        }
    },
    "columns": [
        { data: 'id' },
        { data: 'name' },
        { data: 'code' },
        { data: 'description' },
        {
            data: null,
            render: function (data, type, row) {
                return `<img src="${data.logo}" width="50" height="50">`;
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                if (data.deleted_at === null) {
                    return `<badge class="badge badge-success">Activa</badge>`;
                } else {
                    return `<badge class="badge badge-danger">Inactiva</badge>`;
                }
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                return `<a onclick="EditTrademarkModal(${data.id})" type="button" 
                class="btn btn-primary btn-sm" title="Editar marca de prodcuto">
                    <i class="fas fa-pen text-white"></i>
                </a>`;
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                return `<a class="btn btn-danger btn-sm" onclick="DeleteTrademark(${data.id})"
                    title="Eliminar marca de prodcuto" id="DeleteTrademarkButton">
                        <i class="fas fa-trash text-white"></i>
                    </a>`;
            }
        }/* ,
        {
            data: null,
            render: function (data, type, row) {
                return `<a class="btn btn-info btn-sm" onclick="RestoreTrademark(${data.id})"
                    title="Restaurar marca de prodcuto" id="RestoreTrademarkButton">
                        <i class="fas fa-arrow-rotate-left text-white"></i>
                    </a>`;
            }
        } */
    ],
    "columnDefs": [
        { "orderable": true, "targets": [0, 1, 2, 3, 4, 5] },
        { "orderable": false, "targets": [6, 7] }
    ],
    "pagingType": "full_numbers",
    "language": {
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Último",
            "sNext": "Siguiente",
            "sPrevious": "Anterior",
        },
        "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
        "infoEmpty": "No hay registros para mostrar",
        "infoFiltered": "(filtrados de _MAX_ registros en total)",
        "emptyTable": "No hay datos disponibles.",
        "lengthMenu": "Mostrar _MENU_ registros por página.",
        "search": "Buscar:",
        "zeroRecords": "No se encontraron registros coincidentes.",
        "decimal" : ",",
        "thousands": ".",
        "sEmptyTable" : "No se ha llamado información o no está disponible.",
        "sZeroRecords" : "No se encuentran resultados.",
        "sProcessing": "Procesando..."
    },
    "pageLength": 10,
    "lengthMenu": [10, 25, 50, 100],
    "paging": true,
    "info": true,
    "searching": true,
    "autoWidth": true
});
