function CreateOrderReturnModal(order_id) {
    $.ajax({
        url: `/Dashboard/Orders/Return/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'order_id': order_id
        },
        success: function (response) {
            CreateOrderReturnModalCleaned(response.data.order);
            CreateOrderReturnModalReturnType(response.data.returnTypes);
            CreateOrderReturnAjaxSuccess(response);
            $('#CreateOrderReturnModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            CreateOrderReturnAjaxError(xhr);
        }
    });
}

function CreateOrderReturnModalCleaned(order) {
    CreateOrderReturnModalResetSelect('return_type_id_c');
    RemoveIsValidClassCreateOrderReturn();
    RemoveIsInvalidClassCreateOrderReturn();

    $('#CreateOrderReturnButton').attr('onclick', `CreateOrderReturn(${order.id})`);
    $('#CreateOrderReturnButton').attr('data-order_id', order.id);

    $('#client_c').val(order.client.name);
    $('#document_type_c').val(order.client.document_type.name);
    $('#document_number_c').val(`${order.client.document_number}-${order.client_branch.code}`);
    $('#client_branch_c').val(order.client_branch.name);
    $('#address_c').val(order.client_branch.address);
    $('#neighborhood_c').val(order.client_branch.neighborhood);
    $('#sale_channel_c').val(order.sale_channel.name);
    $('#seller_c').val(`${order.seller_user.name} ${order.seller_user.last_name}`);
    $('#wallet_c').val(`${order.wallet_user.name} ${order.wallet_user.last_name}`);
    $('#date_seller_c').val(order.seller_date);
    $('#date_wallet_c').val(order.wallet_date);
    $('#date_dispatched_c').val(order.dispatched_date);
    $('#return_date_c').val('');
    $('#return_observation_c').val('');
}

function CreateOrderReturnModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function CreateOrderReturnModalReturnType(returnTypes) {
    returnTypes.forEach(returnType => {
        $('#return_type_id_c').append(new Option(returnType.name, returnType.id, false, false));
    });
}

function CreateOrderReturn(order_id) {
    Swal.fire({
        title: '¿Desea guardar la orden de devolucion del pedido?',
        text: 'La orden de devolucion del pedido será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Return/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'order_id': order_id,
                    'return_type_id': $('#return_type_id_c').val(),
                    'return_date': $('#return_date_c').val() == '' ? $('#return_date_c').val() : new Date($('#return_date_c').val()).toISOString().slice(0, 19).replace('T', ' '),
                    'return_observation': $('#return_observation_c').val()
                },
                success: function (response) {
                    window.location.href = response.data.url;
                    tableOrderReturns.ajax.reload();
                    CreateOrderReturnAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    CreateOrderReturnAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La orden de devolucion del pedido no fue creado.')
        }
    });
}

function CreateOrderReturnAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#CreateOrderReturnModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateOrderReturnModal').modal('hide');
    }
}

function CreateOrderReturnAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderReturnModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderReturnModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderReturnModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateOrderReturn();
        RemoveIsInvalidClassCreateOrderReturn();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateOrderReturn(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateOrderReturn();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderReturnModal').modal('hide');
    }
}

function AddIsValidClassCreateOrderReturn() {
    if (!$('#return_observation_c').hasClass('is-invalid')) {
        $('#return_observation_c').addClass('is-valid');
    }
    if (!$('#return_date_c').hasClass('is-invalid')) {
        $('#return_date_c').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-return_type_id_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-return_type_id_c-container"]').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateOrderReturn() {
    $('#return_observation_c').removeClass('is-valid');
    $('#return_date_c').removeClass('is-valid');
    $('span[aria-labelledby="select2-return_type_id_c-container"]').removeClass('is-valid');
}

function AddIsInvalidClassCreateOrderReturn(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_c-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_c-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateOrderReturn() {
    $('#return_observation_c').removeClass('is-invalid');
    $('#return_date_c').removeClass('is-invalid');
    $('span[aria-labelledby="select2-return_type_id_c-container"]').removeClass('is-invalid');
}
