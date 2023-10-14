let tableUsers = $('#users').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "/Dashboard/Users/Inactives/Query",
        "type": "POST",
        "data": function (request) {
            var columnMappings = {
                0: 'id',
                1: 'name',
                2: 'last_name',
                3: 'document_number',
                4: 'phone_number',
                5: 'address',
                6: 'email'
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
            return response.data.users;
        }
    },
    "columns": [
        { data: 'id' },
        { data: 'name' },
        { data: 'last_name' },
        { data: 'document_number' },
        { data: 'phone_number' },
        { data: 'address' },
        { data: 'email' },
        {
            data: null,
            render: function (data, type, row) {
                return `<a class="btn btn-info btn-sm" onclick="RestoreUser(${data.id})"
                    title="Restaurar usuario">
                        <i class="fas fa-user-plus text-white"></i>
                    </a>`;
            }
        },
    ],
    "columnDefs": [
        { "orderable": true, "targets": [0, 1, 2, 3, 4, 5, 6] },
        { "orderable": false, "targets": [7] }
    ],
    "pagingType": "full_numbers",
    "language": {
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Último",
            "sNext": "Siguiente",
            "sPrevious": "Anterior",
            "sProcessing": "Procesando...",
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

tableUsers.on('error.dt', function (e, settings, techNote, message) {
    e.preventDefault();
    toastr.info(message);
});