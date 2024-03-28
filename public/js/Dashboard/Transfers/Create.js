function CreateTransferModal() {
    $.ajax({
        url: `/Dashboard/Transfers/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            CreateTransferModalCleaned();
            CreateTransfersModalFromWarehose(response.data);
            CreateTransferAjaxSuccess(response);
            $('#CreateTransferModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            CreateTransferAjaxError(xhr);
        }
    });
}

function CreateTransferModalCleaned() {
    CreateTransfersModalResetSelect('from_warehouse_id_c');
    CreateTransfersModalResetSelect('to_warehouse_id_c');
    RemoveIsValidClassCreateTransfer();
    RemoveIsInvalidClassCreateTransfer();

    $('#from_observation_c').val('');
}

function CreateTransfersModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function CreateTransfersModalFromWarehose(from_warehouses) {
    from_warehouses.forEach(from_warehouse => {
        $('#from_warehouse_id_c').append(new Option(`${from_warehouse.name} - ${from_warehouse.code}`, from_warehouse.id, false, false));
    });
}

function CreateTransfersModalFromWarehoseGetToWarehouse(select) {
    if($(select).val() == '') {
        CreateTransfersModalResetSelect('to_warehouse_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Transfers/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'from_warehouse_id':  $(select).val()
            },
            success: function(response) {
                CreateTransfersModalResetSelect('to_warehouse_id_c');
                CreateTransfersModalToWarehouse(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateTransferAjaxError(xhr);
            }
        });
    }
};

function CreateTransfersModalToWarehouse(to_warehouses) {
    to_warehouses.forEach(to_warehouse => {
        $('#to_warehouse_id_c').append(new Option(`${to_warehouse.name} - ${to_warehouse.code}`, to_warehouse.id, false, false));
    });
}

function CreateTransfer() {
    Swal.fire({
        title: '¿Desea guardar la transferencia?',
        text: 'La transferencia será creada.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Transfers/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'from_warehouse_id': $('#from_warehouse_id_c').val(),
                    'to_warehouse_id': $('#to_warehouse_id_c').val(),
                    'from_observation': $('#from_observation_c').val()
                },
                success: function (response) {
                    tableTransfers.ajax.reload();
                    CreateTransferAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    CreateTransferAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La transferencia no fue creada.')
        }
    });
}

function CreateTransferAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#CreateTransferModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateTransferModal').modal('hide');
    }
}

function CreateTransferAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateTransferModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateTransferModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateTransferModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateTransfer();
        RemoveIsInvalidClassCreateTransfer();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateTransfer(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateTransfer();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateTransferModal').modal('hide');
    }
}

function AddIsValidClassCreateTransfer() {
    if (!$(`span[aria-labelledby="select2-from_warehouse_id_c-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-from_warehouse_id_c-container"]`).addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-to_warehouse_id_c-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-to_warehouse_id_c-container"]`).addClass('is-valid');
    }
    if (!$('#from_observation_c').hasClass('is-invalid')) {
        $('#from_observation_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateTransfer() {
    $(`span[aria-labelledby="select2-from_warehouse_id_c-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-to_warehouse_id_c-container"]`).removeClass('is-valid');
    $('#from_observation_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateTransfer(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_c-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_c-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateTransfer() {
    $(`span[aria-labelledby="select2-from_warehouse_id_c-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-to_warehouse_id_c-container"]`).removeClass('is-invalid');
    $('#from_observation_c').removeClass('is-invalid');
}
