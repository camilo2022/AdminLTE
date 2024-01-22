function CreatePersonTypeModal() {
    $.ajax({
        url: `/Dashboard/PersonTypes/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            CreatePersonTypeModalCleaned();
            CreatePersonTypeAjaxSuccess(response);
            $('#CreatePersonTypeModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            CreatePersonTypeAjaxError(xhr);
        }
    });
}

function CreatePersonTypeModalCleaned() {
    RemoveIsValidClassCreatePersonType();
    RemoveIsInvalidClassCreatePersonType();

    $('#name_c').val('');
    $('#code_c').val('');
}

function CreatePersonType() {
    Swal.fire({
        title: '¿Desea guardar el tipo de persona?',
        text: 'El tipo de persona será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
        html: '<div class="icheck-primary"><input type="checkbox" id="require_people_c" name="require_people_c"><label for="require_people_c">¿Requiere referencias personales?</label></div>',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/PersonTypes/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                    'code': $('#code_c').val(),
                    'require_people': $('#require_people_c').is(':checked')
                },
                success: function (response) {
                    tablePersonTypes.ajax.reload();
                    CreatePersonTypeAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tablePersonTypes.ajax.reload();
                    CreatePersonTypeAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El tipo de persona no fue creado.')
        }
    });
}

function CreatePersonTypeAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#CreatePersonTypeModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreatePersonTypeModal').modal('hide');
    }
}

function CreatePersonTypeAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreatePersonTypeModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreatePersonTypeModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreatePersonTypeModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreatePersonType();
        RemoveIsInvalidClassCreatePersonType();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreatePersonType(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreatePersonType();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreatePersonTypeModal').modal('hide');
    }
}

function AddIsValidClassCreatePersonType() {
    if (!$('#name_c').hasClass('is-invalid')) {
        $('#name_c').addClass('is-valid');
    }
    if (!$('#code_c').hasClass('is-invalid')) {
        $('#code_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreatePersonType() {
    $('#name_c').removeClass('is-valid');
    $('#code_c').removeClass('is-valid');
    $('#value_c').removeClass('is-valid');
}

function AddIsInvalidClassCreatePersonType(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreatePersonType() {
    $('#name_c').removeClass('is-invalid');
    $('#code_c').removeClass('is-invalid');
}
