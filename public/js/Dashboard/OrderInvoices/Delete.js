function DeleteOrderPacked(id) {
    Swal.fire({
        title: '¿Desea eliminar la orden de alistamiento y empacado?',
        text: 'La orden de alistamiento y empacado será eliminada.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, eliminar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Packed/Delete`,
                type: 'DELETE',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id
                },
                success: function(response) {
                    window.location.href = response.data.url;
                    DeleteOrderPackedAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    DeleteOrderPackedAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La orden de alistamiento y empacado no fue eliminada.')
        }
    });
}

function DeleteOrderPackedAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.success(response.message);
    }
}

function DeleteOrderPackedAjaxError(xhr) {
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
