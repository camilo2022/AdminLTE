function CreateSaleChannelModal() {
    $.ajax({
        url: `/Dashboard/SaleChannels/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            CreateSaleChannelModalCleaned();
            CreateSaleChannelAjaxSuccess(response);
            $('#CreateSaleChannelModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            CreateSaleChannelAjaxError(xhr);
        }
    });
}

function CreateSaleChannelModalCleaned() {
    RemoveIsValidClassCreateSaleChannel();
    RemoveIsInvalidClassCreateSaleChannel();

    $('#name_c').val('');
}

function CreateSaleChannel() {
    Swal.fire({
        title: '¿Desea guardar el canal de venta?',
        text: 'El canal de venta será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
        html: '<div class="icheck-primary"><input type="checkbox" id="require_verify_wallet_c" name="require_verify_wallet_c"><label for="require_verify_wallet_c">¿Requiere verificacion de cartera el pedido?</label></div>',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/SaleChannels/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                    'require_verify_wallet': $('#require_verify_wallet_c').is(':checked')
                },
                success: function (response) {
                    tableSaleChannels.ajax.reload();
                    CreateSaleChannelAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    CreateSaleChannelAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El canal de venta no fue creado.')
        }
    });
}

function CreateSaleChannelAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#CreateSaleChannelModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateSaleChannelModal').modal('hide');
    }
}

function CreateSaleChannelAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateSaleChannelModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateSaleChannelModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateSaleChannelModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateSaleChannel();
        RemoveIsInvalidClassCreateSaleChannel();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateSaleChannel(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateSaleChannel();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateSaleChannelModal').modal('hide');
    }
}

function AddIsValidClassCreateSaleChannel() {
    if (!$('#name_c').hasClass('is-invalid')) {
        $('#name_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateSaleChannel() {
    $('#name_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateSaleChannel(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateSaleChannel() {
    $('#name_c').removeClass('is-invalid');
}
