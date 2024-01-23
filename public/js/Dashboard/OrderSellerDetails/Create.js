function CreateOrderSellerDetailModal() {
    $.ajax({
        url: `/Dashboard/Orders/Seller/Details/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $('#IndexOrderSellerDetail').trigger('click');
            CreateOrderSellerDetailModalCleaned();
            CreateOrderSellerDetailModalProduct(response.data);
            CreateOrderSellerDetailAjaxSuccess(response);
            $('#CreateOrderSellerDetailModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            $('#IndexOrderSellerDetail').trigger('click');
            CreateOrderSellerDetailAjaxError(xhr);
        }
    });
}

function CreateOrderSellerDetailModalCleaned() {
    CreateOrderSellerDetailModalResetSelect('product_id_c');
    RemoveIsValidClassCreateOrderSellerDetail();
    RemoveIsInvalidClassCreateOrderSellerDetail();
    $('#sizes_c').html('');
}

function CreateOrderSellerDetailModalResetSelect(id) {
    $(`#${id}`).html('');
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function CreateOrderSellerDetailModalProduct(products) {
    $.each(products, function(index, product) {
        $('#product_id_c').append(new Option(product.code, product.id, false, false));
    });
}

function CreateOrderSellerDetailModalProductGetColorTone(select) {
    if($(select).val() == '') {
        $('#sizes_c').html('');
        CreateOrderSellerDetailModalResetSelect('color_id_tone_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Orders/Seller/Details/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'product_id':  $(select).val()
            },
            success: function(response) {
                CreateOrderSellerDetailModalResetSelect('color_id_tone_id_c');
                CreateOrderSellerDetailModalColorTone(response.data.colors_tones);
                $('#sizes_c').html('');
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateOrderSellerDetailAjaxError(xhr);
            }
        });
    }
};

function CreateOrderSellerDetailModalColorTone(colors_tones) {
    colors_tones.forEach(color_tone => {
        $('#color_id_tone_id_c').append(new Option(`${color_tone.color.name} - ${color_tone.tone.name}`, `${color_tone.color.id}-${color_tone.tone.id}`, false, false));
    });
}

function CreateOrderSellerDetailModalColorToneGetSizesQuantity() {
    if($('#color_id_tone_id_c').val() == '') {
        $('#sizes_c').html('');
    } else {
        $.ajax({
            url: `/Dashboard/Orders/Seller/Details/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'product_id':  $('#product_id_c').val(),
                'color_id':  $('#color_id_tone_id_c').val().split('-')[0],
                'tone_id':  $('#color_id_tone_id_c').val().split('-')[1],
                'size_id':  $('#size_id_c').val(),
            },
            success: function(response) {
                CreateOrderSellerDetailModalSizes(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateOrderSellerDetailAjaxError(xhr);
            }
        });
    }
}

function CreateOrderSellerDetailModalSizes(sizes) {
    let inputs = '';
    $.each(sizes, function(index, size) {
        inputs += `<div class="form-group">
                        <label for="size_${size.size.id}_c">${size.size.name}</label>
                        <input type="number" class="form-control" id="size_${size.size.id}_c" name="size_${size.size.id}" data-size_id="${size.size.id}" value="0">
                        <small id="message_size_${size.size.id}_c">${size.quantity} unidades disponibles.</small>
                    </div>`;
    });
    $('#sizes_c').html(inputs);
}

function CreateOrderSellerDetail() {
    Swal.fire({
        title: '¿Desea guardar el detalle del pedido?',
        text: 'El detalle del pedido será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Seller/Details/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'order_id': $('#IndexOrderSellerDetail').attr('data-id'),
                    'product_id':  $('#product_id_c').val(),
                    'color_id':  $('#color_id_tone_id_c').val().split('-')[0],
                    'tone_id':  $('#color_id_tone_id_c').val().split('-')[1],
                    'order_detail_quantities': $('#sizes_c').find('div.form-group').map(function(index) {
                        return {
                            'quantity': $(this).find('input').val() == '' ? 0 : $(this).find('input').val(),
                            'size_id': $(this).find('input').attr('data-size_id')
                        };
                    }).get(),
                    'seller_observation': $('#seller_observation_c').val()
                },
                success: function (response) {
                    $('#IndexOrderSellerDetail').trigger('click');
                    CreateOrderSellerDetailAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    $('#IndexOrderSellerDetail').trigger('click');
                    CreateOrderSellerDetailAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El detalle del pedido no fue creado.')
        }
    });
}

function CreateOrderSellerDetailAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#CreateOrderSellerDetailModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateOrderSellerDetailModal').modal('hide');
    }
}

function CreateOrderSellerDetailAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderSellerDetailModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderSellerDetailModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderSellerDetailModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateOrderSellerDetail();
        RemoveIsInvalidClassCreateOrderSellerDetail();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateOrderSellerDetail(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateOrderSellerDetail();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderSellerDetailModal').modal('hide');
    }
}

function AddIsValidClassCreateOrderSellerDetail() {
    if (!$('#seller_observation_c').hasClass('is-invalid')) {
        $('#seller_observation_c').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-product_id_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-product_id_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-color_id_tone_id_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-color_id_tone_id_c-container"]').addClass('is-valid');
    }
    $('#sizes_c').find('input').each(function() {
        if (!$(this).hasClass('is-invalid')) {
            $(this).addClass('is-valid');
        }
    });
}

function RemoveIsValidClassCreateOrderSellerDetail() {
    $('#seller_observation_c').removeClass('is-valid');
    $(`span[aria-labelledby="select2-product_id_c-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-color_id_tone_id_c-container"]`).removeClass('is-valid');
    $('#sizes_c').find('input').each(function() {
        $(this).removeClass('is-valid');
    });
}

function AddIsInvalidClassCreateOrderSellerDetail(input) {
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
    $('#sizes_c').find('input').each(function(index) {
        if(input === `order_detail_quantities.${index}.quantity`) {
            $(this).addClass('is-invalid');
        }
    });
}

function RemoveIsInvalidClassCreateOrderSellerDetail() {
    $('#seller_observation_c').removeClass('is-invalid');
    $(`span[aria-labelledby="select2-product_id_c-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-color_id_tone_id_c-container"]`).removeClass('is-invalid');
    $('#sizes_c').find('input').each(function() {
        $(this).removeClass('is-invalid');
    });
}
