let tableProducts = $('#products').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: `/Dashboard/Products/Index/Query`,
        type: 'POST',
        data: function (request) {
            var columnMappings = {
                0: 'id',
                1: 'code',
                2: 'cost',
                3: 'price',
                4: 'clothing_line_id',
                5: 'category_id',
                6: 'subcategory_id',
                7: 'model_id',
                8: 'trademark_id',
                9: 'correria_id',
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
            return response.data.products;
        },
        error: function (xhr, error, thrown) {
            toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        }
    },
    columns: [
        { data: 'id' },
        { data: 'code' },
        {
            data: 'cost',
            render: function (data, type, row) {
                return `${parseFloat(data).toLocaleString('es-CO', { style: 'currency', currency: 'COP' })}`;
            }
        },
        {
            data: 'price',
            render: function (data, type, row) {
                return `${parseFloat(data).toLocaleString('es-CO', { style: 'currency', currency: 'COP' })}`;
            }
        },
        {
            data: 'clothing_line',
            render: function (data, type, row) {
                return data.name;
            }
        },
        {
            data: 'category',
            render: function (data, type, row) {
                return data.name;
            }
        },
        {
            data: 'subcategory',
            render: function (data, type, row) {
                return data.name;
            }
        },
        {
            data: 'model',
            render: function (data, type, row) {
                return data.name;
            }
        },
        {
            data: 'trademark',
            render: function (data, type, row) {
                return data.name;
            }
        },
        {
            data: 'correria',
            render: function (data, type, row) {
                return data.name;
            }
        },
        {
            data: 'colors_tones',
            render: function(data, type, row) {
                let div = `<div>`;
                $.each(data, function(index, color_tone) {
                    div += `<span class="badge mr-1">${color_tone.color.name} - ${color_tone.color.code} | ${color_tone.tone.name}</span>`;
                });
                div += `</div>`;

                return div;
            }
        },
        {
            data: 'sizes',
            render: function(data, type, row) {
                let div = `<div style="width: 150px;">`;
                $.each(data, function(index, size) {
                    div += `<span class="badge badge-info mr-1">${size.code}</span>`;
                });
                div += `</div>`;

                return div;
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
                let btn = `<div class="text-center" style="width: 150px;">`;
                if (data === null) {
                    btn += `<a onclick="ShowProductModal(${row.id})" type="button"
                    class="btn btn-info btn-sm mr-2" title="Visualizar producto">
                        <i class="fas fa-eye text-white"></i>
                    </a>`;

                    btn += `<a onclick="EditProductModal(${row.id})" type="button"
                    class="btn btn-primary btn-sm mr-2" title="Editar producto">
                        <i class="fas fa-pen text-white"></i>
                    </a>`;

                    btn += `<a onclick="DeleteProduct(${row.id})" type="button"
                    class="btn btn-danger btn-sm mr-2" title="Eliminar producto">
                        <i class="fas fa-trash text-white"></i>
                    </a>`;
                } else {
                    btn += `<a onclick="RestoreProduct(${row.id})" type="button"
                    class="btn btn-info btn-sm mr-2"title="Restaurar producto">
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
            targets: 0,
            orderable: true,
            targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 12]
        },
        {
            orderable: false,
            targets: [10, 11, 13]
        },
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
