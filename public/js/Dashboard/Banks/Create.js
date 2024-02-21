function CreateBankModal() {
    $.ajax({
        url: `/Dashboard/Banks/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            CreateBankModalCleaned();
            CreateBankAjaxSuccess(response);
            $('#CreateBankModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            CreateBankAjaxError(xhr);
        }
    });
}

function CreateBankModalCleaned() {
    RemoveIsValidClassCreateBank();
    RemoveIsInvalidClassCreateBank();

    $('#name_c').val('');
    $('#sector_code_c').val('');
    $('#entity_code_c').val('');
}

function CreateBank() {
    Swal.fire({
        title: '¿Desea guardar el banco?',
        text: 'El banco será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Banks/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                    'sector_code': $('#sector_code_c').val(),
                    'entity_code': $('#entity_code_c').val(),
                },
                success: function (response) {
                    tableBanks.ajax.reload();
                    CreateBankAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableBanks.ajax.reload();
                    CreateBankAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El banco no fue creado.')
        }
    });
}

function CreateBankAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#CreateBankModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateBankModal').modal('hide');
    }
}

function CreateBankAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateBankModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateBankModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateBankModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateBank();
        RemoveIsInvalidClassCreateBank();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateBank(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateBank();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateBankModal').modal('hide');
    }
}

function AddIsValidClassCreateBank() {
    if (!$('#name_c').hasClass('is-invalid')) {
        $('#name_c').addClass('is-valid');
    }
    if (!$('#sector_code_c').hasClass('is-invalid')) {
        $('#sector_code_c').addClass('is-valid');
    }
    if (!$('#entity_code_c').hasClass('is-invalid')) {
        $('#entity_code_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateBank() {
    $('#name_c').removeClass('is-valid');
    $('#sector_code_c').removeClass('is-valid');
    $('#entity_code_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateBank(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateBank() {
    $('#name_c').removeClass('is-invalid');
    $('#sector_code_c').removeClass('is-invalid');
    $('#entity_code_c').removeClass('is-invalid');
}
