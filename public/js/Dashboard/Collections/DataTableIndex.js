let tableCollections = $('#collections').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "/Dashboard/Collections/Index/Query",
        "type": "POST",
        "data": function (request) {
            var columnMappings = {
                0: 'id',
                1: 'name',
                2: 'code',
                3: 'start_date',
                4: 'end_date',
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
            return response.data.collections;
        },
        "error": function (xhr, error, thrown) {
            toastr.error(xhr.responseJSON.error.message);
        }
    },
    "columns": [
        { data: 'id' },
        { data: 'name' },
        { data: 'code' },
        { data: 'start_date' },
        { data: 'end_date' },
        {
            data: null,
            render: function (data, type, row) {
                if (data.deleted_at === null) {
                    return `<span class="badge bg-success">Activa</span>`;
                } else {
                    return `<span class="badge bg-danger">Inactiva</span>`;
                }
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                return `<a onclick="EditCollectionModal(${data.id})" type="button" 
                class="btn btn-primary btn-sm" title="Editar correria">
                    <i class="fas fa-pen text-white"></i>
                </a>`;
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                return `<a class="btn btn-danger btn-sm" onclick="DeleteCollection(${data.id})"
                    title="Eliminar correria" id="DeleteCollectionButton">
                        <i class="fas fa-trash text-white"></i>
                    </a>`;
            }
        }
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
