function DeleteSize(id) {
    Swal.fire({
        title: '¿Desea eliminar la talla?',
        text: 'La talla será desactivada.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, eliminar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Sizes/Delete`,
                type: 'DELETE',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id
                },
                success: function(response) {
                    tableSizes.ajax.reload();
                    DeleteSizeAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableSizes.ajax.reload();
                    DeleteSizeAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La talla seleccionada no fue desactivada.')
        }
    });
}

function DeleteSizeAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.success(response.message);
    }

    if(response.status === 422) {
        toastr.warning(response.message);
    }
}

function DeleteSizeAjaxError(xhr) {
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
