function PendingOrderDispatch(id, status = true) {
    Swal.fire({
        title: '¿Desea pendiente la orden de despacho del pedido?',
        text: 'La orden de despacho del pedido será pendiente.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, pendiente!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Dispatch/Pending`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id
                },
                success: function(response) {
                    status ? tableOrderDispatches.ajax.reload() : location.reload() ;
                    PendingOrderDispatchAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    PendingOrderDispatchAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La orden de despacho del pedido seleccionada no fue pendiente.')
        }
    });
}

function PendingOrderDispatchAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
    }

    if(response.status === 422) {
        toastr.warning(response.message);
    }
}

function PendingOrderDispatchAjaxError(xhr) {
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
