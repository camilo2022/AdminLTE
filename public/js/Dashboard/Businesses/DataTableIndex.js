let tableBusinesses = $('#businesses').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: `/Dashboard/Businesses/Index/Query`,
        type: 'POST',
        data: function (request) {
            var columnMappings = {
                0: 'id',
                1: 'name',
                2: 'document_number',
                3: 'telephone_number',
                4: 'email',
                5: 'country_id',
                6: 'departament_id',
                7: 'city_id',
                8: 'address',
                9: 'neighbourhood',
                10: 'description',
                11: 'deleted_at'
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
            return response.data.businesses;
        },
        error: function (xhr, error, thrown) {
            toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        }
    },
    columns: [
        { data: 'id' },
        { data: 'name' },
        { data: 'document_number' },
        { data: 'telephone_number' },
        { data: 'email' },
        { data: 'country' },
        { data: 'departament' },
        { data: 'city' },
        { data: 'address' },
        { data: 'neighbourhood' },
        { data: 'description' },
        {
            data: 'deleted_at',
            render: function (data, type, row) {
                if (data === null) {
                    return `<h5><span class="badge badge-outline badge-success"><i class="fas fa-check mr-2"></i>Activa</span></h5>`;
                } else {
                    return `<h5><span class="badge badge-outline badge-danger"><i class="fas fa-xmark mr-2"></i>Inactiva</span></h5>`;
                }
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                let btn = ``;
                if (data.deleted_at === null) {
                    btn += `<a onclick="EditBusinessModal(${data.id})" type="button"
                    class="btn btn-primary btn-sm mr-2" title="Editar tipo de paquete">
                        <i class="fas fa-pen text-white"></i>
                    </a>`;

                    btn += `<a onclick="DeleteBusiness(${data.id})" type="button"
                    class="btn btn-danger btn-sm mr-2" title="Eliminar tipo de paquete">
                        <i class="fas fa-trash text-white"></i>
                    </a>`;
                } else {
                    btn += `<a onclick="RestoreBusiness(${data.id})" type="button"
                    class="btn btn-info btn-sm mr-2"title="Restaurar tipo de paquete">
                        <i class="fas fa-arrow-rotate-left text-white"></i>
                    </a>`;
                }
                return btn;
            }
        }
    ],
    columnDefs: [
        {
            orderable: true,
            targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
        },
        {
            orderable: false,
            targets: [12],
            className: "text-center"
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
