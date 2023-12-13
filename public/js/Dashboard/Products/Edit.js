function EditProductModal(id) {
    $.ajax({
        url: `/Dashboard/Products/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            tableProducts.ajax.reload();
            EditProductModalCleaned(response.data.product);
            EditProductsModalCorreria(response.data.correrias);
            EditProductsModalCollection(response.data.collections);
            EditProductsModalModel(response.data.models);
            EditProductsModalTrademark(response.data.trademarks);
            EditProductsModalClothingLine(response.data.clothing_lines);
            EditProductAjaxSuccess(response);
            $('#EditProductModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            tableProducts.ajax.reload();
            EditProductAjaxError(xhr);
        }
    });
}

function EditProductModalCleaned(product) {
    EditProductsModalResetSelect('collection_id_c');
    EditProductsModalResetSelect('correria_id_e');
    EditProductsModalResetSelect('model_id_e');
    EditProductsModalResetSelect('trademark_id_e');
    EditProductsModalResetSelect('clothing_line_id_e');
    RemoveIsValidClassEditProduct();
    RemoveIsInvalidClassEditProduct();

    $('#EditProductButton').attr('onclick', `EditProduct(${product.id})`);
    $('#EditProductButton').attr('data-id', product.id);
    $('#EditProductButton').attr('data-correria_id', product.correria_id);
    $('#EditProductButton').attr('data-collection_id', product.collection_id);
    $('#EditProductButton').attr('data-model_id', product.model_id);
    $('#EditProductButton').attr('data-trademark_id', product.trademark_id);
    $('#EditProductButton').attr('data-clothing_line_id', product.clothing_line_id);
    $('#EditProductButton').attr('data-category_id', product.category_id);
    $('#EditProductButton').attr('data-subcategory_id', product.subcategory_id);

    $('#code_e').val(product.code);
    $('#price_e').val(product.price);
    $('#cost_e').val(product.cost);
}

function EditProductsModalResetSelect(id) {
    const select = $(`#${id}`);
    select.html('');
    const defaultOption = $('<option>', {
        value: '',
        text: 'Seleccione'
    });
    select.append(defaultOption);
    select.trigger('change');
}

function EditProductsModalCollection(collections) {
    collections.forEach(collection => {
        let newOption = new Option(collection.name, collection.id, false, false);
        $('#collection_id_e').append(newOption);
    });
    let collection_id = $('#EditProductButton').attr('data-collection_id');
    if(collection_id != '') {
        $("#collection_id_e").val(collection_id).trigger('change');
        $('#EditProductButton').attr('data-collection_id', '');
    }
}

function EditProductsModalCorreria(correrias) {
    correrias.forEach(correria => {
        let newOption = new Option(correria.name, correria.id, false, false);
        $('#correria_id_e').append(newOption);
    });
    let correria_id = $('#EditProductButton').attr('data-correria_id');
    if(correria_id != '') {
        $("#correria_id_e").val(correria_id).trigger('change');
        $('#EditProductButton').attr('data-correria_id', '');
    }
}

function EditProductsModalModel(models) {
    models.forEach(model => {
        let newOption = new Option(model.name, model.id, false, false);
        $('#model_id_e').append(newOption);
    });
    let model_id = $('#EditProductButton').attr('data-model_id');
    if(model_id != '') {
        $("#model_id_e").val(model_id).trigger('change');
        $('#EditProductButton').attr('data-model_id', '');
    }
}

function EditProductsModalTrademark(trademarks) {
    trademarks.forEach(trademark => {
        let newOption = new Option(trademark.name, trademark.id, false, false);
        $('#trademark_id_e').append(newOption);
    });
    let trademark_id = $('#EditProductButton').attr('data-trademark_id');
    if(trademark_id != '') {
        $("#trademark_id_e").val(trademark_id).trigger('change');
        $('#EditProductButton').attr('data-trademark_id', '');
    }
}

function EditProductsModalClothingLine(clothing_lines) {
    clothing_lines.forEach(clothing_line => {
        let newOption = new Option(clothing_line.name, clothing_line.id, false, false);
        $('#clothing_line_id_e').append(newOption);
    });
    let clothing_line_id = $('#EditProductButton').attr('data-clothing_line_id');
    if(clothing_line_id != '') {
        $("#clothing_line_id_e").val(clothing_line_id).trigger('change');
        $('#EditProductButton').attr('data-clothing_line_id', '');
    }
}

function EditProductsModalClothingLineGetCategory(select) {
    if($(select).val() == '') {
        EditProductsModalResetSelect('category_id_e');
    } else {
        let id = $('#EditProductButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Products/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'clothing_line_id':  $(select).val()
            },
            success: function(response) {
                EditProductsModalResetSelect('category_id_e');
                EditProductsModalCategory(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditProductsAjaxError(xhr);
            }
        });
    }
};

function EditProductsModalCategory(categories) {
    categories.forEach(category => {
        let newOption = new Option(category.name, category.id, false, false);
        $('#category_id_e').append(newOption);
    });
    let category_id = $('#EditProductButton').attr('data-category_id');
    if(category_id != '') {
        $("#category_id_e").val(category_id).trigger('change');
        $('#EditProductButton').attr('data-category_id', '');
    }
}

function EditProductsModalCategoryGetSubcategory(select) {
    if($(select).val() == '') {
        EditProductsModalResetSelect('subcategory_id_e');
    } else {
        let id = $('#EditProductButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Products/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'category_id':  $(select).val()
            },
            success: function(response) {
                EditProductsModalResetSelect('subcategory_id_e');
                EditProductsModalSubcategory(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditProductsAjaxError(xhr);
            }
        });
    }
};

