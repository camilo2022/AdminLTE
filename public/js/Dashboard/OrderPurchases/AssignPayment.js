function AssignPaymentOrderSellerModal(id) {
    $.ajax({
        url: `/Dashboard/Orders/Seller/AssignPayment/Query`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'order_id': id
        },
        success: function (response) {
            AssignPaymentOrderSellerModalCleaned();
            AssignPaymentOrderSellerModalPaymentType(response.data.paymentTypes);
            AssignPaymentOrderSellerAjaxSuccess(response);
            $('#AssignPaymentOrderSellerModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            AssignPaymentOrderSellerAjaxError(xhr);
        }
    });
}

function AssignPaymentOrderSellerModalCleaned() {
    AssignPaymentOrderSellerModalResetSelect('payment_type_id_a');
    RemoveIsValidClassAssignPaymentOrderSeller();
    RemoveIsInvalidClassAssignPaymentOrderSeller();

    $('#value_a').val('');
    $('#reference_a').val('');
    $('#date_a').val('');
    $('#supports_c').val('');
    $('#supports_c').dropify().data('dropify').destroy();
    $('#supports_c').dropify().data('dropify').init();
}

function AssignPaymentOrderSellerModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function AssignPaymentOrderSellerModalPaymentType(paymentTypes) {
    paymentTypes.forEach(paymentType => {
        $('#payment_type_id_a').append(new Option(paymentType.name, paymentType.id, false, false));
    });
}

function AssignPaymentOrderSellerModalPaymentTypeGetBank(select) {
    if($(select).val() == '') {
        AssignPaymentOrderSellerModalResetSelect('bank_id_a');
        $('#div_bank_id_a').hide();
    } else {
        $.ajax({
            url: `/Dashboard/Orders/Seller/AssignPayment/Query`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'order_id': $('#IndexOrderSellerDetail').attr('data-id'),
                'payment_type_id': $(select).val()
            },
            success: function(response) {
                if(response.length == 0) {
                    $('#div_bank_id_a').hide();
                } else {
                    $('#div_bank_id_a').show();
                    AssignPaymentOrderSellerModalResetSelect('bank_id_aphp ');
                    AssignPaymentOrderSellerModalBank(response.data);
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                AssignPaymentOrderSellerAjaxError(xhr);
            }
        });
    }
}

function AssignPaymentOrderSellerModalBank(banks) {
    banks.forEach(bank => {
        $('#bank_id_a').append(new Option(bank.name, bank.id, false, false));
    });
}

function AssignPaymentOrderSeller() {
    let formData = new FormData();
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('order_id', $('#IndexOrderSellerDetail').attr('data-id'));
    formData.append('value', $('#value_a').val());
    formData.append('reference', $('#reference_a').val());
    formData.append('date', $('#date_a').val());
    formData.append('payment_type_id', $('#payment_type_id_a').val());
    formData.append('bank_id', $('#bank_id_a').val());
    for (let i = 0; i < $('#supports_c')[0].files.length; i++) {
        formData.append('supports[]', $('#supports_c')[0].files[i]);
    }

    Swal.fire({
        title: '¿Desea asignar el pago al pedido?',
        text: 'El pago será asignado al pedido.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Seller/AssignPayment`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    tableOrderSellerPayments.ajax.reload();
                    AssignPaymentOrderSellerAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    AssignPaymentOrderSellerAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El pago no fue asignado al pedido.')
        }
    });
}

function AssignPaymentOrderSellerAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#AssignPaymentOrderSellerModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#AssignPaymentOrderSellerModal').modal('hide');
    }
}

function AssignPaymentOrderSellerAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#AssignPaymentOrderSellerModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#AssignPaymentOrderSellerModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#AssignPaymentOrderSellerModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassAssignPaymentOrderSeller();
        RemoveIsInvalidClassAssignPaymentOrderSeller();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassAssignPaymentOrderSeller(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassAssignPaymentOrderSeller();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#AssignPaymentOrderSellerModal').modal('hide');
    }
}

function AddIsValidClassAssignPaymentOrderSeller() {
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

function RemoveIsValidClassAssignPaymentOrderSeller() {
    $('#value_a').removeClass('is-valid');
    $('#reference_a').removeClass('is-valid');
    $('#date_a').removeClass('is-valid');
    $('span[aria-labelledby="select2-payment_type_id_a-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-bank_id_a-container"]').removeClass('is-valid');
}

function AddIsInvalidClassAssignPaymentOrderSeller(input) {
    if (!$(`#${input}_a`).hasClass('is-valid')) {
        $(`#${input}_a`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_a-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_a-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassAssignPaymentOrderSeller() {
    $('#value_a').removeClass('is-invalid');
    $('#reference_a').removeClass('is-invalid');
    $('#date_a').removeClass('is-invalid');
    $('span[aria-labelledby="select2-payment_type_id_a-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-bank_id_a-container"]').removeClass('is-invalid');
}

$('#date_a').datetimepicker({
    format: 'YYYY-MM-DD HH:mm:ss'
});
