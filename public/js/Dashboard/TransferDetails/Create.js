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
            CreateTransferDetailsModalProduct(response.data);
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
    CreateTransferDetailsModalResetSelect('product_id_c');
    RemoveIsValidClassCreateTransferDetail();
    RemoveIsInvalidClassCreateTransferDetail();

    $('#quantity_c').val('');
    $('#quantity_c').attr('max', 0);
}

function CreateTransferDetailsModalResetSelect(id) {
    const select = $(`#${id}`);
    select.html('');
    const defaultOption = $('<option>', {
        value: '',
        text: 'Seleccione'
    });
    select.append(defaultOption);
    select.trigger('change');
    $('#quantity_c').attr('max', 0);
}

function CreateTransferDetailsModalProduct(products) {
    $.each(products, function(index, product) {
        $('#product_id_c').append(new Option(product.code, product.id, false, false));
    });
}

function CreateTransferDetailsModalProductGetColorToneSizes(select) {
    if($(select).val() == '') {
        CreateTransferDetailsModalResetSelect('size_id_c');
        CreateTransferDetailsModalResetSelect('color_id_tone_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Transfers/Details/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'product_id':  $(select).val()
            },
            success: function(response) {
                CreateTransferDetailsModalResetSelect('size_id_c');
                CreateTransferDetailsModalResetSelect('color_id_tone_id_c');
                CreateTransferDetailsModalColorTone(response.data.colors_tones);
                CreateTransferDetailsModalSizes(response.data.sizes);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateTransferDetailAjaxError(xhr);
            }
        });
    }
};

function CreateTransferDetailsModalColorTone(colors_tones) {
    colors_tones.forEach(color_tone => {
        let newOption = new Option(`${color_tone.color.name} - ${color_tone.tone.name}`, `${color_tone.color.id}-${color_tone.tone.id}`, false, false);
        $('#color_id_tone_id_c').append(newOption);
    });
}

function CreateTransferDetailsModalSizes(sizes) {
    sizes.forEach(size => {
        let newOption = new Option(size.name, size.id, false, false);
        $('#size_id_c').append(newOption);
    });
}

function CreateTransferDetailsModalColorToneSizesGetQuantity() {
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
                $('#quantity_c').attr('max', response.quantity);
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
                    'from_warehouse_id': $('#from_warehouse_id_c').val(),
                    'to_warehouse_id': $('#to_warehouse_id_c').val(),
                    'from_observation': $('#from_observation_c').val()
                },
                success: function (response) {
                    tableTransferDetails.ajax.reload();
                    CreateTransferDetailAjaxSuccess(response);
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
    if (!$(`span[aria-labelledby="select2-from_warehouse_id_c-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-from_warehouse_id_c-container"]`).addClass('is-valid');
    }
    if (!$(`span[aria-labelledby="select2-to_warehouse_id_c-container`).hasClass('is-invalid')) {
        $(`span[aria-labelledby="select2-to_warehouse_id_c-container"]`).addClass('is-valid');
    }
    if (!$('#from_observation_c').hasClass('is-invalid')) {
        $('#from_observation_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateTransferDetail() {
    $(`span[aria-labelledby="select2-from_warehouse_id_c-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-to_warehouse_id_c-container"]`).removeClass('is-valid');
    $('#from_observation_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateTransferDetail(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_c-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_c-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateTransferDetail() {
    $(`span[aria-labelledby="select2-from_warehouse_id_c-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-to_warehouse_id_c-container"]`).removeClass('is-invalid');
    $('#from_observation_c').removeClass('is-invalid');
}
