function CreateTransferDetailModal() {
    $.ajax({
        url: `/Dashboard/Transfers/Details/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            
        },
        success: function (response) {
            tableTransferDetails.ajax.reload();
            CreateTransferDetailModalCleaned();
            CreateTransferDetailsModalFromWarehose(response.data);
            CreateTransferDetailAjaxSuccess(response);
            $('#CreateTransferDetailModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tableTransferDetails.ajax.reload();
            CreateTransferDetailAjaxError(xhr);
        }
    });
}

function CreateTransferDetailModalCleaned() {
    CreateTransferDetailsModalResetSelect('from_warehouse_id_c');
    CreateTransferDetailsModalResetSelect('to_warehouse_id_c');
    RemoveIsValidClassCreateTransferDetail();
    RemoveIsInvalidClassCreateTransferDetail();

    $('#from_observation_c').val('');
}

function CreateTransferDetailsModalResetSelect(id) {
    const select = $(`#${id}`);
    select.html('');
    const defaultOption = $('<option>', {
        value: '',
        text: 'Seleccione'
    });
    select.append(defaultOption);
    select.trigger('change');
}

function CreateTransferDetailsModalFromWarehose(from_warehouses) {
    from_warehouses.forEach(from_warehouse => {
        let newOption = new Option(`${from_warehouse.name} - ${from_warehouse.code}`, from_warehouse.id, false, false);
        $('#from_warehouse_id_c').append(newOption);
    });
}

function CreateTransferDetailsModalFromWarehoseGetToWarehouse(select) {
    if($(select).val() == '') {
        CreateTransferDetailsModalResetSelect('to_warehouse_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/TransferDetails/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'from_warehouse_id':  $(select).val()
            },
            success: function(response) {
                CreateTransferDetailsModalResetSelect('to_warehouse_id_c');
                CreateTransferDetailsModalToWarehouse(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateTransferDetailsAjaxError(xhr);
            }
        });
    }
};

function CreateTransferDetailsModalToWarehouse(to_warehouses) {
    to_warehouses.forEach(to_warehouse => {
        let newOption = new Option(`${to_warehouse.name} - ${to_warehouse.code}`, to_warehouse.id, false, false);
        $('#to_warehouse_id_c').append(newOption);
    });
}

function CreateTransferDetail() {
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
                url: `/Dashboard/TransferDetails/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'from_warehouse_id': $('#from_warehouse_id_c').val(),
                    'to_warehouse_id': $('#to_warehouse_id_c').val(),
                    'from_observation': $('#from_observation_c').val()
                },
                success: function (response) {
                    tableTransferDetails.ajax.reload();
                    CreateTransferDetailAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableTransferDetails.ajax.reload();
                    CreateTransferDetailAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La transferencia no fue creada.')
        }
    });
}

function CreateTransferDetailAjaxSuccess(response) {
    if (response.status === 200) {
        toastr.info(response.message);
        $('#CreateTransferDetailModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateTransferDetailModal').modal('hide');
    }
}

function CreateTransferDetailAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateTransferDetailModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateTransferDetailModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateTransferDetailModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateTransferDetail();
        RemoveIsInvalidClassCreateTransferDetail();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateTransferDetail(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateTransferDetail();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateTransferDetailModal').modal('hide');
    }
}

function AddIsValidClassCreateTransferDetail() {
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

function RemoveIsValidClassCreateTransferDetail() {
    $(`span[aria-labelledby="select2-from_warehouse_id_c-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-to_warehouse_id_c-container"]`).removeClass('is-valid');
    $('#from_observation_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateTransferDetail(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_c-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_c-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateTransferDetail() {
    $(`span[aria-labelledby="select2-from_warehouse_id_c-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-to_warehouse_id_c-container"]`).removeClass('is-invalid');
    $('#from_observation_c').removeClass('is-invalid');
}
