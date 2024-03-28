function CreateOrderPacked(order_dispatch_id) {
    Swal.fire({
        title: '¿Desea alistar y empacar la orden de desapacho?',
        text: 'La orden de alistamiento y empacado será creada.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, alistar y empacar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Packed/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'order_dispatch_id': order_dispatch_id
                },
                success: function(response) {
                    window.location.href = response.data.url;
                    CreateOrderPackedAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    CreateOrderPackedAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La orden de alistamiento y empacado no fue creada.')
        }
    });
}

function CreateOrderPackedAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
    }
}

function CreateOrderPackedAjaxError(xhr) {
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
