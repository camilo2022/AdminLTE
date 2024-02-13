function EditSaleChannelModal(id) {
    $.ajax({
        url: `/Dashboard/SaleChannels/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            tableSaleChannels.ajax.reload();
            EditSaleChannelModalCleaned(response.data);
            EditSaleChannelAjaxSuccess(response);
            $('#EditSaleChannelModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tableSaleChannels.ajax.reload();
            EditSaleChannelAjaxError(xhr);
        }
    });
}

function EditSaleChannelModalCleaned(saleChannel) {
    RemoveIsValidClassEditSaleChannel();
    RemoveIsInvalidClassEditSaleChannel();

    $('#EditSaleChannelButton').attr('onclick', `EditSaleChannel(${saleChannel.id}, ${saleChannel.require_verify_wallet})`);

    $("#name_e").val(saleChannel.name);
}

function EditSaleChannel(id, require_verify_wallet) {
    Swal.fire({
        title: '¿Desea actualizar el canal de venta?',
        text: 'El canal de venta se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
        html: `<div class="icheck-primary"><input type="checkbox" id="require_verify_wallet_e" name="require_verify_wallet_e" ${require_verify_wallet ? 'checked' : ''}><label for="require_verify_wallet_e">¿Requiere verificacion de cartera el pedido?</label></div>`,
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/SaleChannels/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_e').val(),
                    'require_verify_wallet': $('#require_verify_wallet_e').is(':checked')
                },
                success: function (response) {
                    tableSaleChannels.ajax.reload();
                    EditSaleChannelAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableSaleChannels.ajax.reload();
                    EditSaleChannelAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El canal de venta no fue actualizado.')
        }
    });
}

function EditSaleChannelAjaxSuccess(response) {
    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditSaleChannelModal').modal('hide');
    }
}

function EditSaleChannelAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditSaleChannelModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditSaleChannelModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditSaleChannelModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditSaleChannel();
        RemoveIsInvalidClassEditSaleChannel();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditSaleChannel(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditSaleChannel();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditSaleChannelModal').modal('hide');
    }
}

function AddIsValidClassEditSaleChannel() {
    if (!$('#name_e').hasClass('is-invalid')) {
        $('#name_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditSaleChannel() {
    $('#name_e').removeClass('is-valid');
}

function AddIsInvalidClassEditSaleChannel(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditSaleChannel() {
    $('#name_e').removeClass('is-invalid');
}
