function EditTransferDetailModal(id) {
    $.ajax({
        url: `/Dashboard/Transfers/Details/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            tableTransferDetails.ajax.reload();
            EditTransferDetailModalCleaned(response.data);
            EditTransferDetailModalProduct(response.data.product);
            EditTransferDetailModalColorTone(response.data);
            EditTransferDetailModalSizes(response.data.size);
            EditTransferDetailAjaxSuccess(response);
            $('#EditTransferDetailModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tableTransferDetails.ajax.reload();
            EditTransferDetailAjaxError(xhr);
        }
    });
}

function EditTransferDetailModalCleaned(transferDetail) {
    RemoveIsValidClassEditTransferDetail();
    RemoveIsInvalidClassEditTransferDetail();

    $('#EditTransferDetailButton').attr('onclick', `EditTransferDetail(${transferDetail.id})`);
    $('#EditTransferDetailButton').attr('data-id', transferDetail.id);

    $('#quantity_e').val(transferDetail.quantity);
    $('#quantity_e').attr('max', 0);
    $('#message_quantity_e').text('');
}

function EditTransferDetailModalProduct(product) {
    $('#product_id_e').html('');
    $('#product_id_e').append(new Option(product.code, product.id, false, false));
    $('#product_id_e').val(product.id).trigger('change');
}

function EditTransferDetailModalColorTone(color_tone) {
    $('#color_id_tone_id_e').html('');
    $('#color_id_tone_id_e').append(new Option(`${color_tone.color.name} - ${color_tone.tone.name}`, `${color_tone.color.id}-${color_tone.tone.id}`, false, false));
    $('#color_id_tone_id_e').val(`${color_tone.color.id}-${color_tone.tone.id}`).trigger('change');
}

function EditTransferDetailModalSizes(size) {
    $('#size_id_e').html('');
    $('#size_id_e').append(new Option(size.name, size.id, false, false));
    $('#size_id_e').val(size.id).trigger('change');
}

function EditTransferDetailModalColorToneSizesGetQuantity() {
    if($('#product_id_e').val() !== '' && $('#size_id_e').val() !== '' && $('#color_id_tone_id_e').val() !== '') {
        let id = $('#EditTransferDetailButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Transfers/Details/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'from_warehouse_id': $('#ShowTransferButton').attr('data-from_warehouse_id'),
                'product_id':  $('#product_id_e').val(),
                'color_id':  $('#color_id_tone_id_e').val().split('-')[0],
                'tone_id':  $('#color_id_tone_id_e').val().split('-')[1],
                'size_id':  $('#size_id_e').val(),
            },
            success: function(response) {
                $('#quantity_e').attr('max', response.data.quantity);
                $('#message_quantity_e').text(`${parseInt($('#quantity_e').val()) + response.data.quantity} unidades disponibles.`);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditTransferDetailAjaxError(xhr);
            }
        });
    }
};

function EditTransferDetail(id) {
    Swal.fire({
        title: '¿Desea actualizar el detalle de la transferencia?',
        text: 'El detalle de la transferencia será actualizado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Transfers/Details/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'from_warehouse_id': $('#ShowTransferButton').attr('data-from_warehouse_id'),
                    'transfer_id': $('#ShowTransferButton').attr('data-id'),
                    'product_id': $('#product_id_e').val(),
                    'color_id':  $('#color_id_tone_id_e').val().split('-')[0],
                    'tone_id':  $('#color_id_tone_id_e').val().split('-')[1],
                    'size_id':  $('#size_id_e').val(),
                    'quantity': $('#quantity_e').val()
                },
                success: function (response) {
                    tableTransferDetails.ajax.reload();
                    EditTransferDetailAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableTransferDetails.ajax.reload();
                    EditTransferDetailAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El detalle de la transferencia no fue actualizado.')
        }
    });
}

function EditTransferDetailAjaxSuccess(response) {
    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditTransferDetailModal').modal('hide');
    }
}

function EditTransferDetailAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditTransferDetailModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditTransferDetailModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditTransferDetailModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditTransferDetail();
        RemoveIsInvalidClassEditTransferDetail();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditTransferDetail(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditTransferDetail();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditTransferDetailModal').modal('hide');
    }
}

function AddIsValidClassEditTransferDetail() {
    if (!$(`span[aria-labelledby="select2-product_id_e-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-product_id_e-container"]`).addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-color_id_tone_id_e-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-color_id_tone_id_e-container"]`).addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-size_id_e-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-size_id_e-container"]`).addClass('is-valid');
    }
    if (!$('#quantity_e').hasClass('is-invalid')) {
        $('#quantity_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditTransferDetail() {
    $(`span[aria-labelledby="select2-product_id_e-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-color_id_tone_id_e-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-size_id_e-container"]`).removeClass('is-valid');
    $('#quantity_e').removeClass('is-valid');
}

function AddIsInvalidClassEditTransferDetail(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
    if(input == 'color_id' || input == 'tone_id') {
        if (!$(`span[aria-labelledby="select2-color_id_tone_id_e-container`).hasClass('is-valid')) {
            $(`span[aria-labelledby="select2-color_id_tone_id_e-container"]`).addClass('is-invalid');
        }
    }
    if (!$(`span[aria-labelledby="select2-${input}_e-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_e-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditTransferDetail() {
    $(`span[aria-labelledby="select2-product_id_e-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-color_id_tone_id_e-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-size_id_e-container"]`).removeClass('is-invalid');
    $('#quantity_e').removeClass('is-invalid');
}
