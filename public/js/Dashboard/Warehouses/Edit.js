function EditWarehouseModal(id) {
    $.ajax({
        url: `/Dashboard/Warehouses/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            EditWarehouseModalCleaned(response.data);
            EditWarehouseAjaxSuccess(response);
            $('#EditWarehouseModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            EditWarehouseAjaxError(xhr);
        }
    });
}

function EditWarehouseModalCleaned(trademark) {
    RemoveIsValidClassEditWarehouse();
    RemoveIsInvalidClassEditWarehouse();

    $('#EditWarehouseButton').attr('onclick', `EditWarehouse(${trademark.id})`);

    $("#name_e").val(trademark.name);
    $("#code_e").val(trademark.code);
    $('#description_e').val(trademark.description);
}

function EditWarehouse(id) {
    Swal.fire({
        title: '¿Desea actualizar la bodega?',
        text: 'La bodega se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Warehouses/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_e').val(),
                    'code': $('#code_e').val(),
                    'description': $('#description_e').val()
                },
                success: function (response) {
                    tableWarehouses.ajax.reload();
                    EditWarehouseAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableWarehouses.ajax.reload();
                    EditWarehouseAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La bodega no fue actualizada.')
        }
    });
}

function EditWarehouseAjaxSuccess(response) {
    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditWarehouseModal').modal('hide');
    }
}

function EditWarehouseAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error.message);
        $('#EditWarehouseModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditWarehouseModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error.message);
        $('#EditWarehouseModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditWarehouse();
        RemoveIsInvalidClassEditWarehouse();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditWarehouse(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditWarehouse();
    }

    if (xhr.status === 500) {
        if (xhr.responseJSON.error) {
            toastr.error(xhr.responseJSON.error.message);
        }

        if (xhr.responseJSON.message) {
            toastr.error(xhr.responseJSON.message);
        }
        $('#EditWarehouseModal').modal('hide');
    }
}

function AddIsValidClassEditWarehouse() {
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

function RemoveIsValidClassEditWarehouse() {
    $('#name_e').removeClass('is-valid');
    $('#code_e').removeClass('is-valid');
    $('#description_e').removeClass('is-valid');
}

function AddIsInvalidClassEditWarehouse(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).removeClass('is-valid');
    }
    $(`#${input}_e`).addClass('is-invalid');
}

function RemoveIsInvalidClassEditWarehouse() {
    $('#name_e').removeClass('is-invalid');
    $('#code_e').removeClass('is-invalid');
    $('#description_e').removeClass('is-invalid');
}
