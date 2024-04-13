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
    console.log(order);
    CreateOrderReturnModalResetSelect('return_type_id_c');
    RemoveIsValidClassCreateOrderReturn();
    RemoveIsInvalidClassCreateOrderReturn();

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

function CreateOrderReturn() {
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
                    'return_type_id': $('#return_type_id_c').val(),
                    'return_date': $('#return_date_c').val(),
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
    if (!$('#Return_observation_c').hasClass('is-invalid')) {
        $('#Return_observation_c').addClass('is-valid');
    }
    if (!$('#dispatch_c').hasClass('is-invalid')) {
        $('#dispatch_c').addClass('is-valid');
    }
    if (!$('#dispatch_date_c').hasClass('is-invalid')) {
        $('#dispatch_date_c').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-client_id_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-client_id_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-client_branch_id_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-client_branch_id_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-sale_channel_id_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-sale_channel_id_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-transporter_id_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-transporter_id_c-container"]').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateOrderReturn() {
    $('#Return_observation_c').removeClass('is-valid');
    $('#dispatch_c').removeClass('is-valid');
    $('#dispatch_date_c').removeClass('is-valid');
    $('span[aria-labelledby="select2-client_id_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-client_branch_id_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-sale_channel_id_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-transporter_id_c-container"]').removeClass('is-valid');
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
    $('#Return_observation_c').removeClass('is-invalid');
    $('#dispatch_c').removeClass('is-invalid');
    $('#dispatch_date_c').removeClass('is-invalid');
    $('span[aria-labelledby="select2-client_id_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-client_branch_id_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-sale_channel_id_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-transporter_id_c-container"]').removeClass('is-invalid');
}

$('#dispatch_date_c').datetimepicker({
    format: 'YYYY-MM-DD'
});
