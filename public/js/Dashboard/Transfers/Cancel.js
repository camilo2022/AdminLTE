function CancelTransferModal(id) {
    $('#to_observation_c').val('');
    $('#CancelTransferButton').attr('onclick', `CancelTransfer(${id})`);
    $('#CancelTransferModal').modal('show');
}

function CancelTransfer(id) {
    Swal.fire({
        title: '¿Desea cancelar la transferencia?',
        text: 'La transferencia será cancelada.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, cancelar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Transfers/Cancel`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id,
                    'to_observation': $('#to_observation_c').val()
                },
                success: function(response) {
                    tableTransfers.ajax.reload();
                    CancelTransferAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    CancelTransferAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La transferencia seleccionada no fue cancelada.')
        }
    });
}

function CancelTransferAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
        $('#CancelTransferModal').modal('hide');
    }
}

function CancelTransferAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CancelTransferModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CancelTransferModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CancelTransferModal').modal('hide');
    }

    if(xhr.status === 422){
        $.each(xhr.responseJSON.errors, function(field, messages) {
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CancelTransferModal').modal('hide');
    }
}
