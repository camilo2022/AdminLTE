function EditPersonTypeModal(id) {
    $.ajax({
        url: `/Dashboard/PersonTypes/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            tablePersonTypes.ajax.reload();
            EditPersonTypeModalCleaned(response.data);
            EditPersonTypeAjaxSuccess(response);
            $('#EditPersonTypeModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tablePersonTypes.ajax.reload();
            EditPersonTypeAjaxError(xhr);
        }
    });
}

function EditPersonTypeModalCleaned(personType) {
    RemoveIsValidClassEditPersonType();
    RemoveIsInvalidClassEditPersonType();

    $('#EditPersonTypeButton').attr('onclick', `EditPersonType(${personType.id}, ${personType.require_people})`);

    $("#name_e").val(personType.name);
    $("#code_e").val(personType.code);
}

function EditPersonType(id, require_people) {
    Swal.fire({
        title: '¿Desea actualizar el tipo de persona?',
        text: 'El tipo de persona se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
        html: `<div class="icheck-primary"><input type="checkbox" id="require_people_c" name="require_people" ${require_people ? 'checked' : ''}><label for="require_people">¿Requiere referencias personales?</label></div>`,
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/PersonTypes/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_e').val(),
                    'code': $('#code_e').val(),
                    'require_people': $('#require_people_c').is(':checked')
                },
                success: function (response) {
                    tablePersonTypes.ajax.reload();
                    EditPersonTypeAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tablePersonTypes.ajax.reload();
                    EditPersonTypeAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El tipo de persona no fue actualizado.')
        }
    });
}

function EditPersonTypeAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#EditPersonTypeModal').modal('hide');
    }

    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditPersonTypeModal').modal('hide');
    }
}

function EditPersonTypeAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditPersonTypeModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditPersonTypeModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditPersonTypeModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditPersonType();
        RemoveIsInvalidClassEditPersonType();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditPersonType(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditPersonType();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditPersonTypeModal').modal('hide');
    }
}

function AddIsValidClassEditPersonType() {
    if (!$('#name_e').hasClass('is-invalid')) {
        $('#name_e').addClass('is-valid');
    }
    if (!$('#code_e').hasClass('is-invalid')) {
        $('#code_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditPersonType() {
    $('#name_e').removeClass('is-valid');
    $('#code_e').removeClass('is-valid');
}

function AddIsInvalidClassEditPersonType(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).removeClass('is-valid');
    }
    $(`#${input}_e`).addClass('is-invalid');
}

function RemoveIsInvalidClassEditPersonType() {
    $('#name_e').removeClass('is-invalid');
    $('#code_e').removeClass('is-invalid');
}
