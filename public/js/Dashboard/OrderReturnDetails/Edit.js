function EditOrderReturnDetailModal(id) {
    $.ajax({
        url: `/Dashboard/Orders/Return/Details/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'order_return_id': $('#IndexOrderReturnDetail').attr('data-id'),
        },
        success: function (response) {
            $('#IndexOrderReturnDetail').trigger('click');
            EditOrderReturnDetailModalCleaned(response.data.orderReturnDetail);
            EditOrderReturnDetailModalProduct(response.data.products);
            EditOrderReturnDetailAjaxSuccess(response);
            $('#EditOrderReturnDetailModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            $('#IndexOrderReturnDetail').trigger('click');
            EditOrderReturnDetailAjaxError(xhr);
        }
    });
}

function EditOrderReturnDetailModalCleaned(orderReturnDetail) {
    EditOrderReturnDetailModalResetSelect('product_id_e');
    RemoveIsValidClassEditOrderReturnDetail();
    RemoveIsInvalidClassEditOrderReturnDetail();
    $('#observation_e').val(orderReturnDetail.observation);
    $('#sizes_e').html('');

    $('#EditOrderReturnDetailButton').attr('onclick', `EditOrderReturnDetail(${orderReturnDetail.id})`);
    $('#EditOrderReturnDetailButton').attr('data-id', orderReturnDetail.id);
    $('#EditOrderReturnDetailButton').attr('data-product_id', orderReturnDetail.order_detail.product_id);
    $('#EditOrderReturnDetailButton').attr('data-color_id', orderReturnDetail.order_detail.color_id);
    $('#EditOrderReturnDetailButton').attr('data-tone_id', orderReturnDetail.order_detail.tone_id);
    $('#EditOrderReturnDetailButton').attr('data-quantities', JSON.stringify(orderReturnDetail.order_return_detail_quantities));
}

function EditOrderReturnDetailModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function EditOrderReturnDetailModalProduct(products) {
    $.each(products, function(index, product) {
        $('#product_id_e').append(new Option(product.code, product.id, false, false));
    });

    let product_id = $('#EditOrderReturnDetailButton').attr('data-product_id');
    if(product_id != '') {
        $("#product_id_e").val(product_id).trigger('change');
        $('#EditOrderReturnDetailButton').attr('data-product_id', '');
    }
}

function EditOrderReturnDetailModalProductGetColorTone(select) {
    if($(select).val() == '') {
        $('#sizes_e').html('');
        EditOrderReturnDetailModalResetSelect('color_id_tone_id_e');
    } else {
        let id = $('#EditOrderReturnDetailButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Orders/Return/Details/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'order_return_id': $('#IndexOrderReturnDetail').attr('data-id'),
                'product_id':  $(select).val()
            },
            success: function(response) {
                EditOrderReturnDetailModalResetSelect('color_id_tone_id_e');
                EditOrderReturnDetailModalColorTone(response.data);
                $('#sizes_e').html('');
            },
            error: function(xhr, textStatus, errorThrown) {
                EditOrderReturnDetailAjaxError(xhr);
            }
        });
    }
};

function EditOrderReturnDetailModalColorTone(colors_tones) {
    colors_tones.forEach(color_tone => {
        $('#color_id_tone_id_e').append(new Option(`${color_tone.color.name} - ${color_tone.tone.name}`, `${color_tone.color.id}-${color_tone.tone.id}`, false, false));
    });

    let color_id = $('#EditOrderReturnDetailButton').attr('data-color_id');
    let tone_id = $('#EditOrderReturnDetailButton').attr('data-tone_id');
    if(color_id != '' && tone_id != '') {
        $("#color_id_tone_id_e").val(`${color_id}-${tone_id}`).trigger('change');
        $('#EditOrderReturnDetailButton').attr('data-color_id', '');
        $('#EditOrderReturnDetailButton').attr('data-tone_id', '');
    }
}

function EditOrderReturnDetailModalColorToneGetSizesQuantity() {
    if($('#color_id_tone_id_e').val() == '') {
        $('#sizes_e').html('');
    } else {
        let id = $('#EditOrderReturnDetailButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Orders/Return/Details/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'order_return_id': $('#IndexOrderReturnDetail').attr('data-id'),
                'product_id':  $('#product_id_e').val(),
                'color_id':  $('#color_id_tone_id_e').val().split('-')[0],
                'tone_id':  $('#color_id_tone_id_e').val().split('-')[1],
                'size_id':  $('#size_id_e').val(),
            },
            success: function(response) {
                toastr.info('Unidades por talla cargado, ingrese las cantidades.');
                EditOrderReturnDetailModalSizes(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditOrderReturnDetailAjaxError(xhr);
            }
        });
    }
}

function EditOrderReturnDetailModalSizes(sizes) {
    let inputs = '';
    $.each(sizes, function(index, size) {
        inputs += `<div class="form-group">
                        <label for="size_${size.size.id}_e">${size.size.name}</label>
                        <input type="number" class="form-control" id="size_${size.size.id}_e" name="size_${size.size.id}" data-size_id="${size.size.id}" value="0">
                        <small id="message_size_${size.size.id}_e">${size.quantity} unidades disponibles.</small>
                    </div>`;
    });
    $('#sizes_e').html(inputs);

    let quantities = $('#EditOrderReturnDetailButton').attr('data-quantities');
    if(quantities != '') {
        $.each(JSON.parse(quantities), function(index, quantity) {
            $(`#size_${quantity.order_detail_quantity.size_id}_e`).val(quantity.quantity);
        })
        $('#EditOrderReturnDetailButton').attr('data-quantities', '');
    }
}

