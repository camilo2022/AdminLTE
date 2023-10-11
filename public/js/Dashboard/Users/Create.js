function CreateUser() {
    Swal.fire({
        title: '¿Desea guardar el usuario?',
        text: 'El usuario será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Users/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $("#name_s").val(),
                    'last_name': $("#last_name_s").val(),
                    'document_number': $("#document_number_s").val(),
                    'phone_number': $("#phone_number_s").val(),
                    'address': $("#address_s").val(),
                    'email': $("#email_s").val(),
                    'password': $("#password_s").val(),
                    'password_confirmation': $("#password_confirmation_s").val()
                },
                success: function(response) {
                    tableUsers.ajax.reload();
                    toastr.success(response.message);
                    $('#CreateUserModal').modal('hide');
                    $("#name_s").val('');
                    $("#last_name_s").val('');
                    $("#document_number_s").val('');
                    $("#phone_number_s").val('');
                    $("#address_s").val('');
                    $("#email_s").val('');
                    $("#password_s").val('');
                    $("#password_confirmation_s").val('');
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableUsers.ajax.reload();
                    if (xhr.status === 403) {
                        toastr.error(xhr.responseJSON.message);
                    }
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
            toastr.error('El usuario no fue creado.')
        }
    });
}
