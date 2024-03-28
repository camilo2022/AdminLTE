function AssignPaymentWalletModal(id) {
    $.ajax({
        url: `/Dashboard/Wallets/AssignPayment/Query`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'order_dispatch_id': id
        },
        success: function (response) {
            AssignPaymentWalletModalCleaned(id);
            AssignPaymentWalletModalPaymentType(response.data.paymentTypes);
            AssignPaymentWalletAjaxSuccess(response);
            $('#AssignPaymentWalletModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            AssignPaymentWalletAjaxError(xhr);
        }
    });
}

function AssignPaymentWalletModalCleaned(order_dispatch_id) {
    AssignPaymentWalletModalResetSelect('payment_type_id_a');
    RemoveIsValidClassAssignPaymentWallet();
    RemoveIsInvalidClassAssignPaymentWallet();
    $('#AssignPaymentWalletButton').attr('data-id', order_dispatch_id)

    $('#value_a').val('');
    $('#reference_a').val('');
    $('#date_a').val('');
    $('#supports_c').val('');
    $('#supports_c').dropify().data('dropify').destroy();
    $('#supports_c').dropify().data('dropify').init();
}

function AssignPaymentWalletModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function AssignPaymentWalletModalPaymentType(paymentTypes) {
    paymentTypes.forEach(paymentType => {
        $('#payment_type_id_a').append(new Option(paymentType.name, paymentType.id, false, false));
    });
}

function AssignPaymentWalletModalPaymentTypeGetBank(select) {
    if($(select).val() == '') {
        AssignPaymentWalletModalResetSelect('bank_id_a');
        $('#div_bank_id_a').hide();
    } else {
        $.ajax({
            url: `/Dashboard/Wallets/AssignPayment/Query`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'order_dispatch_id': $('#AssignPaymentWalletButton').attr('data-id'),
                'payment_type_id': $(select).val()
            },
            success: function(response) {
                if(response.length == 0) {
                    $('#div_bank_id_a').hide();
                } else {
                    $('#div_bank_id_a').show();
                    AssignPaymentWalletModalResetSelect('bank_id_a');
                    AssignPaymentWalletModalBank(response.data);
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                AssignPaymentWalletAjaxError(xhr);
            }
        });
    }
}

function AssignPaymentWalletModalBank(banks) {
    banks.forEach(bank => {
        $('#bank_id_a').append(new Option(bank.name, bank.id, false, false));
    });
}

function AssignPaymentWallet() {
    let formData = new FormData();
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('order_dispatch_id', $('#AssignPaymentWalletButton').attr('data-id'));
    formData.append('value', $('#value_a').val());
    formData.append('reference', $('#reference_a').val());
    formData.append('date', $('#date_a').val());
    formData.append('payment_type_id', $('#payment_type_id_a').val());
    formData.append('bank_id', $('#bank_id_a').val());
    for (let i = 0; i < $('#supports_c')[0].files.length; i++) {
        formData.append('supports[]', $('#supports_c')[0].files[i]);
    }

    Swal.fire({
        title: '¿Desea asignar el pago a la orden de despacho?',
        text: 'El pago será asignado a la orden de despacho.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Wallets/AssignPayment`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    tableWallets.ajax.reload();
                    AssignPaymentWalletAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    AssignPaymentWalletAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El pago no fue asignado a la orden de despacho.')
        }
    });
}

function AssignPaymentWalletAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#AssignPaymentWalletModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#AssignPaymentWalletModal').modal('hide');
    }
}

function AssignPaymentWalletAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#AssignPaymentWalletModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#AssignPaymentWalletModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#AssignPaymentWalletModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassAssignPaymentWallet();
        RemoveIsInvalidClassAssignPaymentWallet();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassAssignPaymentWallet(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassAssignPaymentWallet();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#AssignPaymentWalletModal').modal('hide');
    }
}

function AddIsValidClassAssignPaymentWallet() {
    if (!$('#value_a').hasClass('is-invalid')) {
        $('#value_a').addClass('is-valid');
    }
    if (!$('#reference_a').hasClass('is-invalid')) {
        $('#reference_a').addClass('is-valid');
    }
    if (!$('#date_a').hasClass('is-invalid')) {
        $('#date_a').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-payment_type_id_a-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-payment_type_id_a-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-bank_id_a-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-bank_id_a-container"]').addClass('is-valid');
    }
}

function RemoveIsValidClassAssignPaymentWallet() {
    $('#value_a').removeClass('is-valid');
    $('#reference_a').removeClass('is-valid');
    $('#date_a').removeClass('is-valid');
    $('span[aria-labelledby="select2-payment_type_id_a-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-bank_id_a-container"]').removeClass('is-valid');
}

function AddIsInvalidClassAssignPaymentWallet(input) {
    if (!$(`#${input}_a`).hasClass('is-valid')) {
        $(`#${input}_a`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_a-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_a-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassAssignPaymentWallet() {
    $('#value_a').removeClass('is-invalid');
    $('#reference_a').removeClass('is-invalid');
    $('#date_a').removeClass('is-invalid');
    $('span[aria-labelledby="select2-payment_type_id_a-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-bank_id_a-container"]').removeClass('is-invalid');
}

$('#date_a').datetimepicker({
    format: 'YYYY-MM-DD HH:mm:ss'
});
