function DeleteClothingLine(id) {
    Swal.fire({
        title: '¿Desea eliminar la linea de producto?',
        text: 'La linea de producto será desactivado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, eliminar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/ClothingLines/Delete`,
                type: 'DELETE',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id
                },
                success: function(response) {
                    tableClothingLines.ajax.reload();
                    DeleteClothingLineAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableClothingLines.ajax.reload();
                    DeleteClothingLineAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La linea de producto seleccionada no fue desactivado.')
        }
    });
}

function DeleteClothingLineAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.success(response.message);
    }

    if(response.status === 422) {
        toastr.warning(response.message);
    }
}

function DeleteClothingLineAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error.message);
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
        if(xhr.responseJSON.error) {
            toastr.error(xhr.responseJSON.error.message);
        }

        if(xhr.responseJSON.message) {
            toastr.error(xhr.responseJSON.message);
        }
    }
}
