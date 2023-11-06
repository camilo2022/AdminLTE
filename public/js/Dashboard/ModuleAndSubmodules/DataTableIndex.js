let tableModulesAndSubmodules = $('#modulesAndSubmodules').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "/Dashboard/ModulesAndSubmodules/Index/Query",
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
            return response.data.modules;
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
        { data: 'module' },
        {
            data: null,
            render: function(data, type, row) {
                let icon = $('<i>');
                icon.addClass(data.icon);
                return icon.prop('outerHTML');
            }
        },
        {
            data: null,
            render: function(data, type, row) {
                let rolesDiv = $('<div>');
                $.each(data.roles, function(index, role) {
                    let roleSpan = $('<span>')
                        .text(role.name);
                    rolesDiv.append(roleSpan)
                        .append('<br>');
                });
                return rolesDiv.prop('outerHTML');
            }
        },
        {
            data: null,
            render: function(data, type, row) {
                let submodulesDiv = $('<div>');
                $.each(data.submodules, function(index, submodule) {
                    let submoduleSpan = $('<span>')
                        .text(submodule.name);
                    submodulesDiv.append(submoduleSpan)
                        .append('<br>');
                });
                return submodulesDiv.prop('outerHTML');
            }
        },
        {
            data: null,
            render: function(data, type, row) {
                let submoduleUrlsDiv = $('<div>');
                $.each(data.submodules, function(index, submodule) {
                    let urlSpan = $('<span>')
                        .text(submodule.url);
                    submoduleUrlsDiv.append(urlSpan)
                        .append('<br>');
                });
                return submoduleUrlsDiv.prop('outerHTML');
            }
        },
        {
            data: null,
            render: function(data, type, row) {
                let submoduleIconsDiv = $('<div>');
                $.each(data.submodules, function(index, submodule) {
                    let icon = $('<i>');
                    icon.addClass(submodule.icon);
                    submoduleIconsDiv.append(icon.prop('outerHTML'))
                        .append('<br>');
                });
                return submoduleIconsDiv.prop('outerHTML');
            }
        },
        {
            data: null,
            render: function(data, type, row) {
                let submodulePermissionsDiv = $('<div>');
                $.each(data.submodules, function(index, submodule) {
                    let permissionSpan = $('<span>')
                        .text(submodule.permission.name);
                    submodulePermissionsDiv.append(permissionSpan)
                        .append('<br>');
                });
                return submodulePermissionsDiv.prop('outerHTML');
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                return `<a onclick="EditModuleAndSubmodulesModal(${data.id})" type="button"
                    class="btn btn-primary btn-sm" title="Editar modulo y submomdulos">
                        <i class="fas fa-shield-keyhole text-white"></i>
                    </a>`;
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                return `<a class="btn btn-danger btn-sm" onclick="DeleteModuleAndSubmodules(${data.id})"
                    title="Eliminar modulo y submodulos" id="DeleteModuleAndSubumodulesButton">
                        <i class="fas fa-shield-minus text-white"></i>
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
