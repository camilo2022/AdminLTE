function DeleteModuleAndSubmodules(id) {
    Swal.fire({
        title: '¿Desea eliminar el modulos y los submodulos?',
        text: 'El modulo y sus submodulos serán eliminados.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, eliminar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/ModulesAndSubmodules/Delete`,
                type: 'DELETE',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id
                },
                success: function(response) {
                    tableModulesAndSubmodules.ajax.reload();
                    toastr.success(response.message);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableModulesAndSubmodules.ajax.reload();
                    if(xhr.responseJSON.error){
                        toastr.error(xhr.responseJSON.error.message);
                        toastr.error(xhr.responseJSON.error.error);
                    } else if(xhr.responseJSON.errors){
                        $.each(xhr.responseJSON.errors, function(field, messages) {
                            $.each(messages, function(index, message) {
                                toastr.error(message);
                            });
                        });
                    } else {
                        toastr.error(xhr.responseJSON.message);
                    }
                }
            });
        } else {
            toastr.info('El modulo y los submodulos seleccionados no fueron eliminados.')
        }
    });
}
