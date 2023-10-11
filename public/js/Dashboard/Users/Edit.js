function EditUserModal(id, name, last_name, document_number, phone_number, address, email) {
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
                    toastr.success(response.message);
                    $('#EditUserModal').modal('hide');
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
            toastr.error('El usuario no se le actualizo la contraseña.')
        }
    });
}
