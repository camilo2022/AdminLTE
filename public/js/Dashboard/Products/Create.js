function CreateProductModal() {
    $.ajax({
        url: `/Dashboard/Products/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            CreateProductModalCleaned();
            CreateProductsModalCorreria(response.data.correrias);
            CreateProductsModalModel(response.data.models);
            CreateProductsModalTrademark(response.data.trademarks);
            CreateProductsModalClothingLine(response.data.clothing_lines);
            CreateProductsModalSizes(response.data.sizes);
            CreateProductsModalColors(response.data.colors);
            CreateProductAjaxSuccess(response);
            $('#CreateProductModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            CreateProductAjaxError(xhr);
        }
    });
}

function CreateProductModalCleaned() {
    CreateProductsModalResetSelect('correria_id_c');
    CreateProductsModalResetSelect('model_id_c');
    CreateProductsModalResetSelect('trademark_id_c');
    CreateProductsModalResetSelect('clothing_line_id_c');
    RemoveIsValidClassCreateProduct();
    RemoveIsInvalidClassCreateProduct();

    $('#code_c').val('');
    $('#price_c').val('');
    $('#description_c').val('');
    $('#photos_c').val('');
    $('#photos_c').dropify().data('dropify').destroy();
    $('#photos_c').dropify().data('dropify').init();
}

function CreateProductsModalResetSelect(id) {
    const select = $(`#${id}`);
    select.html('');
    const defaultOption = $('<option>', {
        value: '',
        text: 'Seleccione'
    });
    select.append(defaultOption);
    select.trigger('change');
}

function CreateProductsModalSizes(sizes) {
    $('#sizes_c').empty();
    $.each(sizes, function(index, size) {
        let checkSize = `<div class="row pl-2 icheck-primary">
            <input type="checkbox" id="${size.code}" data-id="${size.id}">
            <label for="${size.code}" class="mt-3 ml-3">${size.code}</label></div>`;
        $('#sizes_c').append(checkSize);
    });
}

function CreateProductsModalColors(colors) {
    $('#colors_c').empty();
    $.each(colors, function(index, color) {
        let checkColor = `<div class="row pl-2 icheck-primary">
            <input type="checkbox" id="${color.value}" data-id="${color.id}">
            <label for="${color.value}" class="mt-3 ml-3">${color.name}</label></div>`;
        $('#colors_c').append(checkColor);
    });
}

function CreateProductsModalCorreria(correrias) {
    correrias.forEach(correria => {
        let newOption = new Option(correria.name, correria.id, false, false);
        $('#correria_id_c').append(newOption);
    });
}

function CreateProductsModalModel(models) {
    models.forEach(model => {
        let newOption = new Option(model.name, model.id, false, false);
        $('#model_id_c').append(newOption);
    });
}

function CreateProductsModalTrademark(trademarks) {
    trademarks.forEach(trademark => {
        let newOption = new Option(trademark.name, trademark.id, false, false);
        $('#trademark_id_c').append(newOption);
    });
}

function CreateProductsModalClothingLine(clothing_lines) {
    clothing_lines.forEach(clothing_line => {
        let newOption = new Option(clothing_line.name, clothing_line.id, false, false);
        $('#clothing_line_id_c').append(newOption);
    });
}

function CreateProductsModalClothingLineGetCategory(select) {
    if($(select).val() == '') {
        CreateProductsModalResetSelect('category_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Products/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'clothing_line_id':  $(select).val()
            },
            success: function(response) {
                CreateProductsModalResetSelect('category_id_c');
                CreateProductsModalCategory(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateProductsAjaxError(xhr);
            }
        });
    }
};

function CreateProductsModalCategory(categories) {
    categories.forEach(category => {
        let newOption = new Option(category.name, category.id, false, false);
        $('#category_id_c').append(newOption);
    });
}

function CreateProductsModalCategoryGetSubcategory(select) {
    if($(select).val() == '') {
        CreateProductsModalResetSelect('subcategory_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Products/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'category_id':  $(select).val()
            },
            success: function(response) {
                CreateProductsModalResetSelect('subcategory_id_c');
                CreateProductsModalSubcategory(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateProductsAjaxError(xhr);
            }
        });
    }
};

function CreateProductsModalSubcategory(subcategories) {
    subcategories.forEach(subcategory => {
        let newOption = new Option(subcategory.name, subcategory.id, false, false);
        $('#subcategory_id_c').append(newOption);
    });
}

function CreateProduct() {
    let formData = new FormData();
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('code', $('#code_c').val());
    formData.append('price', $('#price_c').val());
    formData.append('description', $('#description_c').val());
    formData.append('correria_id', $('#correria_id_c').val());
    formData.append('clothing_line_id', $('#clothing_line_id_c').val());
    formData.append('category_id', $('#category_id_c').val());
    formData.append('subcategory_id', $('#subcategory_id_c').val());
    formData.append('model_id', $('#model_id_c').val());
    formData.append('trademark_id', $('#trademark_id_c').val());
    let sizes = $('#sizes_c input[type="checkbox"]:checked').map(function() {
        return $(this).data('id');
    }).get();
    let colors = $('#colors_c input[type="checkbox"]:checked').map(function() {
        return $(this).data('id');
    }).get();
    formData.append('sizes', JSON.stringify(sizes));
    formData.append('colors', JSON.stringify(colors));
    let files = $('#photos_c')[0].files;
    for (var i = 0; i < files.length; i++) {
        formData.append('photos[]', files[i]);
    }

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
                data: formData,
                processData: false,
                contentType: false,
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
    if(response.status === 200) {
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
    if (!$('#description_c').hasClass('is-invalid')) {
        $('#description_c').addClass('is-valid');
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
}

function RemoveIsValidClassCreateProduct() {
    $('#code_c').removeClass('is-valid');
    $('#price_c').removeClass('is-valid');
    $('#description_c').removeClass('is-valid');
    $(`span[aria-labelledby="select2-clothing_line_id_c-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-category_id_c-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-subcategory_id_c-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-model_id_c-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-trademark_id_c-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-correria_id_c-container"]`).removeClass('is-valid');
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
    $('#description_c').removeClass('is-invalid');
    $(`span[aria-labelledby="select2-clothing_line_id_c-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-category_id_c-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-subcategory_id_c-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-model_id_c-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-trademark_id_c-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-correria_id_c-container"]`).removeClass('is-invalid');
}