function EditProductsModalSubcategory(subcategories) {
    subcategories.forEach(subcategory => {
        let newOption = new Option(subcategory.name, subcategory.id, false, false);
        $('#subcategory_id_e').append(newOption);
    });
    let subcategory_id = $('#EditProductButton').attr('data-subcategory_id');
    if(subcategory_id != '') {
        $("#subcategory_id_e").val(subcategory_id).trigger('change');
        $('#EditProductButton').attr('data-subcategory_id', '');
    }
}

function EditProduct(id) {
    /* let files = $('#photos_e')[0].files;
    $.each(files, function(index, file) {
        formData.append('photos[]', file);
    }); */

    Swal.fire({
        title: '¿Desea actualizar el producto?',
        text: 'El producto se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Products/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'code': $('#code_e').val(),
                    'price': $('#price_e').val(),
                    'cost': $('#cost_e').val(),
                    'correria_id': $('#correria_id_e').val(),
                    'clothing_line_id': $('#clothing_line_id_e').val(),
                    'category_id': $('#category_id_e').val(),
                    'collection_id': $('#collection_id_e').val(),
                    'subcategory_id': $('#subcategory_id_e').val(),
                    'model_id': $('#model_id_e').val(),
                    'trademark_id': $('#trademark_id_e').val(),
                },
                success: function(response) {
                    tableProducts.ajax.reload();
                    EditProductAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableProducts.ajax.reload();
                    EditProductAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El producto no fue actualizado.');
        }
    });
}

function EditProductAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
        $('#EditProductModal').modal('hide');
    }
}

function EditProductAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditProductModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditProductModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditProductModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassEditProduct();
        RemoveIsInvalidClassEditProduct();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassEditProduct(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditProduct();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditProductModal').modal('hide');
    }
}

function AddIsValidClassEditProduct() {
    if (!$('#code_e').hasClass('is-invalid')) {
        $('#code_e').addClass('is-valid');
    }
    if (!$('#price_e').hasClass('is-invalid')) {
        $('#price_e').addClass('is-valid');
    }
    if (!$('#cost_e').hasClass('is-invalid')) {
        $('#cost_e').addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-clothing_line_id_e-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-clothing_line_id_e-container"]`).addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-category_id_e-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-category_id_e-container"]`).addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-subcategory_id_e-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-subcategory_id_e-container"]`).addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-model_id_e-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-model_id_e-container"]`).addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-trademark_id_e-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-trademark_id_e-container"]`).addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-correria_id_e-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-correria_id_e-container"]`).addClass('is-valid');
    }
}

function RemoveIsValidClassEditProduct() {
    $('#code_e').removeClass('is-valid');
    $('#price_e').removeClass('is-valid');
    $('#cost_e').removeClass('is-valid');
    $(`span[aria-labelledby="select2-clothing_line_id_e-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-category_id_e-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-subcategory_id_e-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-model_id_e-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-trademark_id_e-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-correria_id_e-container"]`).removeClass('is-valid');
}

function AddIsInvalidClassEditProduct(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_e-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_e-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditProduct() {
    $('#code_e').removeClass('is-invalid');
    $('#price_e').removeClass('is-invalid');
    $('#cost_e').removeClass('is-invalid');
    $(`span[aria-labelledby="select2-clothing_line_id_e-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-category_id_e-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-subcategory_id_e-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-model_id_e-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-trademark_id_e-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-correria_id_e-container"]`).removeClass('is-invalid');
}
