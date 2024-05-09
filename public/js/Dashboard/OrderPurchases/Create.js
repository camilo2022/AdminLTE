function CreateOrderPurchaseModal() {
    $.ajax({
        url: `/Dashboard/Orders/Purchase/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            CreateOrderPurchaseModalCleaned();
            CreateOrderPurchaseModalWorkshop(response.data.workshops);
            CreateOrderPurchaseModalPaymentType(response.data.paymentTypes);
            CreateOrderPurchaseAjaxSuccess(response);
            $('#CreateOrderPurchaseModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            CreateOrderPurchaseAjaxError(xhr);
        }
    });
}

function CreateOrderPurchaseModalCleaned() {
    CreateOrderPurchaseModalResetSelect('workshop_id_c');
    RemoveIsValidClassCreateOrderPurchase();
    RemoveIsInvalidClassCreateOrderPurchase();

    $('#payment_types_c').empty();
    $('#seller_observation_c').val('');
}

function CreateOrderPurchaseModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function CreateOrderPurchaseModalWorkshop(workshops) {
    workshops.forEach(workshop => {
        $('#workshop_id_c').append(new Option(`${workshop.name} - ${workshop.document_number}`, workshop.id, false, false));
    });
}

function CreateOrderPurchaseModalPaymentType(paymentTypes) {
    paymentTypes.forEach(paymentType => {
        let check = `<div class="icheck-primary">
                        <input type="checkbox" id="payment_type_${paymentType.id}_c" name="payment_type_${paymentType.id}_c" data-id="${paymentType.id}">
                        <label for="payment_type_${paymentType.id}_c">${paymentType.name}</label>
                    </div>`;
        $('#payment_types_c').append(check);
    });
}

function CreateOrderPurchase() {
    Swal.fire({
        title: '¿Desea guardar la orden de compra?',
        text: 'La orden de compra será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Purchase/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'workshop_id': $('#workshop_id_c').val(),
                    'purchase_observation': $('#purchase_observation_c').val(),
                    'payment_type_ids': $('#payment_types_c input[type="checkbox"]:checked').map(function() {
                        return $(this).attr('data-id');
                    }).get()
                },
                success: function (response) {
                    window.location.href = response.data.url;
                    tableOrderPurchases.ajax.reload();
                    CreateOrderPurchaseAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    CreateOrderPurchaseAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La orden de compra no fue creado.')
        }
    });
}

function CreateOrderPurchaseAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#CreateOrderPurchaseModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateOrderPurchaseModal').modal('hide');
    }
}

function CreateOrderPurchaseAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderPurchaseModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderPurchaseModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderPurchaseModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateOrderPurchase();
        RemoveIsInvalidClassCreateOrderPurchase();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateOrderPurchase(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateOrderPurchase();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderPurchaseModal').modal('hide');
    }
}

function AddIsValidClassCreateOrderPurchase() {
    if (!$('#purchase_observation_c').hasClass('is-invalid')) {
        $('#purchase_observation_c').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-client_id_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-client_id_c-container"]').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateOrderPurchase() {
    $('#purchase_observation_c').removeClass('is-valid');
    $('span[aria-labelledby="select2-client_id_c-container"]').removeClass('is-valid');
}

function AddIsInvalidClassCreateOrderPurchase(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_c-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_c-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateOrderPurchase() {
    $('#purchase_observation_c').removeClass('is-invalid');
    $('span[aria-labelledby="select2-client_id_c-container"]').removeClass('is-invalid');
}
