function CreateClientTypeModal() {
    $.ajax({
        url: `/Dashboard/ClientTypes/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            CreateClientTypeModalCleaned();
            CreateClientTypeAjaxSuccess(response);
            $('#CreateClientTypeModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            CreateClientTypeAjaxError(xhr);
        }
    });
}

function CreateClientTypeModalCleaned() {
    RemoveIsValidClassCreateClientType();
    RemoveIsInvalidClassCreateClientType();

    $('#name_c').val('');
    $('#code_c').val('');
}

function CreateClientType() {
    Swal.fire({
        title: '¿Desea guardar el tipo de cliente?',
        text: 'El tipo de cliente será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/ClientTypes/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                    'code': $('#code_c').val()
                },
                success: function (response) {
                    tableClientTypes.ajax.reload();
                    CreateClientTypeAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableClientTypes.ajax.reload();
                    CreateClientTypeAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El tipo de cliente no fue creado.')
        }
    });
}

function CreateClientTypeAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#CreateClientTypeModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateClientTypeModal').modal('hide');
    }
}

function CreateClientTypeAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateClientTypeModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateClientTypeModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateClientTypeModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateClientType();
        RemoveIsInvalidClassCreateClientType();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateClientType(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateClientType();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateClientTypeModal').modal('hide');
    }
}

function AddIsValidClassCreateClientType() {
    if (!$('#name_c').hasClass('is-invalid')) {
        $('#name_c').addClass('is-valid');
    }
    if (!$('#code_c').hasClass('is-invalid')) {
        $('#code_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateClientType() {
    $('#name_c').removeClass('is-valid');
    $('#code_c').removeClass('is-valid');
    $('#value_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateClientType(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateClientType() {
    $('#name_c').removeClass('is-invalid');
    $('#code_c').removeClass('is-invalid');
}
