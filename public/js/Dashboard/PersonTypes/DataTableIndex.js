let tablePersonTypes = $('#personTypes').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: `/Dashboard/PersonTypes/Index/Query`,
        type: 'POST',
        data: function (request) {
            var columnMappings = {
                0: 'id',
                1: 'name',
                2: 'code',
                3: 'require_references',
                4: 'deleted_at'
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
            return response.data.personTypes;
        },
        error: function (xhr, error, thrown) {
            toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        }
    },
    columns: [
        { data: 'id' },
        { data: 'name' },
        { data: 'code' },
        { 
            data: 'require_people' ,
            render: function (data, type, row) {
                if (data === 1) {
                    return `<h5><span class="badge badge-pill badge-success"><i class="far fa-circle-check mr-2"></i>Requerido</span></h5>`;
                } else {
                    return `<h5><span class="badge badge-pill badge-danger"><i class="far fa-circle-xmark mr-2"></i>No requerido</span></h5>`;
                }
            }
        },
        {
            data: 'deleted_at',
            render: function (data, type, row) {
                if (data === null) {
                    return `<h5><span class="badge badge-pill badge-success"><i class="fas fa-check mr-2"></i>Activa</span></h5>`;
                } else {
                    return `<h5><span class="badge badge-pill badge-danger"><i class="fas fa-xmark mr-2"></i>Inactiva</span></h5>`;
                }
            }
        },
        {
            data: 'deleted_at',
            render: function (data, type, row) {
                let btn = `<div class="text-center" style="width: 100px;">`;
                if (data === null) {
                    btn += `<a onclick="ShowPersonTypeModal(${row.id})" type="button"
                    class="btn btn-info btn-sm mr-2" title="Asignar y remover tipos de documentos">
                        <i class="fas fa-eye text-white"></i>
                    </a>`;

                    btn += `<a onclick="EditPersonTypeModal(${row.id})" type="button"
                    class="btn btn-primary btn-sm mr-2" title="Editar tipo de Persona">
                        <i class="fas fa-pen text-white"></i>
                    </a>`;

                    btn += `<a onclick="DeletePersonType(${row.id})" type="button"
                    class="btn btn-danger btn-sm mr-2" title="Eliminar tipo de Persona">
                        <i class="fas fa-trash text-white"></i>
                    </a>`;
                } else {
                    btn += `<a onclick="RestorePersonType(${row.id})" type="button"
                    class="btn btn-info btn-sm mr-2" title="Restaurar tipo de Persona">
                        <i class="fas fa-arrow-rotate-left text-white"></i>
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
            targets: [0, 1, 2, 3, 4]
        },
        {
            orderable: false,
            targets: [5]
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
