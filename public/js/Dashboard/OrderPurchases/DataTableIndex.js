let tableOrderPurchases = $('#orderPurchases').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: `/Dashboard/Orders/Purchase/Index/Query`,
        type: 'POST',
        data: function (request) {
            var columnMappings = {
                0: 'id',
                1: 'workshop_id',
                2: 'client_branch_id',
                8: 'Purchase_date',
                9: 'Purchase_user_id',
                10: 'Purchase_status',
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
            return response.data.orderPurchases;
        },
        error: function (xhr, error, thrown) {
            toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        }
    },
    columns: [
        {
            data: null,
            render: function (data, type, row) {
                let btn = '';
                if(row.payments.length > 0 || row.invoices.length > 0) {
                    btn += '<button class="btn btn-sm btn-success dt-expand rounded-circle"><i class="fas fa-plus"></i</button>';
                }
                return btn;
            },
        },
        { data: 'id' },
        {
            data: 'workshop_id',
            render: function (data, type, row) {
                return row.workshop.document_number;
            }
        },
        {
            data: 'workshop_id',
            render: function (data, type, row) {
                return row.workshop.name;
            }
        },
        {
            data: 'workshop_id',
            render: function (data, type, row) {
                return row.workshop.country.name;
            }
        },
        {
            data: 'workshop_id',
            render: function (data, type, row) {
                return row.workshop.departament.name;
            }
        },
        {
            data: 'workshop_id',
            render: function (data, type, row) {
                return row.workshop.city.name;
            }
        },
        {
            data: 'workshop_id',
            render: function (data, type, row) {
                return row.workshop.address;
            }
        },
        {
            data: 'workshop_id' ,
            render: function (data, type, row) {
                return row.workshop.neighborhood;
            }
        },
        { data: 'purchase_date' },
        {
            data: 'purchase_user_id' ,
            render: function (data, type, row) {
                return `${row.purchase_user.name} ${row.purchase_user.last_name}`;
            }
        },
        {
            data: 'purchase_status',
            render: function (data, type, row) {
                switch (data) {
                    case 'Cancelado':
                        return `<h5><span class="badge badge-pill badge-danger text-white"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span></h5>`;
                        break;
                    case 'Pendiente':
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                    case 'Aprobado':
                        return `<h5><span class="badge badge-pill badge-success"><i class="fas fa-check-double mr-2"></i>Aprobado</span></h5>`;
                        break;
                    case 'Parcialmente Recibido':
                        return `<h5><span class="badge badge-pill badge-warning text-white"><i class="fas fa-reply mr-2"></i>Parcialmente Recibido</span></h5>`;
                        break;
                    case 'Recibido':
                        return `<h5><span class="badge badge-pill badge-primary text-white"><i class="fas fa-reply-all mr-2"></i>Recibido</span></h5>`;
                        break;
                    default:
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                }
            }
        },
        { data: 'purchase_date' },
        { data: 'purchase_observation' },
        {
            data: 'payment_status',
            render: function (data, type, row) {
                switch (order_dispatch.payment_status) {
                    case 'Pendiente de Pago':
                        return `<h5><span class="badge badge-pill badge-warning text-white"><i class="fas fa-circle-exclamation mr-2 text-white"></i>Pendiente de Pago</span></h5>`;
                        break;
                    case 'Parcialmente Pagado':
                        return `<h5><span class="badge badge-pill badge-primary text-white"><i class="fas fa-circle-dollar mr-2 text-white"></i>Parcialmente Pagado</span></h5>`;
                        break;
                    case 'Pagado':
                        return `<h5><span class="badge badge-pill badge-success text-white"><i class="fas fa-circle-xmark mr-2 text-white"></i>Cancelado</span></h5>`;
                        break;
                    case 'Cancelado':
                        return `<h5><span class="badge badge-pill badge-danger"><i class="fas fa-circle-check mr-2"></i>Aprobado</span></h5>`;
                        break;
                    default:
                        return `<h5><span class="badge badge-pill badge-warning text-white"><i class="fas fa-circle-exclamation mr-2 text-white"></i>Pendiente de Pago</span></h5>`;
                        break;
                };
            }
        },
        {
            data: 'purchase_status',
            render: function (data, type, row) {
                let btn = `<div class="text-center" style="width: 100%;">`;

                btn += `<a href="/Dashboard/Orders/Purchases/Details/Index/${row.id}" type="button"
                class="btn btn-info btn-sm mr-2" title="Visualizar detalles de la orden de compra.">
                    <i class="fas fa-eye text-white"></i>
                </a>`;

                if (data === 'Pendiente') {
                    btn += `<a onclick="EditOrderPurchaseModal(${row.id})" type="button"
                    class="btn btn-primary btn-sm mr-2" title="Editar orden de compra.">
                        <i class="fas fa-pen text-white"></i>
                    </a>`;

                    btn += `<a onclick="ApproveOrderPurchase(${row.id})" type="button"
                    class="btn btn-success btn-sm mr-2" title="Aprobar orden de compra.">
                        <i class="fas fa-check text-white"></i>
                    </a>`;

                    btn += `<a onclick="CancelOrderPurchase(${row.id})" type="button"
                    class="btn btn-danger btn-sm mr-2" title="Cancelar orden de compra.">
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
