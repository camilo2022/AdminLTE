function CreateDocumentTypeModal() {
    $.ajax({
        url: `/Dashboard/DocumentTypes/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            CreateDocumentTypeModalCleaned();
            CreateDocumentTypeAjaxSuccess(response);
            $('#CreateDocumentTypeModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            CreateDocumentTypeAjaxError(xhr);
        }
    });
}

function CreateDocumentTypeModalCleaned() {
    RemoveIsValidClassCreateDocumentType();
    RemoveIsInvalidClassCreateDocumentType();

    $('#name_c').val('');
    $('#code_c').val('');
}

function CreateDocumentType() {
    Swal.fire({
        title: '¿Desea guardar el tipo de documento?',
        text: 'El tipo de documento será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/DocumentTypes/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                    'code': $('#code_c').val()
                },
                success: function (response) {
                    tableDocumentTypes.ajax.reload();
                    CreateDocumentTypeAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    CreateDocumentTypeAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El tipo de documento no fue creado.')
        }
    });
}

function CreateDocumentTypeAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#CreateDocumentTypeModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateDocumentTypeModal').modal('hide');
    }
}

function CreateDocumentTypeAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateDocumentTypeModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateDocumentTypeModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateDocumentTypeModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateDocumentType();
        RemoveIsInvalidClassCreateDocumentType();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateDocumentType(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateDocumentType();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateDocumentTypeModal').modal('hide');
    }
}

function AddIsValidClassCreateDocumentType() {
    if (!$('#name_c').hasClass('is-invalid')) {
        $('#name_c').addClass('is-valid');
    }
    if (!$('#code_c').hasClass('is-invalid')) {
        $('#code_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateDocumentType() {
    $('#name_c').removeClass('is-valid');
    $('#code_c').removeClass('is-valid');
    $('#value_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateDocumentType(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateDocumentType() {
    $('#name_c').removeClass('is-invalid');
    $('#code_c').removeClass('is-invalid');
}
