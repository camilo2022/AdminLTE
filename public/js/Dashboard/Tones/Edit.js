function EditToneModal(id) {
    $.ajax({
        url: `/Dashboard/Tones/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            tableTones.ajax.reload();
            EditToneModalCleaned(response.data);
            EditToneAjaxSuccess(response);
            $('#EditToneModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tableTones.ajax.reload();
            EditToneAjaxError(xhr);
        }
    });
}

function EditToneModalCleaned(tone) {
    RemoveIsValidClassEditTone();
    RemoveIsInvalidClassEditTone();

    $('#EditToneButton').attr('onclick', `EditTone(${tone.id})`);

    $("#name_e").val(tone.name);
}

function EditTone(id) {
    Swal.fire({
        title: '¿Desea actualizar el tono?',
        text: 'El tono se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Tones/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_e').val(),
                },
                success: function (response) {
                    tableTones.ajax.reload();
                    EditToneAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableTones.ajax.reload();
                    EditToneAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El tono no fue actualizado.')
        }
    });
}

function EditToneAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#EditToneModal').modal('hide');
    }
    
    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditToneModal').modal('hide');
    }
}

function EditToneAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditToneModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditToneModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditToneModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditTone();
        RemoveIsInvalidClassEditTone();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditTone(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditTone();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditToneModal').modal('hide');
    }
}

function AddIsValidClassEditTone() {
    if (!$('#name_e').hasClass('is-invalid')) {
        $('#name_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditTone() {
    $('#name_e').removeClass('is-valid');
}

function AddIsInvalidClassEditTone(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditTone() {
    $('#name_e').removeClass('is-invalid');
}