function EditOrderReturnDetail(id) {
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
                url: `/Dashboard/Orders/Return/Details/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'order_return_id': $('#IndexOrderReturnDetail').attr('data-id'),
                    'product_id':  $('#product_id_e').val(),
                    'color_id':  $('#color_id_tone_id_e').val().split('-')[0],
                    'tone_id':  $('#color_id_tone_id_e').val().split('-')[1],
                    'order_return_detail_quantities': $('#sizes_e').find('div.form-group').map(function(index) {
                        return {
                            'quantity': $(this).find('input').val() == '' ? 0 : $(this).find('input').val(),
                            'size_id': $(this).find('input').attr('data-size_id')
                        };
                    }).get(),
                    'observation': $('#observation_e').val()
                },
                success: function (response) {
                    $('#IndexOrderReturnDetail').trigger('click');
                    EditOrderReturnDetailAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    $('#IndexOrderReturnDetail').trigger('click');
                    EditOrderReturnDetailAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El detalle del pedido no fue actualizado.')
        }
    });
}

function EditOrderReturnDetailAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#EditOrderReturnDetailModal').modal('hide');
    }

    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditOrderReturnDetailModal').modal('hide');
    }
}

function EditOrderReturnDetailAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderReturnDetailModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderReturnDetailModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderReturnDetailModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditOrderReturnDetail();
        RemoveIsInvalidClassEditOrderReturnDetail();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditOrderReturnDetail(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditOrderReturnDetail();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderReturnDetailModal').modal('hide');
    }
}

function AddIsValidClassEditOrderReturnDetail() {
    if (!$('#observation_e').hasClass('is-invalid')) {
        $('#observation_e').addClass('is-valid');
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

function RemoveIsValidClassEditOrderReturnDetail() {
    $('#observation_e').removeClass('is-valid');
    $(`span[aria-labelledby="select2-product_id_e-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-color_id_tone_id_e-container"]`).removeClass('is-valid');
    $('#sizes_e').find('input').each(function() {
        $(this).removeClass('is-valid');
    });
}

function AddIsInvalidClassEditOrderReturnDetail(input) {
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
        if(input === `order_return_detail_quantities.${index}.quantity`) {
            $(this).addClass('is-invalid');
        }
    });
}

function RemoveIsInvalidClassEditOrderReturnDetail() {
    $('#observation_e').removeClass('is-invalid');
    $(`span[aria-labelledby="select2-product_id_e-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-color_id_tone_id_e-container"]`).removeClass('is-invalid');
    $('#sizes_e').find('input').each(function() {
        $(this).removeClass('is-invalid');
    });
}
