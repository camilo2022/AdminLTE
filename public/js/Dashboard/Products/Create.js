function CreateProductModal() {
    $.ajax({
        url: `/Dashboard/Products/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            tableProducts.ajax.reload();
            CreateProductModalCleaned();
            CreateProductModalCorreria(response.data.correrias);
            CreateProductModalCollection(response.data.collections);
            CreateProductModalModel(response.data.models);
            CreateProductModalTrademark(response.data.trademarks);
            CreateProductModalClothingLine(response.data.clothing_lines);
            CreateProductAjaxSuccess(response);
            $('#CreateProductModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            tableProducts.ajax.reload();
            CreateProductAjaxError(xhr);
        }
    });
}

function CreateProductModalCleaned() {
    CreateProductModalResetSelect('collection_id_c');
    CreateProductModalResetSelect('correria_id_c');
    CreateProductModalResetSelect('model_id_c');
    CreateProductModalResetSelect('trademark_id_c');
    CreateProductModalResetSelect('clothing_line_id_c');
    RemoveIsValidClassCreateProduct();
    RemoveIsInvalidClassCreateProduct();

    $('#code_c').val('');
    $('#price_c').val('');
    $('#cost_c').val('');
    /* $('#photos_c').val('');
    $('#photos_c').dropify().data('dropify').destroy();
    $('#photos_c').dropify().data('dropify').init(); */
}

function CreateProductModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function CreateProductModalCollection(collections) {
    collections.forEach(collection => {
        $('#collection_id_c').append(new Option(collection.name, collection.id, false, false));
    });
}

function CreateProductModalCorreria(correrias) {
    correrias.forEach(correria => {
        $('#correria_id_c').append(new Option(correria.name, correria.id, false, false));
    });
}

function CreateProductModalModel(models) {
    models.forEach(model => {
        $('#model_id_c').append(new Option(model.name, model.id, false, false));
    });
}

function CreateProductModalTrademark(trademarks) {
    trademarks.forEach(trademark => {
        $('#trademark_id_c').append(new Option(trademark.name, trademark.id, false, false));
    });
}

function CreateProductModalClothingLine(clothing_lines) {
    clothing_lines.forEach(clothing_line => {
        $('#clothing_line_id_c').append(new Option(clothing_line.name, clothing_line.id, false, false));
    });
}

function CreateProductModalClothingLineGetCategory(select) {
    if($(select).val() == '') {
        CreateProductModalResetSelect('category_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Products/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'clothing_line_id':  $(select).val()
            },
            success: function(response) {
                CreateProductModalResetSelect('category_id_c');
                CreateProductModalCategory(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateProductAjaxError(xhr);
            }
        });
    }
};

function CreateProductModalCategory(categories) {
    categories.forEach(category => {
        $('#category_id_c').append(new Option(category.name, category.id, false, false));
    });
}

function CreateProductModalCategoryGetSubcategory(select) {
    if($(select).val() == '') {
        CreateProductModalResetSelect('subcategory_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Products/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'category_id':  $(select).val()
            },
            success: function(response) {
                CreateProductModalResetSelect('subcategory_id_c');
                CreateProductModalSubcategory(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateProductAjaxError(xhr);
            }
        });
    }
};

function CreateProductModalSubcategory(subcategories) {
    subcategories.forEach(subcategory => {
        $('#subcategory_id_c').append(new Option(subcategory.name, subcategory.id, false, false));
    });
}

function CreateProduct() {
    /* let files = $('#photos_c')[0].files;
    for (var i = 0; i < files.length; i++) {
        formData.append('photos[]', files[i]);
    } */

    Swal.fire({
        title: '¿Desea guardar el producto?',
        text: 'El producto será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Products/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'code': $('#code_c').val(),
                    'price': $('#price_c').val(),
                    'cost': $('#cost_c').val(),
                    'description': $('#cost_c').val(),
                    'correria_id': $('#correria_id_c').val(),
                    'clothing_line_id': $('#clothing_line_id_c').val(),
                    'category_id': $('#category_id_c').val(),
                    'collection_id': $('#collection_id_c').val(),
                    'subcategory_id': $('#subcategory_id_c').val(),
                    'model_id': $('#model_id_c').val(),
                    'trademark_id': $('#trademark_id_c').val(),
                },
                success: function(response) {
                    tableProducts.ajax.reload();
                    CreateProductAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableProducts.ajax.reload();
                    CreateProductAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El producto no fue creado.')
        }
    });
}

function CreateProductAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.info(response.message);
        $('#CreateProductModal').modal('hide');
    }

    if(response.status === 201) {
        toastr.success(response.message);
        $('#CreateProductModal').modal('hide');
    }
}

function CreateProductAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateProductModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateProductModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateProductModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassCreateProduct();
        RemoveIsInvalidClassCreateProduct();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassCreateProduct(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateProduct();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateProductModal').modal('hide');
    }
}

function AddIsValidClassCreateProduct() {
    if (!$('#code_c').hasClass('is-invalid')) {
        $('#code_c').addClass('is-valid');
    }
    if (!$('#price_c').hasClass('is-invalid')) {
        $('#price_c').addClass('is-valid');
    }
    if (!$('#cost_c').hasClass('is-invalid')) {
        $('#cost_c').addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-clothing_line_id_c-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-clothing_line_id_c-container"]`).addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-category_id_c-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-category_id_c-container"]`).addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-subcategory_id_c-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-subcategory_id_c-container"]`).addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-model_id_c-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-model_id_c-container"]`).addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-trademark_id_c-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-trademark_id_c-container"]`).addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-correria_id_c-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-correria_id_c-container"]`).addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-collection_id_c-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-collection_id_c-container"]`).addClass('is-valid');
    }
}

function RemoveIsValidClassCreateProduct() {
    $('#code_c').removeClass('is-valid');
    $('#price_c').removeClass('is-valid');
    $('#cost_c').removeClass('is-valid');
    $(`span[aria-labelledby="select2-clothing_line_id_c-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-category_id_c-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-subcategory_id_c-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-model_id_c-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-trademark_id_c-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-correria_id_c-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-collection_id_c-container"]`).removeClass('is-valid');
}

function AddIsInvalidClassCreateProduct(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_c-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_c-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateProduct() {
    $('#code_c').removeClass('is-invalid');
    $('#price_c').removeClass('is-invalid');
    $('#cost_c').removeClass('is-invalid');
    $(`span[aria-labelledby="select2-clothing_line_id_c-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-category_id_c-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-subcategory_id_c-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-model_id_c-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-trademark_id_c-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-correria_id_c-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-collection_id_c-container"]`).removeClass('is-invalid');
}
