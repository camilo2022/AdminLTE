function CreateTransferModal() {
    $.ajax({
        url: `/Dashboard/Transfers/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            CreateTransferModalCleaned();
            CreateTransferAjaxSuccess(response);
            $('#CreateTransferModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            CreateTransferAjaxError(xhr);
        }
    });
}

function CreateTransferModalCleaned() {
    RemoveIsValidClassCreateTransfer();
    RemoveIsInvalidClassCreateTransfer();

    $('#from_observation_c').val('');
}

function CreateTransfer() {
    Swal.fire({
        title: '¿Desea guardar la transferencia?',
        text: 'La transferencia será creada.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Transfers/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'from_observation': $('#from_observation_c').val()
                },
                success: function (response) {
                    tableTransfers.ajax.reload();
                    CreateTransferAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableTransfers.ajax.reload();
                    CreateTransferAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La transferencia no fue creada.')
        }
    });
}

function CreateTransferAjaxSuccess(response) {
    if (response.status === 200) {
        toastr.info(response.message);
        $('#CreateTransferModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateTransferModal').modal('hide');
    }
}

function CreateTransferAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateTransferModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateTransferModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateTransferModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateTransfer();
        RemoveIsInvalidClassCreateTransfer();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateTransfer(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateTransfer();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateTransferModal').modal('hide');
    }
}

function AddIsValidClassCreateTransfer() {
    if (!$('#name_c').hasClass('is-invalid')) {
        $('#name_c').addClass('is-valid');
    }
    if (!$('#code_c').hasClass('is-invalid')) {
        $('#code_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateTransfer() {
    $('#name_c').removeClass('is-valid');
    $('#code_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateTransfer(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateTransfer() {
    $('#name_c').removeClass('is-invalid');
    $('#code_c').removeClass('is-invalid');
}
