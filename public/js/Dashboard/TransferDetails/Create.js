function CreateTransferDetailModal() {
    $.ajax({
        url: `/Dashboard/Transfers/Details/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'from_warehouse_id': $('#ShowTransferButton').attr('data-from_warehouse_id')
        },
        success: function (response) {
            tableTransferDetails.ajax.reload();
            CreateTransferDetailModalCleaned();
            CreateTransferDetailModalProduct(response.data);
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
    $('#CreateTransferDetailModal').modal('hide');
    CreateTransferDetailModalResetSelect('product_id_c');
    RemoveIsValidClassCreateTransferDetail();
    RemoveIsInvalidClassCreateTransferDetail();

    $('#quantity_c').val('');
    $('#quantity_c').attr('max', 0);
    $('#message_quantity_c').text('');
}

function CreateTransferDetailModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
    $('#quantity_c').attr('max', 0);
}

function CreateTransferDetailModalProduct(products) {
    $.each(products, function(index, product) {
        $('#product_id_c').append(new Option(product.code, product.id, false, false));
    });
}

function CreateTransferDetailModalProductGetColorToneSizes(select) {
    if($(select).val() == '') {
        CreateTransferDetailModalResetSelect('size_id_c');
        CreateTransferDetailModalResetSelect('color_id_tone_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Transfers/Details/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'product_id':  $(select).val()
            },
            success: function(response) {
                CreateTransferDetailModalResetSelect('size_id_c');
                CreateTransferDetailModalResetSelect('color_id_tone_id_c');
                CreateTransferDetailModalColorTone(response.data.colors_tones);
                CreateTransferDetailModalSizes(response.data.sizes);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateTransferDetailAjaxError(xhr);
            }
        });
    }
};

function CreateTransferDetailModalColorTone(colors_tones) {
    colors_tones.forEach(color_tone => {
        $('#color_id_tone_id_c').append(new Option(`${color_tone.color.name} - ${color_tone.tone.name}`, `${color_tone.color.id}-${color_tone.tone.id}`, false, false));
    });
}

function CreateTransferDetailModalSizes(sizes) {
    sizes.forEach(size => {
        $('#size_id_c').append(new Option(size.name, size.id, false, false));
    });
}

function CreateTransferDetailModalColorToneSizesGetQuantity() {
    if($('#size_id_c').val() !== '' && $('#color_id_tone_id_c').val() !== '') {
        $.ajax({
            url: `/Dashboard/Transfers/Details/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'from_warehouse_id': $('#ShowTransferButton').attr('data-from_warehouse_id'),
                'product_id':  $('#product_id_c').val(),
                'color_id':  $('#color_id_tone_id_c').val().split('-')[0],
                'tone_id':  $('#color_id_tone_id_c').val().split('-')[1],
                'size_id':  $('#size_id_c').val(),
            },
            success: function(response) {
                $('#quantity_c').attr('max', response.data.quantity);
                $('#message_quantity_c').text(`${response.data.quantity} unidades disponibles.`);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateTransferDetailAjaxError(xhr);
            }
        });
    }
};

function CreateTransferDetail() {
    Swal.fire({
        title: '¿Desea guardar el detalle de la transferencia?',
        text: 'El detalle de la transferencia será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Transfers/Details/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'from_warehouse_id': $('#ShowTransferButton').attr('data-from_warehouse_id'),
                    'transfer_id': $('#ShowTransferButton').attr('data-id'),
                    'product_id': $('#product_id_c').val(),
                    'color_id':  $('#color_id_tone_id_c').val().split('-')[0],
                    'tone_id':  $('#color_id_tone_id_c').val().split('-')[1],
                    'size_id':  $('#size_id_c').val(),
                    'quantity': $('#quantity_c').val()
                },
                success: function (response) {
                    tableTransferDetails.ajax.reload();
                    CreateTransferDetailAjaxSuccess(response);
                    $('#CreateTransferDetailModal').modal('show');
                    $('#size_id_c').val('').trigger('change');
                    $('#quantity_c').val('');
                    $('#message_quantity_c').text('');
                    RemoveIsValidClassCreateTransferDetail();
                    RemoveIsInvalidClassCreateTransferDetail();
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableTransferDetails.ajax.reload();
                    CreateTransferDetailAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El detalle de la transferencia no fue creado.')
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
    if (!$(`span[aria-labelledby="select2-product_id_c-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-product_id_c-container"]`).addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-color_id_tone_id_c-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-color_id_tone_id_c-container"]`).addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-size_id_c-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-size_id_c-container"]`).addClass('is-valid');
    }
    if (!$('#quantity_c').hasClass('is-invalid')) {
        $('#quantity_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateTransferDetail() {
    $(`span[aria-labelledby="select2-product_id_c-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-color_id_tone_id_c-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-size_id_c-container"]`).removeClass('is-valid');
    $('#quantity_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateTransferDetail(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
    if(input == 'color_id' || input == 'tone_id') {
        if (!$(`span[aria-labelledby="select2-color_id_tone_id_c-container`).hasClass('is-valid')) {
            $(`span[aria-labelledby="select2-color_id_tone_id_c-container"]`).addClass('is-invalid');
        }
    }
    if (!$(`span[aria-labelledby="select2-${input}_c-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_c-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateTransferDetail() {
    $(`span[aria-labelledby="select2-product_id_c-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-color_id_tone_id_c-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-size_id_c-container"]`).removeClass('is-invalid');
    $('#quantity_c').removeClass('is-invalid');
}
