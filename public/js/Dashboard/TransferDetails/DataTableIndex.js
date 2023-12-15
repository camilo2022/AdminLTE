let warehousesUser2 = [];
let tableTransferDetails = $('#transferDetails').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: `/Dashboard/Transfers/Details/Index/Query`,
        type: 'POST',
        data: function (request) {
            var columnMappings = {
                0: 'id',
                1: 'transfer_id',
                2: 'product_id',
                3: 'size_id',
                4: 'color_id',
                5: 'tone_id',
                6: 'quantity',
                7: 'status',
            };
            request._token = $('meta[name="csrf-token"]').attr('content');
            request.perPage = request.length;
            request.page = (request.start / request.length) + 1;
            request.search = request.search.value;
            request.column = columnMappings[request.order[0].column];
            request.dir = request.order[0].dir;
            request.transfer_id = $('#ShowTransferButton').attr('data-id');
        },
        dataSrc: function (response) {
            response.recordsTotal = response.data.transferDetails.meta.pagination.count;
            response.recordsFiltered = response.data.transferDetails.meta.pagination.total;
            warehousesUser2 = response.data.warehouses;
            return response.data.transferDetails.transferDetails;
        },
        error: function (xhr, error, thrown) {
            toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        }
    },
    columns: [
        { data: 'id' },
        {
            data: 'transfer_id',
            render: function (data, type, row) {
                return row.transfer.consecutive;
            }
        },
        {
            data: 'product_id',
            render: function (data, type, row) {
                return row.product.code;
            }
        },
        {
            data: 'size_id',
            render: function (data, type, row) {
                return row.size.code;
            }
        },
        {
            data: 'color_id',
            render: function (data, type, row) {
                return `${row.color.name} | ${row.color.code}`;
            }
        },
        {
            data: 'tone_id',
            render: function (data, type, row) {
                return row.tone.name;
            }
        },
        { data: 'quantity' },
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

                if (data === null && row.transfer.status == 'Pendiente' && row.transfer.from_user_id == $('meta[name="user-id"]').attr('content')) {
                    btn += `<a onclick="EditTransferDetailModal(${row.id})" type="button"
                    class="btn btn-primary btn-sm mr-2" title="Editar detalle de la transferencia">
                        <i class="fas fa-pen text-white"></i>
                    </a>`;

                    btn += `<a onclick="DeleteTransferDetail(${row.id})" type="button"
                    class="btn btn-danger btn-sm mr-2" title="Eliminar detalle de la transferencia">
                        <i class="fas fa-trash text-white"></i>
                    </a>`;
                }

                if (data === null && row.transfer.status === 'Pendiente' && warehousesUser2.includes(row.transfer.to_warehouse_id)) {
                    if(row.status === 'Cancelado') {
                        btn += `<a onclick="PendingTransferDetail(${row.id})" type="button"
                        class="btn btn-info btn-sm mr-2" title="Pendiente detalle de la transferencia">
                            <i class="fas fa-hourglass-end text-white"></i>
                        </a>`;
                    }
                    if(row.status === 'Pendiente') {
                        btn += `<a onclick="CancelTransferDetail(${row.id})" type="button"
                        class="btn bg-orange btn-sm mr-2" title="Cancelar detalle de la transferencia">
                            <i class="fas fa-xmark text-white"></i>
                        </a>`;
                    }
                }
                btn += `</div>`;
                return btn;
            }
        }
    ],
    columnDefs: [
        {
            orderable: true,
            targets: [0, 1, 2, 3, 4, 5, 6, 7]
        },
        {
            orderable: false,
            targets: [8]
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
