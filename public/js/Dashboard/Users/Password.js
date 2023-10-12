function PasswordUserModal(id, email) {
    $('#PasswordUserButton').attr('onclick', `PasswordUser(${id})`);

    $("#email_p").val(email);
    $("#password_p").val('')
    $("#password_confirmation_p").val('')
}

function PasswordUserVisibility(id) {
    let passwordInput = $(`#${id}`);
    let passwordIcon = passwordInput.closest('.input-group');
    if (passwordInput.attr('type') == 'password') {
        passwordInput.attr('type', 'text');
        passwordIcon.find('.fa-eye').toggleClass('fa-eye fa-eye-slash');
    } else if (passwordInput.attr('type') == 'text') {
        passwordInput.attr('type', 'password');
        passwordIcon.find('.fa-eye-slash').toggleClass('fa-eye-slash fa-eye');
    }
}

function PasswordUser(id) {
    Swal.fire({
        title: '¿Desea actualizar la contraseña el usuario?',
        text: 'El usuario se le actualizara la contraseña.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Users/Password/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id,
                    'password': $("#password_p").val(),
                    'password_confirmation': $("#password_confirmation_p").val()
                },
                success: function(response) {
                    tableUsers.ajax.reload();
                    toastr.success(response.message);
                    $('#PasswordUserModal').modal('hide');
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableUsers.ajax.reload();
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
            toastr.info('El usuario no se le actualizo la contraseña.')
        }
    });
}
