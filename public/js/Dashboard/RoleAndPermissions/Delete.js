function DeleteRoleAndPermissions(id, permission) {
    console.log(id, permission);
    Swal.fire({
        title: '¿Desea eliminar el rol y los permisos?',
        text: 'El rol y sus permisos serán eliminados.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, eliminar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/RolesAndPermissions/Delete`,
                type: 'DELETE',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'role_id': [id],
                    'permission_id': permission
                },
                success: function(response) {
                    tableRolesAndPermissions.ajax.reload();
                    toastr.success(response.message);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableRolesAndPermissions.ajax.reload();
                    if(xhr.responseJSON.error){
                        toastr.error(xhr.responseJSON.error.message);
                    }
                    if(xhr.responseJSON.errors){
                        $.each(xhr.responseJSON.errors, function(field, messages) {
                            $.each(messages, function(index, message) {
                                toastr.error(message);
                            });
                        });
                    }
                }
            });
        } else {
            toastr.info('El rol y los permisos seleccionados no fueron eliminados.')
        }
    });
}
