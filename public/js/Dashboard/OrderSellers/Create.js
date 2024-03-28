function CreateOrderSellerModal() {
    $.ajax({
        url: `/Dashboard/Orders/Seller/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            CreateOrderSellerModalCleaned();
            CreateOrderSellerModalClient(response.data.clients);
            CreateOrderSellerModalTransporter(response.data.transporters);
            CreateOrderSellerModalSaleChannel(response.data.saleChannels);
            CreateOrderSellerModalPaymentType(response.data.paymentTypes);
            CreateOrderSellerAjaxSuccess(response);
            $('#CreateOrderSellerModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            CreateOrderSellerAjaxError(xhr);
        }
    });
}

function CreateOrderSellerModalCleaned() {
    CreateOrderSellerModalResetSelect('client_id_c');
    CreateOrderSellerModalResetSelect('transporter_id_c');
    CreateOrderSellerModalResetSelect('sale_channel_id_c');
    RemoveIsValidClassCreateOrderSeller();
    RemoveIsInvalidClassCreateOrderSeller();

    $('#payment_types_c').empty();
    $('#dispatch_c').val('').trigger('change');
    $('#seller_observation_c').val('');
}

function CreateOrderSellerModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function CreateOrderSellerModalClient(clients) {
    clients.forEach(client => {
        $('#client_id_c').append(new Option(`${client.name} - ${client.document_number}`, client.id, false, false));
    });
}

function CreateOrderSellerModalClientGetClientBranch(select) {
    if($(select).val() == '') {
        CreateOrderSellerModalResetSelect('client_branch_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Orders/Seller/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'client_id':  $(select).val()
            },
            success: function(response) {
                CreateOrderSellerModalResetSelect('client_branch_id_c');
                CreateOrderSellerModalClienteBranch(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateOrderSellerAjaxError(xhr);
            }
        });
    }
}

function CreateOrderSellerModalClienteBranch(clientBranches) {
    clientBranches.forEach(clientBranch => {
        $('#client_branch_id_c').append(new Option(`${clientBranch.name} - ${clientBranch.code}`, clientBranch.id, false, false));
    });
}

function CreateOrderSellerModalTransporter(transporters) {
    transporters.forEach(transporter => {
        $('#transporter_id_c').append(new Option(transporter.name, transporter.id, false, false));
    });
}

function CreateOrderSellerModalSaleChannel(saleChannels) {
    saleChannels.forEach(saleChannel => {
        $('#sale_channel_id_c').append(new Option(saleChannel.name, saleChannel.id, false, false));
    });
}

function CreateOrderSellerModalPaymentType(paymentTypes) {
    paymentTypes.forEach(paymentType => {
        let check = `<div class="icheck-primary">
                        <input type="checkbox" id="payment_type_${paymentType.id}_c" name="payment_type_${paymentType.id}_c" data-id="${paymentType.id}">
                        <label for="payment_type_${paymentType.id}_c">${paymentType.name}</label>
                    </div>`;
        $('#payment_types_c').append(check);
    });
}

function CreateOrderSellerModalDispatchGetDispatchDate(select) {
    if($(select).val() == '' || $(select).val() == 'De inmediato') {
        $('#div_dispatch_date_c').hide();
        $('#dispatch_date_c').val(new Date().toISOString().split('T')[0]);
    } else {
        $('#div_dispatch_date_c').show();
    }
}

function CreateOrderSeller() {
    Swal.fire({
        title: '¿Desea guardar el pedido?',
        text: 'El pedido será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Seller/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'client_id': $('#client_id_c').val(),
                    'client_branch_id': $('#client_branch_id_c').val(),
                    'transporter_id': $('#transporter_id_c').val(),
                    'sale_channel_id': $('#sale_channel_id_c').val(),
                    'seller_observation': $('#seller_observation_c').val(),
                    'dispatch': $('#dispatch_c').val(),
                    'dispatch_date': $('#dispatch_c').val() == 'De inmediato' ? new Date().toISOString().split('T')[0] : $('#dispatch_date_c').val(),
                    'payment_type_ids': $('#payment_types_c input[type="checkbox"]:checked').map(function() {
                        return $(this).attr('data-id');
                    }).get()
                },
                success: function (response) {
                    window.location.href = response.data.url;
                    tableOrderSellers.ajax.reload();
                    CreateOrderSellerAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    CreateOrderSellerAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El pedido no fue creado.')
        }
    });
}

function CreateOrderSellerAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#CreateOrderSellerModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateOrderSellerModal').modal('hide');
    }
}

function CreateOrderSellerAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderSellerModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderSellerModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderSellerModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateOrderSeller();
        RemoveIsInvalidClassCreateOrderSeller();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateOrderSeller(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateOrderSeller();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderSellerModal').modal('hide');
    }
}

function AddIsValidClassCreateOrderSeller() {
    if (!$('#seller_observation_c').hasClass('is-invalid')) {
        $('#seller_observation_c').addClass('is-valid');
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

function RemoveIsValidClassCreateOrderSeller() {
    $('#seller_observation_c').removeClass('is-valid');
    $('#dispatch_c').removeClass('is-valid');
    $('#dispatch_date_c').removeClass('is-valid');
    $('span[aria-labelledby="select2-client_id_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-client_branch_id_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-sale_channel_id_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-transporter_id_c-container"]').removeClass('is-valid');
}

function AddIsInvalidClassCreateOrderSeller(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_c-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_c-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateOrderSeller() {
    $('#seller_observation_c').removeClass('is-invalid');
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
