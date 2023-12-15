let tableCategoriesAndSubcategories = $('#categoriesAndSubcategories').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: `/Dashboard/CategoriesAndSubcategories/Index/Query`,
        type: 'POST',
        data: function (request) {
            var columnMappings = {
                0: 'id',
                1: 'clothing_line_id',
                2: 'name',
                3: 'icon',
                4: 'description',
                6: 'deleted_at',
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
            return response.data.categories;
        },
        error: function (xhr, error, thrown) {
            toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        }
    },
    columns: [
        { data: 'id' },
        { data: 'clothingLine',
            render: function (data, type, row) {
                return data.name;
            }
        },
        { data: 'name' },
        { data: 'code' },
        { data: 'description' },
        {
            data: 'subcategories',
            render: function(data, type, row) {
                var table = `<table border="1" class="w-100">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Subcategoría</th>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>`;

                $.each(data, function(index, subcategory) {
                    table += `<tr>
                                    <td>${subcategory.id}</td>
                                    <td>${subcategory.name}</td>
                                    <td>${subcategory.code}</td>
                                    <td>${subcategory.description}</td>
                                    <td>${subcategory.deleted_at === null ?
                                        '<span class="badge badge-success"><i class="fas fa-check mr-2"></i>Activa</span>' :
                                        '<span class="badge badge-danger"><i class="fas fa-xmark mr-2"></i>Inactiva</span>'}</td>
                                </tr>`;
                });

                table += `</tbody></table>`;

                return data.length > 0 ? table : '';
            }
        },
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
            data: 'deleted_at',
            render: function (data, type, row) {
                let btn = `<div class="text-center" style="width: 100%;">`;
                if (data === null) {
                    btn += `<a onclick="EditCategoryAndSubcategoriesModal(${row.id})" type="button"
                    class="btn btn-primary btn-sm mr-2" title="Editar categoria y subcategorias">
                        <i class="fas fa-pen text-white"></i>
                    </a>`;

                    btn += `<a onclick="DeleteCategoryAndSubcategories(${row.id})" type="button"
                    class="btn btn-danger btn-sm mr-2" title="Eliminar categoria y subcategorias">
                        <i class="fas fa-trash text-white"></i>
                    </a>`;
                } else {
                    btn += `<a onclick="RestoreCategoryAndSubcategories(${row.id})" type="button"
                    class="btn btn-info btn-sm mr-2"title="Restaurar categoria y subcategorias">
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
            targets: [0, 1, 2, 3, 4, 6]
        },
        {
            orderable: false,
            targets: [5, 7]
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
