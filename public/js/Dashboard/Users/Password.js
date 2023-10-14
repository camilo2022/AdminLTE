function PasswordUserModal(id, email) {
    RemoveIsValidClassPasswordUser();
    RemoveIsInvalidClassPasswordUser();
    $('#PasswordUserButton').attr('onclick', `PasswordUser(${id})`);

    $("#email_p").val(email);
    $("#password_p").val('')
    $("#password_confirmation_p").val('')
}

function PasswordUserVisibility(id) {
    let passwordInput = $(`#${id}`);
    let passwordIcon = passwordInput.closest('.input-group');
    if (passwordInput.attr('type') === 'password') {
        passwordInput.attr('type', 'text');
        passwordIcon.find('.fa-eye').toggleClass('fa-eye fa-eye-slash');
    } else if (passwordInput.attr('type') === 'text') {
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
                    RemoveIsValidClassPasswordUser();
                    RemoveIsInvalidClassPasswordUser();
                    tableUsers.ajax.reload();
                    toastr.success(response.message);
                    $('#PasswordUserModal').modal('hide');
                },
                error: function(xhr, textStatus, errorThrown) {
                    RemoveIsInvalidClassPasswordUser();
                    tableUsers.ajax.reload();
                    if(xhr.responseJSON.error){
                        toastr.error(xhr.responseJSON.error.message);
                    }
                    if(xhr.responseJSON.errors){
                        $.each(xhr.responseJSON.errors, function(field, messages) {
                            AddIsInvalidClassPasswordUser(field);
                            $.each(messages, function(index, message) {
                                toastr.error(message);
                            });
                        });
                    }
                    AddIsValidClassPasswordUser();
                }
            });
        } else {
            toastr.info('El usuario no se le actualizo la contraseña.')
        }
    });
}

function AddIsValidClassPasswordUser() {
    if (!$('#password_p').hasClass('is-invalid')) {
      $('#password_p').addClass('is-valid');
    }
    if (!$('#password_confirmation_p').hasClass('is-invalid')) {
      $('#password_confirmation_p').addClass('is-valid');
    }
}

function RemoveIsValidClassPasswordUser() {
    $('#password_p').removeClass('is-valid');
    $('#password_confirmation_p').removeClass('is-valid');
}

function AddIsInvalidClassPasswordUser(input) {
    if (!$(`#${input}_p`).hasClass('is-valid')) {
        $(`#${input}_p`).removeClass('is-valid');
    }
    $(`#${input}_p`).addClass('is-invalid');
}

function RemoveIsInvalidClassPasswordUser() {
    $('#password_p').removeClass('is-invalid');
    $('#password_confirmation_p').removeClass('is-invalid');
}
