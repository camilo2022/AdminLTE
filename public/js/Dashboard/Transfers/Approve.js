function ApproveTransferModal(id) {
    $('#to_observation_a').val('');
    $('#ApproveTransferButton').attr('onclick', `ApproveTransfer(${id})`);
    $('#ApproveTransferModal').modal('show');
}

function ApproveTransfer(id) {
    Swal.fire({
        title: '¿Desea aprobar la transferencia?',
        text: 'La transferencia será aprobada.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, aprobar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Transfers/Approve`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id,
                    'to_observation': $('#to_observation_a').val()
                },
                success: function(response) {
                    tableTransfers.ajax.reload();
                    ApproveTransferAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    ApproveTransferAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La transferencia seleccionada no fue aprobada.')
        }
    });
}

function ApproveTransferAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
        $('#ApproveTransferModal').modal('hide');
    }
}

function ApproveTransferAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ApproveTransferModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ApproveTransferModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ApproveTransferModal').modal('hide');
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
        $('#ApproveTransferModal').modal('hide');
    }
}
