function EditModelModal(id) {
    $.ajax({
        url: `/Dashboard/Models/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            tableModels.ajax.reload();
            EditModelModalCleaned(response.data);
            EditModelAjaxSuccess(response);
            $('#EditModelModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tableModels.ajax.reload();
            EditModelAjaxError(xhr);
        }
    });
}

function EditModelModalCleaned(trademark) {
    RemoveIsValidClassEditModel();
    RemoveIsInvalidClassEditModel();

    $('#EditModelButton').attr('onclick', `EditModel(${trademark.id})`);

    $("#name_e").val(trademark.name);
    $("#code_e").val(trademark.code);
    $('#description_e').val(trademark.description);
}

function EditModel(id) {
    Swal.fire({
        title: '¿Desea actualizar el modelo de producto?',
        text: 'El modelo de producto se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Models/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_e').val(),
                    'code': $('#code_e').val(),
                    'description': $('#description_e').val()
                },
                success: function (response) {
                    tableModels.ajax.reload();
                    EditModelAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableModels.ajax.reload();
                    EditModelAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El modelo de producto no fue actualizado.')
        }
    });
}

function EditModelAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#EditModelModal').modal('hide');
    }
    
    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditModelModal').modal('hide');
    }
}

function EditModelAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditModelModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditModelModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditModelModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditModel();
        RemoveIsInvalidClassEditModel();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditModel(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditModel();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditModelModal').modal('hide');
    }
}

function AddIsValidClassEditModel() {
    if (!$('#name_e').hasClass('is-invalid')) {
        $('#name_e').addClass('is-valid');
    }
    if (!$('#code_e').hasClass('is-invalid')) {
        $('#code_e').addClass('is-valid');
    }
    if (!$('#description_e').hasClass('is-invalid')) {
        $('#description_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditModel() {
    $('#name_e').removeClass('is-valid');
    $('#code_e').removeClass('is-valid');
    $('#description_e').removeClass('is-valid');
}

function AddIsInvalidClassEditModel(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).removeClass('is-valid');
    }
    $(`#${input}_e`).addClass('is-invalid');
}

function RemoveIsInvalidClassEditModel() {
    $('#name_e').removeClass('is-invalid');
    $('#code_e').removeClass('is-invalid');
    $('#description_e').removeClass('is-invalid');
}
