function EditUserModal(id, name, last_name, document_number, phone_number, address, email) {
    RemoveIsValidClassEditUser();
    RemoveIsInvalidClassEditUser();

    $('#EditUserButton').attr('onclick', `EditUser(${id})`);

    $("#name_e").val(name);
    $("#last_name_e").val(last_name);
    $("#document_number_e").val(document_number);
    $("#phone_number_e").val(phone_number);
    $("#address_e").val(address);
    $("#email_e").val(email);
}

function EditUser(id) {
    Swal.fire({
        title: '¿Desea actualizar el usuario?',
        text: 'El usuario se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Users/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id,
                    'name': $("#name_e").val(),
                    'last_name': $("#last_name_e").val(),
                    'document_number': $("#document_number_e").val(),
                    'phone_number': $("#phone_number_e").val(),
                    'address': $("#address_e").val(),
                    'email': $("#email_e").val()
                },
                success: function(response) {
                    tableUsers.ajax.reload();
                    EditUserAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableUsers.ajax.reload();
                    EditUserAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El usuario no fue actualizado.')
        }
    });
}

function EditUserAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
        $('#EditUserModal').modal('hide');
    }
}

function EditUserAjaxError(xhr) {
    console.log(xhr);
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error.message);
        $('#EditUserModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditUserModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error.message);
        $('#EditUserModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassEditUser();
        RemoveIsInvalidClassEditUser();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassEditUser(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditUser();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.message);
        $('#EditUserModal').modal('hide');
    }
}

function AddIsValidClassEditUser() {
    if (!$('#name_e').hasClass('is-invalid')) {
      $('#name_e').addClass('is-valid');
    }
    if (!$('#last_name_e').hasClass('is-invalid')) {
      $('#last_name_e').addClass('is-valid');
    }
    if (!$('#document_number_e').hasClass('is-invalid')) {
      $('#document_number_e').addClass('is-valid');
    }
    if (!$('#phone_number_e').hasClass('is-invalid')) {
      $('#phone_number_e').addClass('is-valid');
    }
    if (!$('#address_e').hasClass('is-invalid')) {
      $('#address_e').addClass('is-valid');
    }
    if (!$('#email_e').hasClass('is-invalid')) {
      $('#email_e').addClass('is-valid');
    }
    if (!$('#password_e').hasClass('is-invalid')) {
      $('#password_e').addClass('is-valid');
    }
    if (!$('#password_confirmation_e').hasClass('is-invalid')) {
      $('#password_confirmation_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditUser() {
    $('#name_e').removeClass('is-valid');
    $('#last_name_e').removeClass('is-valid');
    $('#document_number_e').removeClass('is-valid');
    $('#phone_number_e').removeClass('is-valid');
    $('#address_e').removeClass('is-valid');
    $('#email_e').removeClass('is-valid');
}

function AddIsInvalidClassEditUser(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).removeClass('is-valid');
    }
    $(`#${input}_e`).addClass('is-invalid');
}

function RemoveIsInvalidClassEditUser() {
    $('#name_e').removeClass('is-invalid');
    $('#last_name_e').removeClass('is-invalid');
    $('#document_number_e').removeClass('is-invalid');
    $('#phone_number_e').removeClass('is-invalid');
    $('#address_e').removeClass('is-invalid');
    $('#email_e').removeClass('is-invalid');
}
