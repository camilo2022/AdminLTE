function CreateClothingLineModal() {
    $.ajax({
        url: `/Dashboard/ClothingLines/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            CreateClothingLineModalCleaned();
            CreateClothingLineAjaxSuccess(response);
            $('#CreateClothingLineModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            CreateClothingLineAjaxError(xhr);
        }
    });
}

function CreateClothingLineModalCleaned() {
    RemoveIsValidClassCreateClothingLine();
    RemoveIsInvalidClassCreateClothingLine();

    $('#name_c').val('');
    $('#code_c').val('');
    $('#description_c').val('');
}

function CreateClothingLine() {
    Swal.fire({
        title: '¿Desea guardar la linea de producto?',
        text: 'La linea de producto será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/ClothingLines/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                    'code': $('#code_c').val(),
                    'description': $('#description_c').val()
                },
                success: function (response) {
                    tableClothingLines.ajax.reload();
                    CreateClothingLineAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableClothingLines.ajax.reload();
                    CreateClothingLineAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La linea de producto no fue creado.')
        }
    });
}

function CreateClothingLineAjaxSuccess(response) {
    if (response.status === 200) {
        toastr.info(response.message);
        $('#CreateClothingLineModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateClothingLineModal').modal('hide');
    }
}

function CreateClothingLineAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateClothingLineModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateClothingLineModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateClothingLineModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateClothingLine();
        RemoveIsInvalidClassCreateClothingLine();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateClothingLine(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateClothingLine();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateClothingLineModal').modal('hide');
    }
}

function AddIsValidClassCreateClothingLine() {
    if (!$('#name_c').hasClass('is-invalid')) {
        $('#name_c').addClass('is-valid');
    }
    if (!$('#code_c').hasClass('is-invalid')) {
        $('#code_c').addClass('is-valid');
    }
    if (!$('#description_c').hasClass('is-invalid')) {
        $('#description_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateClothingLine() {
    $('#name_c').removeClass('is-valid');
    $('#code_c').removeClass('is-valid');
    $('#description_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateClothingLine(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateClothingLine() {
    $('#name_c').removeClass('is-invalid');
    $('#code_c').removeClass('is-invalid');
    $('#description_c').removeClass('is-valid');
}
