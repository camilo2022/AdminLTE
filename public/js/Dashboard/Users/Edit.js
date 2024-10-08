function EditUserModal(id) {
    $.ajax({
        url: `/Dashboard/Users/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            EditUserModalCleaned(response.data.user);
            EditUserModalAreas(response.data.areas);
            EditUserAjaxSuccess(response);
            $('#EditUserModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            EditUserAjaxError(xhr);
        }
    });
}

function EditUserModalCleaned(user) {
    EditUserModalResetSelect('area_id_e');
    RemoveIsValidClassEditUser();
    RemoveIsInvalidClassEditUser();

    $('#EditUserButton').attr('onclick', `EditUser(${user.id})`);
    $('#EditUserButton').attr('data-id', user.id);
    $('#EditUserButton').attr('data-area_id', user.area_id);
    $('#EditUserButton').attr('data-charge_id', user.charge_id);

    $("#name_e").val(user.name);
    $("#last_name_e").val(user.last_name);
    $("#document_number_e").val(user.document_number);
    $("#phone_number_e").val(user.phone_number);
    $("#address_e").val(user.address);
    $("#email_e").val(user.email);
}

function EditUserModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function EditUserModalAreas(areas) {
    areas.forEach(area => {
        $('#area_id_e').append(new Option(area.name, area.id, false, false));
    });
    let area_id = $('#EditUserButton').attr('data-area_id');
    if(area_id != '') {
        $("#area_id_e").val(area_id).trigger('change');
        $('#EditUserButton').attr('data-area_id', '');
    }
}

function EditUserModalAreasGetCharge(select) {
    if($(select).val() === '') {
        EditUserModalResetSelect('charge_id_e');
    } else {
        let id = $('#EditUserButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Users/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'area_id':  $(select).val()
            },
            success: function(response) {
                EditUserModalResetSelect('charge_id_e');
                EditUserModalCharges(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditBusinessAjaxError(xhr);
            }
        });
    }
};

function EditUserModalCharges(charges) {
    charges.forEach(charge => {
        $('#charge_id_e').append(new Option(charge.name, charge.id, false, false));
    });
    let charge_id = $('#EditUserButton').attr('data-charge_id');
    if(charge_id != '') {
        $("#charge_id_e").val(charge_id).trigger('change');
        $('#EditUserButton').attr('data-charge_id', '');
    }
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
                    'email': $("#email_e").val(),
                    'area_id': $('#area_id_e').val(),
                    'charge_id': $('#charge_id_e').val(),
                },
                success: function(response) {
                    tableUsers.ajax.reload();
                    EditUserAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
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

    if(response.status === 204) {
        toastr.info(response.message);
        $('#PasswordUserModal').modal('hide');
    }
}

function EditUserAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditUserModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditUserModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
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
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
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
    if (!$('span[aria-labelledby="select2-area_id_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-area_id_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-charge_id_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-charge_id_e-container"]').addClass('is-valid');
    }
}

function RemoveIsValidClassEditUser() {
    $('#name_e').removeClass('is-valid');
    $('#last_name_e').removeClass('is-valid');
    $('#document_number_e').removeClass('is-valid');
    $('#phone_number_e').removeClass('is-valid');
    $('#address_e').removeClass('is-valid');
    $('#email_e').removeClass('is-valid');
    $('span[aria-labelledby="select2-area_id_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-charge_id_e-container"]').removeClass('is-valid');
}

function AddIsInvalidClassEditUser(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
    if (!$(`#span[aria-labelledby="select2-${input}_e-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_e-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditUser() {
    $('#name_e').removeClass('is-invalid');
    $('#last_name_e').removeClass('is-invalid');
    $('#document_number_e').removeClass('is-invalid');
    $('#phone_number_e').removeClass('is-invalid');
    $('#address_e').removeClass('is-invalid');
    $('#email_e').removeClass('is-invalid');
    $('span[aria-labelledby="select2-area_id_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-charge_id_e-container"]').removeClass('is-invalid');
}
