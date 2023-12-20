function EditClothingLineModal(id) {
    $.ajax({
        url: `/Dashboard/ClothingLines/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            tableClothingLines.ajax.reload();
            EditClothingLineModalCleaned(response.data);
            EditClothingLineAjaxSuccess(response);
            $('#EditClothingLineModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tableClothingLines.ajax.reload();
            EditClothingLineAjaxError(xhr);
        }
    });
}

function EditClothingLineModalCleaned(clothingLine) {
    RemoveIsValidClassEditClothingLine();
    RemoveIsInvalidClassEditClothingLine();

    $('#EditClothingLineButton').attr('onclick', `EditClothingLine(${clothingLine.id})`);

    $("#name_e").val(clothingLine.name);
    $("#code_e").val(clothingLine.code);
    $('#description_e').val(clothingLine.description);
}

function EditClothingLine(id) {
    Swal.fire({
        title: '¿Desea actualizar la linea de producto?',
        text: 'La linea de producto se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/ClothingLines/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_e').val(),
                    'code': $('#code_e').val(),
                    'description': $('#description_e').val()
                },
                success: function (response) {
                    tableClothingLines.ajax.reload();
                    EditClothingLineAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableClothingLines.ajax.reload();
                    EditClothingLineAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La linea de producto no fue actualizado.')
        }
    });
}

function EditClothingLineAjaxSuccess(response) {
    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditClothingLineModal').modal('hide');
    }
}

function EditClothingLineAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditClothingLineModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditClothingLineModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditClothingLineModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditClothingLine();
        RemoveIsInvalidClassEditClothingLine();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditClothingLine(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditClothingLine();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditClothingLineModal').modal('hide');
    }
}

function AddIsValidClassEditClothingLine() {
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

function RemoveIsValidClassEditClothingLine() {
    $('#name_e').removeClass('is-valid');
    $('#code_e').removeClass('is-valid');
    $('#description_e').removeClass('is-valid');
}

function AddIsInvalidClassEditClothingLine(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditClothingLine() {
    $('#name_e').removeClass('is-invalid');
    $('#code_e').removeClass('is-invalid');
    $('#description_e').removeClass('is-invalid');
}
