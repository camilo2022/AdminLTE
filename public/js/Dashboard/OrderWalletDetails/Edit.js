function EditOrderWalletDetailModal(id) {
    $.ajax({
        url: `/Dashboard/Orders/Wallet/Details/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $('#IndexOrderWalletDetail').trigger('click');
            EditOrderWalletDetailModalCleaned(response.data.orderDetail);
            EditOrderWalletDetailModalProduct(response.data.products);
            EditOrderWalletDetailAjaxSuccess(response);
            $('#EditOrderWalletDetailModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            $('#IndexOrderWalletDetail').trigger('click');
            EditOrderWalletDetailAjaxError(xhr);
        }
    });
}

function EditOrderWalletDetailModalCleaned(orderDetail) {
    EditOrderWalletDetailModalResetSelect('product_id_e');
    RemoveIsValidClassEditOrderWalletDetail();
    RemoveIsInvalidClassEditOrderWalletDetail();
    $('#seller_observation_e').val(orderDetail.seller_observation);
    $('#sizes_e').html('');

    $('#EditOrderWalletDetailButton').attr('onclick', `EditOrderWalletDetail(${orderDetail.id})`);
    $('#EditOrderWalletDetailButton').attr('data-id', orderDetail.id);
    $('#EditOrderWalletDetailButton').attr('data-product_id', orderDetail.product_id);
    $('#EditOrderWalletDetailButton').attr('data-color_id', orderDetail.color_id);
    $('#EditOrderWalletDetailButton').attr('data-tone_id', orderDetail.tone_id);
    $('#EditOrderWalletDetailButton').attr('data-quantities', JSON.stringify(orderDetail.order_detail_quantities));
}

function EditOrderWalletDetailModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function EditOrderWalletDetailModalProduct(products) {
    $.each(products, function(index, product) {
        $('#product_id_e').append(new Option(product.code, product.id, false, false));
    });

    let product_id = $('#EditOrderWalletDetailButton').attr('data-product_id');
    if(product_id != '') {
        $("#product_id_e").val(product_id).trigger('change');
        $('#EditOrderWalletDetailButton').attr('data-product_id', '');
    }
}

function EditOrderWalletDetailModalProductGetColorTone(select) {
    if($(select).val() == '') {
        $('#sizes_e').html('');
        EditOrderWalletDetailModalResetSelect('color_id_tone_id_e');
    } else {
        let id = $('#EditOrderWalletDetailButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Orders/Wallet/Details/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'product_id':  $(select).val()
            },
            success: function(response) {
                EditOrderWalletDetailModalResetSelect('color_id_tone_id_e');
                EditOrderWalletDetailModalColorTone(response.data.colors_tones);
                $('#sizes_e').html('');
            },
            error: function(xhr, textStatus, errorThrown) {
                EditOrderWalletDetailAjaxError(xhr);
            }
        });
    }
};

function EditOrderWalletDetailModalColorTone(colors_tones) {
    colors_tones.forEach(color_tone => {
        $('#color_id_tone_id_e').append(new Option(`${color_tone.color.name} - ${color_tone.tone.name}`, `${color_tone.color.id}-${color_tone.tone.id}`, false, false));
    });

    let color_id = $('#EditOrderWalletDetailButton').attr('data-color_id');
    let tone_id = $('#EditOrderWalletDetailButton').attr('data-tone_id');
    if(color_id != '' && tone_id != '') {
        $("#color_id_tone_id_e").val(`${color_id}-${tone_id}`).trigger('change');
        $('#EditOrderWalletDetailButton').attr('data-color_id', '');
        $('#EditOrderWalletDetailButton').attr('data-tone_id', '');
    }
}

function EditOrderWalletDetailModalColorToneGetSizesQuantity() {
    if($('#color_id_tone_id_e').val() == '') {
        $('#sizes_e').html('');
    } else {
        let id = $('#EditOrderWalletDetailButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Orders/Wallet/Details/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'product_id':  $('#product_id_e').val(),
                'color_id':  $('#color_id_tone_id_e').val().split('-')[0],
                'tone_id':  $('#color_id_tone_id_e').val().split('-')[1]
            },
            success: function(response) {
                EditOrderWalletDetailModalSizes(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditOrderWalletDetailAjaxError(xhr);
            }
        });
    }
}

function EditOrderWalletDetailModalSizes(sizes) {
    let inputs = '';
    $.each(sizes, function(index, size) {
        inputs += `<div class="form-group">
                        <label for="size_${size.size.id}_e">${size.size.name}</label>
                        <input type="number" class="form-control" id="size_${size.size.id}_e" name="size_${size.size.id}" data-size_id="${size.size.id}" value="0">
                        <small id="message_size_${size.size.id}_e">${size.quantity} unidades disponibles.</small>
                    </div>`;
    });
    $('#sizes_e').html(inputs);

    let quantities = $('#EditOrderWalletDetailButton').attr('data-quantities');
    if(quantities != '') {
        $.each(JSON.parse(quantities), function(index, quantity) {
            $(`#size_${quantity.size_id}_e`).val(quantity.quantity);
        })
        $('#EditOrderWalletDetailButton').attr('data-quantities', '');
    }
}

