let tableOrderInvoices = $('#orderInvoices').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: `/Dashboard/Orders/Invoice/Index/Query`,
        type: 'POST',
        data: function (request) {
            var columnMappings = {
                0: 'id',
                3: 'dispatch_user_id',
                4: 'consecutive',
                5: 'dispatch_status',
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
            return response.data.orderInvoices;
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
                return row.client.document_number;
            }
        },
        {
            data: 'client_branch_id',
            render: function (data, type, row) {
                return row.client_branch.code;
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
            data: 'dispatch_status',
            render: function (data, type, row) {
                switch (data) {
                    case 'Pendiente':
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                    case 'Rechazado':
                        return `<h5><span class="badge badge-pill badge-danger text-white"><i class="fas fa-ban mr-2 text-white"></i>Rechazado</span></h5>`;
                        break;
                    case 'Cancelado':
                        return `<h5><span class="badge badge-pill badge-warning text-white"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span></h5>`;
                        break;
                    case 'Aprobado':
                        return `<h5><span class="badge badge-pill badge-success"><i class="fas fa-check mr-2"></i>Aprobado</span></h5>`;
                        break;
                    case 'Empacado':
                        return `<h5><span class="badge badge-pill bg-gray" style="color:white !important;"><i class="fas fa-box mr-2 text-white"></i>Empacado</span></h5>`;
                        break;
                    case 'Despachado':
                        return `<h5><span class="badge badge-pill badge-primary"><i class="fas fa-share mr-2 text-white"></i>Despachado</span></h5>`;
                        break;
                    default:
                        return `<h5><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></h5>`;
                        break;
                };
            }
        },
        {
            data: 'correria_id',
            render: function (data, type, row) {
                return row.correria.code;
            }
        },
        {
            data: 'order_dispatches',
            render: function (data, type, row) {
                let btn = `<div class="text-center" style="width: 100%;">`;

                btn += `<a onclick="CreateOrderInvoiceModal(${row.id})" type="button"
                class="btn btn-primary btn-sm mr-2 text-white" title="Agregar facturas a la orden de despacho.">
                    <i class="fas fa-file-invoice-dollar"></i>
                </a>`;

                btn += `</div>`;
                return btn;
            }
        }
    ],
    columnDefs: [
        {
            orderable: true,
            targets: [0, 3, 4, 5]
        },
        {
            orderable: false,
            targets: [1, 2, 6]
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
