function EditOrderReturnModal(id) {
    $.ajax({
        url: `/Dashboard/Orders/Return/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            EditOrderReturnModalCleaned(response.data.orderReturn);
            EditOrderReturnModalReturnType(response.data.returnTypes);
            EditOrderReturnAjaxSuccess(response);
            $('#EditOrderReturnModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            EditOrderReturnAjaxError(xhr);
        }
    });
}

function EditOrderReturnModalCleaned(orderReturn) {
    EditOrderReturnModalResetSelect('return_type_id_e');
    RemoveIsValidClassEditOrderReturn();
    RemoveIsInvalidClassEditOrderReturn();

    $('#EditOrderReturnButton').attr('onclick', `EditOrderReturn(${orderReturn.id})`);
    $('#EditOrderReturnButton').attr('data-return_type_id', orderReturn.return_type_id);
    $('#EditOrderReturnButton').attr('data-id', orderReturn.id);

    $('#client_e').val(orderReturn.order.client.name);
    $('#document_type_e').val(orderReturn.order.client.document_type.name);
    $('#document_number_e').val(`${orderReturn.order.client.document_number}-${orderReturn.order.client_branch.code}`);
    $('#client_branch_e').val(orderReturn.order.client_branch.name);
    $('#address_e').val(orderReturn.order.client_branch.address);
    $('#neighborhood_e').val(orderReturn.order.client_branch.neighborhood);
    $('#sale_channel_e').val(orderReturn.order.sale_channel.name);
    $('#seller_e').val(`${orderReturn.order.seller_user.name} ${orderReturn.order.seller_user.last_name}`);
    $('#wallet_e').val(`${orderReturn.order.wallet_user.name} ${orderReturn.order.wallet_user.last_name}`);
    $('#date_seller_e').val(orderReturn.order.seller_date);
    $('#date_wallet_e').val(orderReturn.order.wallet_date);
    $('#date_dispatched_e').val(orderReturn.order.dispatched_date);
    $('#return_date_e').val(orderReturn.return_date);
    $('#return_observation_e').val(orderReturn.return_observation);
}

function EditOrderReturnModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function EditOrderReturnModalReturnType(returnTypes) {
    returnTypes.forEach(returnType => {
        $('#return_type_id_e').append(new Option(returnType.name, returnType.id, false, false));
    });

    let return_type_id = $('#EditOrderReturnButton').attr('data-return_type_id');
    if(return_type_id != '') {
        $("#return_type_id_e").val(return_type_id).trigger('change');
        $('#EditOrderReturnButton').attr('data-return_type_id', '');
    }
}

function EditOrderReturn(id) {
    Swal.fire({
        title: '¿Desea actualizar la orden de devolucion del pedido?',
        text: 'La orden de devolucion del pedido se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Return/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'return_type_id': $('#return_type_id_e').val(),
                    'return_date': new Date($('#return_date_e').val()).toISOString().slice(0, 19).replace('T', ' '),
                    'return_observation': $('#return_observation_e').val()
                },
                success: function (response) {
                    tableOrderReturns.ajax.reload();
                    EditOrderReturnAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    EditOrderReturnAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La orden de devolucion del pedido no fue actualizada.')
        }
    });
}

function EditOrderReturnAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#EditOrderReturnModal').modal('hide');
    }

    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditOrderReturnModal').modal('hide');
    }
}

function EditOrderReturnAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderReturnModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderReturnModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderReturnModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditOrderReturn();
        RemoveIsInvalidClassEditOrderReturn();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditOrderReturn(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditOrderReturn();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderReturnModal').modal('hide');
    }
}

function AddIsValidClassEditOrderReturn() {
    if (!$('#return_observation_e').hasClass('is-invalid')) {
        $('#return_observation_e').addClass('is-valid');
    }
    if (!$('#return_date_e').hasClass('is-invalid')) {
        $('#return_date_e').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-return_type_id_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-return_type_id_c-container"]').addClass('is-valid');
    }
}

function RemoveIsValidClassEditOrderReturn() {
    $('#return_observation_e').removeClass('is-valid');
    $('#return_date_e').removeClass('is-valid');
    $('span[aria-labelledby="select2-return_type_id_c-container"]').removeClass('is-valid');
}

function AddIsInvalidClassEditOrderReturn(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_c-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_c-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditOrderReturn() {
    $('#return_observation_e').removeClass('is-invalid');
    $('#return_date_e').removeClass('is-invalid');
    $('span[aria-labelledby="select2-return_type_id_c-container"]').removeClass('is-invalid');
}
