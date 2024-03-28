function CreateToneModal() {
    $.ajax({
        url: `/Dashboard/Tones/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            CreateToneModalCleaned();
            CreateToneAjaxSuccess(response);
            $('#CreateToneModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            CreateToneAjaxError(xhr);
        }
    });
}

function CreateToneModalCleaned() {
    RemoveIsValidClassCreateTone();
    RemoveIsInvalidClassCreateTone();

    $('#name_c').val('');
}

function CreateTone() {
    Swal.fire({
        title: '¿Desea guardar el tono?',
        text: 'El tono será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Tones/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                },
                success: function (response) {
                    tableTones.ajax.reload();
                    CreateToneAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    CreateToneAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El tono no fue creado.')
        }
    });
}

function CreateToneAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#CreateToneModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateToneModal').modal('hide');
    }
}

function CreateToneAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateToneModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateToneModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateToneModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateTone();
        RemoveIsInvalidClassCreateTone();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateTone(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateTone();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateToneModal').modal('hide');
    }
}

function AddIsValidClassCreateTone() {
    if (!$('#name_c').hasClass('is-invalid')) {
        $('#name_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateTone() {
    $('#name_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateTone(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateTone() {
    $('#name_c').removeClass('is-invalid');
}
