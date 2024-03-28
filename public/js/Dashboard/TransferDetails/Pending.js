function PendingTransferDetail(id) {
    Swal.fire({
        title: '¿Desea pendiente el detalle de la transferencia?',
        text: 'El detalle de la transferencia será pendiente.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, aprovar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Transfers/Details/Pending`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id
                },
                success: function(response) {
                    tableTransferDetails.ajax.reload();
                    PendingTransferDetailAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    PendingTransferDetailAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El detalle de la transferencia seleccionada no fue pendiente.')
        }
    });
}

function PendingTransferDetailAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.success(response.message);
    }
}

function PendingTransferDetailAjaxError(xhr) {
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
