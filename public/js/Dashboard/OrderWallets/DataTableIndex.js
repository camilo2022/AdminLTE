let tableOrderWallets = $('#orderWallets').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: `/Dashboard/Orders/Wallet/Index/Query`,
        type: 'POST',
        data: function (request) {
            var columnMappings = {
                0: 'id',
                1: 'client_id',
                2: 'client_branch_id',
                8: 'seller_date',
                9: 'seller_user_id',
                10: 'seller_status',
                11: 'wallet_status',
                12: 'dispatched_status',
                13: 'correria_id'
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
            return response.data.orderWallets;
        },
        error: function (xhr, error, thrown) {
            toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        }
    },
    columns: [
        { data: 'id' },
        {
            data: 'client_id',
            render: function (data, type, row) {
                return `${row.client.document_number}-${row.client_branch.code}`;
            }
        },
        {
            data: 'client_branch_id',
            render: function (data, type, row) {
                return `${row.client.name} - ${row.client_branch.name}`;
            }
        },
        {
            data: 'client_branch_id',
            render: function (data, type, row) {
                return row.client_branch.country.name;
            }
        },
        {
            data: 'client_branch_id',
            render: function (data, type, row) {
                return row.client_branch.departament.name;
            }
        },
        {
            data: 'client_branch_id',
            render: function (data, type, row) {
                return row.client_branch.city.name;
            }
        },
        {
            data: 'client_branch_id',
            render: function (data, type, row) {
                return row.client_branch.address;
            }
        },
        {
            data: 'client_branch_id' ,
            render: function (data, type, row) {
                return row.client_branch.neighborhood;
            }
        },
        { data: 'seller_date' },
        {
            data: 'seller_user_id' ,
            render: function (data, type, row) {
                return `${row.seller_user.name} ${row.seller_user.last_name}`;
            }
        },
        {
            data: 'seller_status',
            render: function (data, type, row) {
                switch (data) {
                    case 'Cancelado':
                        return `<h5><span class="badge badge-pill badge-danger text-white"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span></h5>`;
                        break;
                    case 'Pendiente':
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                    case 'Aprobado':
                        return `<h5><span class="badge badge-pill badge-success"><i class="fas fa-check mr-2"></i>Aprobado</span></h5>`;
                        break;
                    default:
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                }
            }
        },
        {
            data: 'wallet_status',
            render: function (data, type, row) {
                switch (data) {
                    case 'Cancelado':
                        return `<h5><span class="badge badge-pill badge-danger text-white"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span></h5>`;
                        break;
                    case 'Pendiente':
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                    case 'Parcialmente Aprobado':
                        return `<h5><span class="badge badge-pill badge-warning text-white"><i class="fas fa-check mr-2 text-white"></i>Parcialmente Aprobado</span></h5>`;
                        break;
                    case 'Aprobado':
                        return `<h5><span class="badge badge-pill badge-success"><i class="fas fa-check-double mr-2"></i>Aprobado</span></h5>`;
                        break;
                    default:
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                }
            }
        },
        {
            data: 'dispatched_status',
            render: function (data, type, row) {
                switch (data) {
                    case 'Cancelado':
                        return `<h5><span class="badge badge-pill bg-orange text-white" style="color:white !important;"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span></h5>`;
                        break;
                    case 'Rechazado':
                        return `<span class="badge badge-pill badge-danger text-white" id="dispatched_status"><i class="fas fa-ban mr-2 text-white"></i>Rechazado</span>`;
                        break
                    case 'Pendiente':
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                    case 'Parcialmente Aprobado':
                        return `<h5><span class="badge badge-pill badge-warning text-white"><i class="fas fa-check mr-2 text-white"></i>Parcialmente Aprobado</span></h5>`;
                        break;
                    case 'Aprobado':
                        return `<h5><span class="badge badge-pill badge-success"><i class="fas fa-check-double mr-2"></i>Aprobado</span></h5>`;
                        break;
                    case 'Parcialmente Devuelto':
                        return `<h5><span class="badge badge-pill bg-gray"><i class="fas fa-reply mr-2"></i>Parcialmente Devuelto</span></h5>`;
                        break;
                    case 'Devuelto':
                        return `<h5><span class="badge badge-pill bg-dark text-white"><i class="fas fa-reply-all mr-2 text-white"></i>Devuelto</span></h5>`;
                        break;
                    case 'Parcialmente Despachado':
                        return `<h5><span class="badge badge-pill bg-purple text-white" style="color:white !important;"><i class="fas fa-share mr-2 text-white"></i>Parcialmente Despachado</span></h5>`;
                        break;
                    case 'Despachado':
                        return `<h5><span class="badge badge-pill badge-primary"><i class="fas fa-share-all mr-2"></i>Despachado</span></h5>`;
                        break;
                    default:
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                }
            }
        },
        {
            data: 'correria_id',
            render: function (data, type, row) {
                return row.correria.code;
            }
        },
        {
            data: 'wallet_status',
            render: function (data, type, row) {
                let btn = `<div class="text-center" style="width: 100%;">`;

                btn += `<a href="/Dashboard/Orders/Wallet/Details/Index/${row.id}" type="button"
                class="btn btn-info btn-sm mr-2" title="Visualizar detalles del pedido.">
                    <i class="fas fa-eye text-white"></i>
                </a>`;

                if (data === 'Pendiente') {
                    btn += `<a onclick="ApproveOrderWallet(${row.id})" type="button"
                    class="btn btn-success btn-sm mr-2" title="Aprobar pedido.">
                        <i class="fas fa-check-double text-white"></i>
                    </a>`;

                    btn += `<a onclick="PartiallyApproveOrderWallet(${row.id})" type="button"
                    class="btn btn-warning btn-sm mr-2" title="Aprobar parcialmente pedido.">
                        <i class="fas fa-check text-white"></i>
                    </a>`;

                    btn += `<a onclick="CancelOrderWallet(${row.id})" type="button"
                    class="btn btn-danger btn-sm mr-2" title="Cancelar pedido.">
                        <i class="fas fa-xmark text-white"></i>
                    </a>`;
                }

                if (data === 'Parcialmente Aprobado') {
                    btn += `<a onclick="ApproveOrderWallet(${row.id})" type="button"
                    class="btn btn-success btn-sm mr-2" title="Aprobar pedido.">
                        <i class="fas fa-check-double text-white"></i>
                    </a>`;

                    btn += `<a onclick="CancelOrderWallet(${row.id})" type="button"
                    class="btn btn-danger btn-sm mr-2" title="Cancelar pedido.">
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
            targets: [0, 1, 2, 8, 9, 10, 11, 12, 13]
        },
        {
            orderable: false,
            targets: [3, 4, 5, 6, 7, 14]
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
