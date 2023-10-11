let tableUsers = $('#users').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "/Dashboard/Users/Index/Query",
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
                return `<a onclick="PasswordUserModal(${data.id}, '${data.email}')"
                    type="button" data-target="#PasswordUserModal" data-toggle='modal'
                    class="btn bg-dark btn-sm" title="Recuperar contraseña">
                        <i class="fas fa-user-gear text-white"></i>
                    </a>`;
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                return `<a onclick="EditUserModal(${data.id}, '${CleanText(data.name)}',
                '${CleanText(data.last_name)}', '${CleanText(data.document_number)}',
                '${CleanText(data.phone_number)}', '${CleanText(data.address)}',
                '${CleanText(data.email)}')"
                    type="button" data-target="#EditUserModal" data-toggle='modal'
                    class="btn btn-primary btn-sm" title="Editar usuario">
                        <i class="fas fa-user-pen text-white"></i>
                    </a>`;
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                return `<a class="btn btn-danger btn-sm" onclick="DeleteUser(${data.id})"
                    title="Eliminar usuario" id="DeleteUserButton">
                        <i class="fas fa-user-minus text-white"></i>
                    </a>`;
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                return `<a onclick="AssignRoleAndPermissionUserModal(${data.id}, '${CleanText(data.email)}')"
                    type="button" class="btn btn-success btn-sm"
                    title="Asignar rol y permisos al usuario">
                        <i class="fas fa-user-unlock text-white"></i>
                    </a>`;
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                return `<a onclick="RemoveRoleAndPermissionUserModal(${data.id}, '${CleanText(data.email)}')"
                    type="button" class="btn bg-orange btn-sm"
                    title="Remover rol y permisos al usuario">
                        <i class="fas fa-user-lock text-white"></i>
                    </a>`;
            }
        },
    ],
    "columnDefs": [
        { "orderable": true, "targets": [0, 1, 2, 3, 4, 5, 6] },
        { "orderable": false, "targets": [7, 8, 9, 10, 11] }
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
    },
    "pageLength": 10,
    "lengthMenu": [10, 25, 50, 100],
    "paging": true,
    "info": true,
    "searching": true,
    "autoWidth": true
});
