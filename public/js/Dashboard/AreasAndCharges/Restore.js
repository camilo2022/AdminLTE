function RestoreAreaAndCharges(id) {
    Swal.fire({
        title: '¿Desea restaurar el area y los cargos?',
        text: 'El area y los cargos serán restaurada.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, restaurar!',
        cancelButtonText: 'No, cancelar!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '/Dashboard/AreasAndCharges/Restore',
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id
                },
                success: function(response) {
                    tableAreasAndCharges.ajax.reload();
                    RestoreAreaAndChargesAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableAreasAndCharges.ajax.reload();
                    RestoreAreaAndChargesAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La categoria y las subcategorias seleccionadas no fueron restauradas.')
        }
    });
}

function RestoreAreaAndChargesAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.success(response.message);
    }
}

function RestoreAreaAndChargesAjaxError(xhr) {
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
