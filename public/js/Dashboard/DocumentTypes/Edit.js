function EditDocumentTypeModal(id) {
    $.ajax({
        url: `/Dashboard/DocumentTypes/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            EditDocumentTypeModalCleaned(response.data);
            EditDocumentTypeAjaxSuccess(response);
            $('#EditDocumentTypeModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            EditDocumentTypeAjaxError(xhr);
        }
    });
}

function EditDocumentTypeModalCleaned(documentType) {
    RemoveIsValidClassEditDocumentType();
    RemoveIsInvalidClassEditDocumentType();

    $('#EditDocumentTypeButton').attr('onclick', `EditDocumentType(${documentType.id})`);

    $("#name_e").val(documentType.name);
    $("#code_e").val(documentType.code);
}

function EditDocumentType(id) {
    Swal.fire({
        title: '¿Desea actualizar el tipo de documento?',
        text: 'El tipo de documento se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/DocumentTypes/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_e').val(),
                    'code': $('#code_e').val()
                },
                success: function (response) {
                    tableDocumentTypes.ajax.reload();
                    EditDocumentTypeAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableDocumentTypes.ajax.reload();
                    EditDocumentTypeAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El tipo de documento no fue actualizado.')
        }
    });
}

function EditDocumentTypeAjaxSuccess(response) {
    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditDocumentTypeModal').modal('hide');
    }
}

function EditDocumentTypeAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditDocumentTypeModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditDocumentTypeModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditDocumentTypeModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditDocumentType();
        RemoveIsInvalidClassEditDocumentType();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditDocumentType(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditDocumentType();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditDocumentTypeModal').modal('hide');
    }
}

function AddIsValidClassEditDocumentType() {
    if (!$('#name_e').hasClass('is-invalid')) {
        $('#name_e').addClass('is-valid');
    }
    if (!$('#code_e').hasClass('is-invalid')) {
        $('#code_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditDocumentType() {
    $('#name_e').removeClass('is-valid');
    $('#code_e').removeClass('is-valid');
}

function AddIsInvalidClassEditDocumentType(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).removeClass('is-valid');
    }
    $(`#${input}_e`).addClass('is-invalid');
}

function RemoveIsInvalidClassEditDocumentType() {
    $('#name_e').removeClass('is-invalid');
    $('#code_e').removeClass('is-invalid');
}