function EditOrderWalletDetail(id) {
    Swal.fire({
        title: '¿Desea actualizar el detalle del pedido?',
        text: 'El detalle del pedido se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Wallet/Details/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'order_id': $('#IndexOrderWalletDetail').attr('data-id'),
                    'product_id':  $('#product_id_e').val(),
                    'color_id':  $('#color_id_tone_id_e').val().split('-')[0],
                    'tone_id':  $('#color_id_tone_id_e').val().split('-')[1],
                    'order_detail_quantities': $('#sizes_e').find('div.form-group').map(function(index) {
                        return {
                            'quantity': $(this).find('input').val() == '' ? 0 : $(this).find('input').val(),
                            'size_id': $(this).find('input').attr('data-size_id')
                        };
                    }).get(),
                    'seller_observation': $('#seller_observation_e').val()
                },
                success: function (response) {
                    $('#IndexOrderWalletDetail').trigger('click');
                    EditOrderWalletDetailAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    $('#IndexOrderWalletDetail').trigger('click');
                    EditOrderWalletDetailAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El detalle del pedido no fue actualizado.')
        }
    });
}

function EditOrderWalletDetailAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#EditOrderWalletDetailModal').modal('hide');
    }

    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditOrderWalletDetailModal').modal('hide');
    }
}

function EditOrderWalletDetailAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderWalletDetailModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderWalletDetailModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderWalletDetailModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditOrderWalletDetail();
        RemoveIsInvalidClassEditOrderWalletDetail();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditOrderWalletDetail(field);
            $.each(messages, function(index, message) {
                if(field === 'quota_available') {
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Cupo disponible insuficiente',
                        body: message
                    });
                } else {
                    toastr.error(message);
                }
            });
        });
        AddIsValidClassEditOrderWalletDetail();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderWalletDetailModal').modal('hide');
    }
}

function AddIsValidClassEditOrderWalletDetail() {
    if (!$('#seller_observation_e').hasClass('is-invalid')) {
        $('#seller_observation_e').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-product_id_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-product_id_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-color_id_tone_id_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-color_id_tone_id_e-container"]').addClass('is-valid');
    }
    $('#sizes_e').find('input').each(function() {
        if (!$(this).hasClass('is-invalid')) {
            $(this).addClass('is-valid');
        }
    });
}

function RemoveIsValidClassEditOrderWalletDetail() {
    $('#seller_observation_e').removeClass('is-valid');
    $(`span[aria-labelledby="select2-product_id_e-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-color_id_tone_id_e-container"]`).removeClass('is-valid');
    $('#sizes_e').find('input').each(function() {
        $(this).removeClass('is-valid');
    });
}

function AddIsInvalidClassEditOrderWalletDetail(input) {
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
    $('#sizes_e').find('input').each(function(index) {
        if(input === `order_detail_quantities.${index}.quantity`) {
            $(this).addClass('is-invalid');
        }
    });
}

function RemoveIsInvalidClassEditOrderWalletDetail() {
    $('#seller_observation_e').removeClass('is-invalid');
    $(`span[aria-labelledby="select2-product_id_e-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-color_id_tone_id_e-container"]`).removeClass('is-invalid');
    $('#sizes_e').find('input').each(function() {
        $(this).removeClass('is-invalid');
    });
}
