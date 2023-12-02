let tableTransfers = $('#transfers').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: `/Dashboard/Transfers/Index/Query`,
        type: 'POST',
        data: function (request) {
            var columnMappings = {
                0: 'id',
                1: 'consecutive',
                2: 'from_user_id',
                3: 'form_date',
                4: 'from_observation',
                5: 'to_user_id',
                6: 'to_date',
                7: 'to_observation',
                8: 'status'
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
            return response.data.transfers;
        },
        error: function (xhr, error, thrown) {
            toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        }
    },
    columns: [
        { data: 'id' },
        { data: 'consecutive' },
        { 
            data: 'from_user',
            render: function (data, type, row) {
                return `${data.name} ${data.last_name}`;
            }
        },
        { data: 'form_date' },
        { data: 'from_observation' },
        { 
            data: 'to_user',
            render: function (data, type, row) {
                return `${data.name} ${data.last_name}`;
            }
        },
        { data: 'to_date' },
        { data: 'to_observation' },
        {
            data: 'status',
            render: function (data, type, row) {
                switch (data) {
                    case 'Cancelado':
                        return `<h5><span class="badge badge-outline badge-warning"><i class="fas fa-xmark mr-2"></i>Cancelado</span></h5>`;
                    case 'Eliminado':
                        return `<h5><span class="badge badge-outline badge-danger"><i class="fas fa-trash mr-2"></i>Eliminado</span></h5>`;
                    case 'Pendiente':
                        return `<h5><span class="badge badge-outline badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                    case 'Aprobado':
                        return `<h5><span class="badge badge-outline badge-success"><i class="fas fa-check mr-2"></i>Aprobado</span></h5>`;
                    default:
                        return `<h5><span class="badge badge-outline badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                }
            }
        },
        {
            data: 'deleted_at',
            render: function (data, type, row) {
                let btn = `<div class="text-center" style="width: 100%;">`;
                
                btn += `<a onclick="ShowTransferModal(${row.id})" type="button"
                class="btn btn-info btn-sm mr-2" title="Visualizar transferencia">
                    <i class="fas fa-eye text-white"></i>
                </a>`;

                if (data === null && row.status === 'Pendiente' && row.to_user_id === null) {
                    btn += `<a onclick="EditTransferModal(${row.id})" type="button"
                    class="btn btn-primary btn-sm mr-2" title="Editar transferencia">
                        <i class="fas fa-pen text-white"></i>
                    </a>`;

                    btn += `<a onclick="DeleteTransfer(${row.id})" type="button"
                    class="btn btn-danger btn-sm mr-2" title="Eliminar transferencia">
                        <i class="fas fa-trash text-white"></i>
                    </a>`;
                } else if (data === null && row.status === 'Pendiente' && row.to_user_id !== null) {
                    btn += `<a onclick="AprroveTransfer(${row.id})" type="button"
                    class="btn btn-success btn-sm mr-2" title="Aceptar transferencia">
                        <i class="fas fa-check text-white"></i>
                    </a>`;

                    btn += `<a onclick="CancelTransfer(${row.id})" type="button"
                    class="btn bg-orange btn-sm mr-2" title="Cancelar transferencia">
                        <i class="fas fa-xmark text-white"></i>
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
            targets: [0, 1, 2, 3, 4, 5, 6, 7, 8]
        },
        {
            orderable: false,
            targets: [9]
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
