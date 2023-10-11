function RestoreUser(id) {
    Swal.fire({
        title: '¿Desea restaurar el usuario?',
        text: 'El usuario será restaurado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, restaurar!',
        cancelButtonText: 'No, cancelar!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '/Dashboard/Users/Restore',
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id
                },
                success: function(response) {
                    tableUsers.ajax.reload();
                    toastr.success(response.message)
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableUsers.ajax.reload(); 
                    if(xhr.responseJSON.error){
                        toastr.error(xhr.responseJSON.error.message)
                    }
                    if(xhr.responseJSON.errors){
                        $.each(xhr.responseJSON.errors, function(field, messages) {
                            $.each(messages, function(index, message) {
                                toastr.error(message)
                            });
                        });
                    }
                }
            });
        } else {
            toastr.error('El usuario seleccionado no fue restaurado.')
        }
    });
}
