function EditSizeModal(id) {
    $.ajax({
        url: `/Dashboard/Sizes/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            tableSizes.ajax.reload();
            EditSizeModalCleaned(response.data);
            EditSizeAjaxSuccess(response);
            $('#EditSizeModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tableSizes.ajax.reload();
            EditSizeAjaxError(xhr);
        }
    });
}

function EditSizeModalCleaned(size) {
    RemoveIsValidClassEditSize();
    RemoveIsInvalidClassEditSize();

    $('#EditSizeButton').attr('onclick', `EditSize(${size.id})`);

    $("#name_e").val(size.name);
    $("#code_e").val(size.code);
}

function EditSize(id) {
    Swal.fire({
        title: '¿Desea actualizar la talla?',
        text: 'La talla se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Sizes/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_e').val(),
                    'code': $('#code_e').val(),
                },
                success: function (response) {
                    tableSizes.ajax.reload();
                    EditSizeAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableSizes.ajax.reload();
                    EditSizeAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La talla no fue actualizada.')
        }
    });
}

function EditSizeAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#EditSizeModal').modal('hide');
    }
    
    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditSizeModal').modal('hide');
    }
}

function EditSizeAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditSizeModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditSizeModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditSizeModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditSize();
        RemoveIsInvalidClassEditSize();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditSize(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditSize();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditSizeModal').modal('hide');
    }
}

function AddIsValidClassEditSize() {
    if (!$('#name_e').hasClass('is-invalid')) {
        $('#name_e').addClass('is-valid');
    }
    if (!$('#code_e').hasClass('is-invalid')) {
        $('#code_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditSize() {
    $('#name_e').removeClass('is-valid');
    $('#code_e').removeClass('is-valid');
}

function AddIsInvalidClassEditSize(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditSize() {
    $('#name_e').removeClass('is-invalid');
    $('#code_e').removeClass('is-invalid');
}
