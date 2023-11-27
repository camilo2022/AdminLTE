function CreateProductModal() {
    $.ajax({
        url: `/Dashboard/Products/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            CreateProductModalCleaned();
            CreateProductsModalCollection(response.data.collections);
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
    CreateProductsModalResetSelect('collection_id_c');
    CreateProductsModalResetSelect('model_id_c');
    CreateProductsModalResetSelect('trademark_id_c');
    CreateProductsModalResetSelect('clothing_line_id_c');
    RemoveIsValidClassCreateProduct();
    RemoveIsInvalidClassCreateProduct();

    $('#code_c').val('');
    $('#price_c').val('');
    $('#description_c').val('');
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
            <label for="${size.code}" class="mt-3 ml-3">`;

        $.each(size.code.replace(/\s/g, "").split(""), function(index, letra) {
            checkSize += `<i class="fas fa-${letra}"></i>`;
        });
            
        checkSize += `</label></div>`;
        $('#sizes_c').append(checkSize);
    });
}

function CreateProductsModalColors(colors) {
    $('#colors_c').empty();
    $.each(colors, function(index, color) {
        let checkColor = `<div class="row pl-2 icheck-primary">
            <input type="checkbox" id="${color.id}" data-id="${color.id}">
            <label for="${color.id}" class="mt-3 ml-3">`;

        $.each(color.name.replace(/\s/g, "").split(""), function(index, letra) {
            checkColor += `<i class="fas fa-${letra}"></i>`;
        });
            
        checkColor += `</label></div>`;
        $('#colors_c').append(checkColor);
    });
}

function CreateProductsModalCollection(collections) {
    collections.forEach(collection => {
        let newOption = new Option(collection.name, collection.id, false, false);
        $('#collection_id_c').append(newOption);
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
                    'name': $('#name_c').val(),
                    'code': $('#code_c').val(),
                    'start_date': $('#start_date_c').val(),
                    'end_date': $('#end_date_c').val()
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
    if (!$('#name_c').hasClass('is-invalid')) {
      $('#name_c').addClass('is-valid');
    }
    if (!$('#code_c').hasClass('is-invalid')) {
      $('#code_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateProduct() {
    $('#name_c').removeClass('is-valid');
    $('#code_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateProduct(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
    if (!$(`#span[aria-labelledby="select2-${input}_c-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_c-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateProduct() {
    $('#name_c').removeClass('is-invalid');
    $('#code_c').removeClass('is-invalid');
}
