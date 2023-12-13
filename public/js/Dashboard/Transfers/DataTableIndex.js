let warehousesUser = [];
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
                2: 'from_warehouse_id',
                3: 'from_user_id',
                4: 'form_date',
                5: 'from_observation',
                6: 'to_warehouse_id',
                7: 'to_user_id',
                8: 'to_date',
                9: 'to_observation',
                10: 'status'
            };
            request._token = $('meta[name="csrf-token"]').attr('content');
            request.perPage = request.length;
            request.page = (request.start / request.length) + 1;
            request.search = request.search.value;
            request.column = columnMappings[request.order[0].column];
            request.dir = request.order[0].dir;
        },
        dataSrc: function (response) {
            response.recordsTotal = response.data.transfers.meta.pagination.count;
            response.recordsFiltered = response.data.transfers.meta.pagination.total;
            warehousesUser = response.data.warehouses;
            return response.data.transfers.transfers;
        },
        error: function (xhr, error, thrown) {
            toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        }
    },
    columns: [
        { data: 'id' },
        { data: 'consecutive' },
        { 
            data: 'from_warehouse_id',
            render: function (data, type, row) {
                return `${row.from_warehouse.name} - ${row.from_warehouse.code}`;
            }
        },
        { 
            data: 'from_user_id',
            render: function (data, type, row) {
                return `${row.from_user.name} ${row.from_user.last_name}`;
            }
        },
        { data: 'from_date' },
        { data: 'from_observation' },
        { 
            data: 'to_warehouse_id',
            render: function (data, type, row) {
                return `${row.to_warehouse.name} - ${row.to_warehouse.code}`;
            }
        },
        { 
            data: 'to_user_id',
            render: function (data, type, row) {
                return data === null ? '' : `${row.to_user.name} ${row.to_user.last_name}`;
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

                if (data === null && row.status == 'Pendiente' && row.from_user_id === $('meta[name="user-id"]').attr('content')) {
                    btn += `<a onclick="EditTransferModal(${row.id})" type="button"
                    class="btn btn-primary btn-sm mr-2" title="Editar transferencia">
                        <i class="fas fa-pen text-white"></i>
                    </a>`;

                    btn += `<a onclick="DeleteTransfer(${row.id})" type="button"
                    class="btn btn-danger btn-sm mr-2" title="Eliminar transferencia">
                        <i class="fas fa-trash text-white"></i>
                    </a>`;
                } 
                
                if (data === null && row.status === 'Pendiente' && warehousesUser.includes(row.to_warehouse_id)) {
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
            targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
        },
        {
            orderable: false,
            targets: [11]
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
