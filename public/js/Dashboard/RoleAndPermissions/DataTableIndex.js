let tableRolesAndPermissions = $('#rolesAndPermissions').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "/Dashboard/RolesAndPermissions/Index/Query",
        "type": "POST",
        "data": function (request) {
            var columnMappings = {
                0: 'id',
                1: 'name',
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
            return response.data.roles;
        }
    },
    "columns": [
        { data: 'id' },
        { data: 'role' },
        {
            data: null,
            render: function(data, type, row) {
                let div = $('<div>');
                // Utiliza $.each() para iterar a través de los elementos del array y crear un <span> para cada permiso.
                $.each(data.permissions, function(index, permission) {
                    let span = $('<label>').text(permission.name);
                    span.attr('class', 'badge badge-info mr-1');
                    div.append(span);
                });

                return div.html();
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                let permissions = [];
                $.each(data.permissions, function(index, permission) {
                    permissions.push(permission.name);
                });
                return `<a onclick="EditRoleAndPermissionsModal(${data.id}, '${data.role}', ${JSON.stringify(permissions).replace(/"/g, "'")})"
                    type="button" data-target="#EditRoleAndPermissionsModal" data-toggle='modal'
                    class="btn btn-primary btn-sm" title="Editar rol y permisos">
                        <i class="fas fa-folder-gear text-white"></i>
                    </a>`;
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                let permission_id = [];
                $.each(data.permissions, function(index, permission) {
                    permission_id.push(permission.id);
                });
                return `<a class="btn btn-danger btn-sm" onclick="DeleteRoleAndPermissions(${data.id}, ${JSON.stringify(permission_id)})"
                    title="Eliminar rol y permisos" id="DeleteRoleAndPermissionsButton">
                        <i class="fas fa-folder-minus text-white"></i>
                    </a>`;
            }
        },
    ],
    "columnDefs": [
        { "orderable": true, "targets": [0, 1] },
        { "orderable": false, "targets": [2, 3, 4] }
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
