function CancelTransferDetail(id) {
    Swal.fire({
        title: '¿Desea cancelar el detalle de la transferencia?',
        text: 'El detalle de la transferencia será cancelada.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, cancelar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Transfers/Details/Cancel`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id
                },
                success: function(response) {
                    tableTransferDetails.ajax.reload();
                    CancelTransferDetailAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableTransferDetails.ajax.reload();
                    CancelTransferDetailAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El detalle de la transferencia seleccionado no fue cancelada.')
        }
    });
}

function CancelTransferDetailAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.success(response.message);
    }
}

function CancelTransferDetailAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
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
    }
}
