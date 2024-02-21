function EditBankModal(id) {
    $.ajax({
        url: `/Dashboard/Banks/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            tableBanks.ajax.reload();
            EditBankModalCleaned(response.data);
            EditBankAjaxSuccess(response);
            $('#EditBankModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tableBanks.ajax.reload();
            EditBankAjaxError(xhr);
        }
    });
}

function EditBankModalCleaned(tone) {
    RemoveIsValidClassEditBank();
    RemoveIsInvalidClassEditBank();

    $('#EditBankButton').attr('onclick', `EditBank(${tone.id})`);

    $("#name_e").val(tone.name);
    $('#sector_code_e').val(tone.sector_code);
    $('#entity_code_e').val(tone.entity_code);
}

function EditBank(id) {
    Swal.fire({
        title: '¿Desea actualizar el banco?',
        text: 'El banco se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Banks/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_e').val(),
                    'sector_code': $('#sector_code_e').val(),
                    'entity_code': $('#entity_code_e').val(),
                },
                success: function (response) {
                    tableBanks.ajax.reload();
                    EditBankAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableBanks.ajax.reload();
                    EditBankAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El banco no fue actualizado.')
        }
    });
}

function EditBankAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#EditBankModal').modal('hide');
    }
    
    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditBankModal').modal('hide');
    }
}

function EditBankAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditBankModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditBankModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditBankModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditBank();
        RemoveIsInvalidClassEditBank();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditBank(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditBank();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditBankModal').modal('hide');
    }
}

function AddIsValidClassEditBank() {
    if (!$('#name_e').hasClass('is-invalid')) {
        $('#name_e').addClass('is-valid');
    }
    if (!$('#sector_code_e').hasClass('is-invalid')) {
        $('#sector_code_e').addClass('is-valid');
    }
    if (!$('#entity_code_e').hasClass('is-invalid')) {
        $('#entity_code_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditBank() {
    $('#name_e').removeClass('is-valid');
    $('#sector_code_e').removeClass('is-valid');
    $('#entity_code_e').removeClass('is-valid');
}

function AddIsInvalidClassEditBank(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditBank() {
    $('#name_e').removeClass('is-invalid');
    $('#sector_code_e').removeClass('is-invalid');
    $('#entity_code_e').removeClass('is-invalid');
}
