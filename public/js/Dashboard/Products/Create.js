function CreateProductModal() {
    $.ajax({
        url: `/Dashboard/Products/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            CreateProductModalCleaned();
            CreateProductAjaxSuccess(response);
            $('#CreateProductModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            CreateProductAjaxError(xhr);
        }
    });
}

function CreateProductModalCleaned() {
    RemoveIsValidClassCreateProduct();
    RemoveIsInvalidClassCreateProduct();

    $('#name_c').val('');
    $('#code_c').val('');
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
        $(`#${input}_c`).removeClass('is-valid');
    }
    $(`#${input}_c`).addClass('is-invalid');
}

function RemoveIsInvalidClassCreateProduct() {
    $('#name_c').removeClass('is-invalid');
    $('#code_c').removeClass('is-invalid');
}
