function EditClientTypeModal(id) {
    $.ajax({
        url: `/Dashboard/ClientTypes/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            tableClientTypes.ajax.reload();
            EditClientTypeModalCleaned(response.data);
            EditClientTypeAjaxSuccess(response);
            $('#EditClientTypeModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tableClientTypes.ajax.reload();
            EditClientTypeAjaxError(xhr);
        }
    });
}

function EditClientTypeModalCleaned(ClientType) {
    RemoveIsValidClassEditClientType();
    RemoveIsInvalidClassEditClientType();

    $('#EditClientTypeButton').attr('onclick', `EditClientType(${ClientType.id})`);

    $("#name_e").val(ClientType.name);
    $("#code_e").val(ClientType.code);
}

function EditClientType(id) {
    Swal.fire({
        title: '¿Desea actualizar el tipo de cliente?',
        text: 'El tipo de cliente se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/ClientTypes/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_e').val(),
                    'code': $('#code_e').val()
                },
                success: function (response) {
                    tableClientTypes.ajax.reload();
                    EditClientTypeAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableClientTypes.ajax.reload();
                    EditClientTypeAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El tipo de cliente no fue actualizado.')
        }
    });
}

function EditClientTypeAjaxSuccess(response) {
    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditClientTypeModal').modal('hide');
    }
}

function EditClientTypeAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditClientTypeModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditClientTypeModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditClientTypeModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditClientType();
        RemoveIsInvalidClassEditClientType();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditClientType(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditClientType();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditClientTypeModal').modal('hide');
    }
}

function AddIsValidClassEditClientType() {
    if (!$('#name_e').hasClass('is-invalid')) {
        $('#name_e').addClass('is-valid');
    }
    if (!$('#code_e').hasClass('is-invalid')) {
        $('#code_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditClientType() {
    $('#name_e').removeClass('is-valid');
    $('#code_e').removeClass('is-valid');
}

function AddIsInvalidClassEditClientType(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).removeClass('is-valid');
    }
    $(`#${input}_e`).addClass('is-invalid');
}

function RemoveIsInvalidClassEditClientType() {
    $('#name_e').removeClass('is-invalid');
    $('#code_e').removeClass('is-invalid');
}
