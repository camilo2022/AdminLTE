function CancelOrderDispatch(id, status = true) {
    Swal.fire({
        title: '¿Desea cancelar la orden de despacho del pedido?',
        text: 'La orden de despacho del pedido será cancelada.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, cancelar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Dispatch/Cancel`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id
                },
                success: function(response) {
                    status ? tableOrderDispatches.ajax.reload() : location.reload() ;
                    CancelOrderDispatchAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    status ? tableOrderDispatches.ajax.reload() : location.reload() ;
                    CancelOrderDispatchAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La orden de despacho del pedido seleccionada no fue cancelar.')
        }
    });
}

function CancelOrderDispatchAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
    }

    if(response.status === 422) {
        toastr.warning(response.message);
    }
}

function CancelOrderDispatchAjaxError(xhr) {
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
