function CreateSizeModal() {
    $.ajax({
        url: `/Dashboard/Sizes/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            CreateSizeModalCleaned();
            CreateSizeAjaxSuccess(response);
            $('#CreateSizeModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            CreateSizeAjaxError(xhr);
        }
    });
}

function CreateSizeModalCleaned() {
    RemoveIsValidClassCreateSize();
    RemoveIsInvalidClassCreateSize();

    $('#name_c').val('');
    $('#code_c').val('');
}

function CreateSize() {
    Swal.fire({
        title: '¿Desea guardar la talla?',
        text: 'La talla será creada.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Sizes/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                    'code': $('#code_c').val()
                },
                success: function (response) {
                    tableSizes.ajax.reload();
                    CreateSizeAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    CreateSizeAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La talla no fue creada.')
        }
    });
}

function CreateSizeAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#CreateSizeModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateSizeModal').modal('hide');
    }
}

function CreateSizeAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateSizeModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateSizeModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateSizeModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateSize();
        RemoveIsInvalidClassCreateSize();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateSize(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateSize();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateSizeModal').modal('hide');
    }
}

function AddIsValidClassCreateSize() {
    if (!$('#name_c').hasClass('is-invalid')) {
        $('#name_c').addClass('is-valid');
    }
    if (!$('#code_c').hasClass('is-invalid')) {
        $('#code_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateSize() {
    $('#name_c').removeClass('is-valid');
    $('#code_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateSize(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateSize() {
    $('#name_c').removeClass('is-invalid');
    $('#code_c').removeClass('is-invalid');
}
