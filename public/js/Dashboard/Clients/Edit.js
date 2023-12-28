function EditTransferModal(id) {
    $.ajax({
        url: `/Dashboard/Transfers/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            console.log(response);
            tableTransfers.ajax.reload();
            EditTransferModalCleaned(response.data.transfer);
            EditTransfersModalFromWarehose(response.data.transfer.from_warehouse);
            EditTransferAjaxSuccess(response);
            $('#EditTransferModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tableTransfers.ajax.reload();
            EditTransferAjaxError(xhr);
        }
    });
}

function EditTransferModalCleaned(transfer) {
    EditTransfersModalResetSelect('from_warehouse_id_e');
    EditTransfersModalResetSelect('to_warehouse_id_e');
    RemoveIsValidClassEditTransfer();
    RemoveIsInvalidClassEditTransfer();

    $('#EditTransferButton').attr('onclick', `EditTransfer(${transfer.id})`);
    $('#EditTransferButton').attr('data-id', transfer.id);
    $('#EditTransferButton').attr('data-from_warehouse_id', transfer.from_warehouse_id);
    $('#EditTransferButton').attr('data-to_warehouse_id', transfer.to_warehouse_id);

    $('#from_observation_e').val(transfer.from_observation);
}

function EditTransfersModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function EditTransfersModalFromWarehose(from_warehouse) {
    $(`#from_warehouse_id_e`).html('')
    $(`#from_warehouse_id_e`).append(new Option(`${from_warehouse.name} - ${from_warehouse.code}`, from_warehouse.id, false, false));

    let from_warehouse_id = $('#EditTransferButton').attr('data-from_warehouse_id');
    if(from_warehouse_id != '') {
        $("#from_warehouse_id_e").val(from_warehouse_id).trigger('change');
        $('#EditTransferButton').attr('data-from_warehouse_id', '');
    }
}

function EditTransfersModalFromWarehoseGetToWarehouse(select) {
    if($(select).val() == '') {
        EditTransfersModalResetSelect('to_warehouse_id_e');
    } else {
        let id = $('#EditTransferButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Transfers/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'from_warehouse_id':  $(select).val()
            },
            success: function(response) {
                EditTransfersModalResetSelect('to_warehouse_id_e');
                EditTransfersModalToWarehouse(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditTransfersAjaxError(xhr);
            }
        });
    }
};

function EditTransfersModalToWarehouse(to_warehouses) {
    to_warehouses.forEach(to_warehouse => {
        $('#to_warehouse_id_e').append(new Option(`${to_warehouse.name} - ${to_warehouse.code}`, to_warehouse.id, false, false));
    });
    let to_warehouse_id = $('#EditTransferButton').attr('data-to_warehouse_id');
    if(to_warehouse_id != '') {
        $("#to_warehouse_id_e").val(to_warehouse_id).trigger('change');
        $('#EditTransferButton').attr('data-to_warehouse_id', '');
    }
}

function EditTransfer(id) {
    Swal.fire({
        title: '¿Desea actualizar la transferencia?',
        text: 'La transferencia se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Transfers/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'to_warehouse_id': $('#to_warehouse_id_e').val(),
                    'from_observation': $('#from_observation_e').val()
                },
                success: function (response) {
                    tableTransfers.ajax.reload();
                    EditTransferAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableTransfers.ajax.reload();
                    EditTransferAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La transferencia no fue actualizada.')
        }
    });
}

function EditTransferAjaxSuccess(response) {
    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditTransferModal').modal('hide');
    }
}

function EditTransferAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditTransferModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditTransferModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditTransferModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditTransfer();
        RemoveIsInvalidClassEditTransfer();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditTransfer(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditTransfer();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditTransferModal').modal('hide');
    }
}

function AddIsValidClassEditTransfer() {
    if (!$(`span[aria-labelledby="select2-from_warehouse_id_e-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-from_warehouse_id_e-container"]`).addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-to_warehouse_id_e-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-to_warehouse_id_e-container"]`).addClass('is-valid');
    }
    if (!$('#from_observation_e').hasClass('is-invalid')) {
        $('#from_observation_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditTransfer() {
    $(`span[aria-labelledby="select2-from_warehouse_id_e-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-to_warehouse_id_e-container"]`).removeClass('is-valid');
    $('#from_observation_e').removeClass('is-valid');
}

function AddIsInvalidClassEditTransfer(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_e-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_e-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditTransfer() {
    $(`span[aria-labelledby="select2-from_warehouse_id_e-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-to_warehouse_id_e-container"]`).removeClass('is-invalid');
    $('#from_observation_e').removeClass('is-invalid');
}
