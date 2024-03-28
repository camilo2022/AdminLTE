function CreateUserModal() {
    $.ajax({
        url: `/Dashboard/Users/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            CreateUserModalCleaned();
            CreateUserModalAreas(response.data);
            CreateUserAjaxSuccess(response);
            $('#CreateUserModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            CreateUserAjaxError(xhr);
        }
    });
}

function CreateUserModalCleaned() {
    CreateUserModalResetSelect('area_id_c');
    RemoveIsValidClassCreateUser();
    RemoveIsInvalidClassCreateUser();

    $('#name_c').val('');
    $('#last_name_c').val('');
    $('#document_number_c').val('');
    $('#phone_number_c').val('');
    $('#address_c').val('');
    $('#email_c').val('');
    $('#password_c').val('');
    $('#password_confirmation_c').val('');
}

function CreateUserModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function CreateUserModalAreas(areas) {
    areas.forEach(area => {
        $('#area_id_c').append(new Option(area.name, area.id, false, false));
    });
}

function CreateUserModalAreasGetCharge(select) {
    if($(select).val() === '') {
        CreateUserModalResetSelect('charge_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Users/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'area_id':  $(select).val()
            },
            success: function(response) {
                CreateUserModalResetSelect('charge_id_c');
                CreateUserModalCharges(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateBusinessAjaxError(xhr);
            }
        });
    }
};

function CreateUserModalCharges(charges) {
    charges.forEach(charge => {
        $('#charge_id_c').append(new Option(charge.name, charge.id, false, false));
    });
}

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
                    'name': $('#name_c').val(),
                    'last_name': $('#last_name_c').val(),
                    'document_number': $('#document_number_c').val(),
                    'phone_number': $('#phone_number_c').val(),
                    'address': $('#address_c').val(),
                    'email': $('#email_c').val(),
                    'area_id': $('#area_id_c').val(),
                    'charge_id': $('#charge_id_c').val(),
                    'password': $('#password_c').val(),
                    'password_confirmation': $('#password_confirmation_c').val()
                },
                success: function(response) {
                    tableUsers.ajax.reload();
                    CreateUserAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    CreateUserAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El usuario no fue creado.')
        }
    });
}

function CreateUserAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.info(response.message);
        $('#CreateUserModal').modal('hide');
    }

    if(response.status === 201) {
        toastr.success(response.message);
        $('#CreateUserModal').modal('hide');
    }
}

function CreateUserAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateUserModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateUserModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateUserModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassCreateUser();
        RemoveIsInvalidClassCreateUser();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassCreateUser(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateUser();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateUserModal').modal('hide');
    }
}

function AddIsValidClassCreateUser() {
    if (!$('#name_c').hasClass('is-invalid')) {
      $('#name_c').addClass('is-valid');
    }
    if (!$('#last_name_c').hasClass('is-invalid')) {
      $('#last_name_c').addClass('is-valid');
    }
    if (!$('#document_number_c').hasClass('is-invalid')) {
      $('#document_number_c').addClass('is-valid');
    }
    if (!$('#phone_number_c').hasClass('is-invalid')) {
      $('#phone_number_c').addClass('is-valid');
    }
    if (!$('#address_c').hasClass('is-invalid')) {
      $('#address_c').addClass('is-valid');
    }
    if (!$('#email_c').hasClass('is-invalid')) {
      $('#email_c').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-area_id_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-area_id_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-charge_id_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-charge_id_c-container"]').addClass('is-valid');
    }
    if (!$('#password_c').hasClass('is-invalid')) {
      $('#password_c').addClass('is-valid');
    }
    if (!$('#password_confirmation_c').hasClass('is-invalid')) {
      $('#password_confirmation_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateUser() {
    $('#name_c').removeClass('is-valid');
    $('#last_name_c').removeClass('is-valid');
    $('#document_number_c').removeClass('is-valid');
    $('#phone_number_c').removeClass('is-valid');
    $('#address_c').removeClass('is-valid');
    $('#email_c').removeClass('is-valid');
    $('#password_c').removeClass('is-valid');
    $('#password_confirmation_c').removeClass('is-valid');
    $('span[aria-labelledby="select2-area_id_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-charge_id_c-container"]').removeClass('is-valid');
}

function AddIsInvalidClassCreateUser(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
    if (!$(`#span[aria-labelledby="select2-${input}_c-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_c-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateUser() {
    $('#name_c').removeClass('is-invalid');
    $('#last_name_c').removeClass('is-invalid');
    $('#document_number_c').removeClass('is-invalid');
    $('#phone_number_c').removeClass('is-invalid');
    $('#address_c').removeClass('is-invalid');
    $('#email_c').removeClass('is-invalid');
    $('#password_c').removeClass('is-invalid');
    $('#password_confirmation_c').removeClass('is-invalid');
    $('span[aria-labelledby="select2-area_id_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-charge_id_c-container"]').removeClass('is-invalid');
}
