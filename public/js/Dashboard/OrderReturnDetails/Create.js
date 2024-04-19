function CreateOrderReturnDetailModal() {
    $.ajax({
        url: `/Dashboard/Orders/Return/Details/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'order_return_id': $('#IndexOrderReturnDetail').attr('data-id'),
        },
        success: function (response) {
            console.log(response);
            $('#IndexOrderReturnDetail').trigger('click');
            CreateOrderReturnDetailModalCleaned();
            CreateOrderReturnDetailModalProduct(response.data);
            CreateOrderReturnDetailAjaxSuccess(response);
            $('#CreateOrderReturnDetailModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            $('#IndexOrderReturnDetail').trigger('click');
            CreateOrderReturnDetailAjaxError(xhr);
        }
    });
}

function CreateOrderReturnDetailModalCleaned() {
    CreateOrderReturnDetailModalResetSelect('product_id_c');
    RemoveIsValidClassCreateOrderReturnDetail();
    RemoveIsInvalidClassCreateOrderReturnDetail();
    $('#observation_c').val('');
    $('#sizes_c').html('');
}

function CreateOrderReturnDetailModalResetSelect(id) {
    $(`#${id}`).html('');
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function CreateOrderReturnDetailModalProduct(products) {
    $.each(products, function(index, product) {
        $('#product_id_c').append(new Option(product.code, product.id, false, false));
    });
}

function CreateOrderReturnDetailModalProductGetColorTone(select) {
    if($(select).val() == '') {
        $('#sizes_c').html('');
        CreateOrderReturnDetailModalResetSelect('color_id_tone_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Orders/Return/Details/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'order_return_id': $('#IndexOrderReturnDetail').attr('data-id'),
                'product_id':  $(select).val()
            },
            success: function(response) {
                CreateOrderReturnDetailModalResetSelect('color_id_tone_id_c');
                CreateOrderReturnDetailModalColorTone(response.data);
                $('#sizes_c').html('');
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateOrderReturnDetailAjaxError(xhr);
            }
        });
    }
};

function CreateOrderReturnDetailModalColorTone(colors_tones) {
    colors_tones.forEach(color_tone => {
        $('#color_id_tone_id_c').append(new Option(`${color_tone.color.name} - ${color_tone.tone.name}`, `${color_tone.color.id}-${color_tone.tone.id}`, false, false));
    });
}

function CreateOrderReturnDetailModalColorToneGetSizesQuantity() {
    if($('#color_id_tone_id_c').val() == '') {
        $('#sizes_c').html('');
    } else {
        $.ajax({
            url: `/Dashboard/Orders/Return/Details/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'order_return_id': $('#IndexOrderReturnDetail').attr('data-id'),
                'product_id':  $('#product_id_c').val(),
                'color_id':  $('#color_id_tone_id_c').val().split('-')[0],
                'tone_id':  $('#color_id_tone_id_c').val().split('-')[1],
                'size_id':  $('#size_id_c').val(),
            },
            success: function(response) {
                toastr.info('Inventario por talla cargado, ingrese las cantidades.');
                CreateOrderReturnDetailModalSizes(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateOrderReturnDetailAjaxError(xhr);
            }
        });
    }
}

function CreateOrderReturnDetailModalSizes(sizes) {
    console.log(sizes);
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

function CreateOrderReturnDetail() {
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
                url: `/Dashboard/Orders/Return/Details/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'order_return_id': $('#IndexOrderReturnDetail').attr('data-id'),
                    'product_id':  $('#product_id_c').val(),
                    'color_id':  $('#color_id_tone_id_c').val().split('-')[0],
                    'tone_id':  $('#color_id_tone_id_c').val().split('-')[1],
                    'order_return_detail_quantities': $('#sizes_c').find('div.form-group').map(function(index) {
                        return {
                            'quantity': $(this).find('input').val() == '' ? 0 : $(this).find('input').val(),
                            'size_id': $(this).find('input').attr('data-size_id')
                        };
                    }).get(),
                    'observation': $('#observation_c').val()
                },
                success: function (response) {
                    $('#IndexOrderReturnDetail').trigger('click');
                    CreateOrderReturnDetailAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    $('#IndexOrderReturnDetail').trigger('click');
                    CreateOrderReturnDetailAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El detalle del pedido no fue creado.')
        }
    });
}

function CreateOrderReturnDetailAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#CreateOrderReturnDetailModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateOrderReturnDetailModal').modal('hide');
    }
}

function CreateOrderReturnDetailAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderReturnDetailModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderReturnDetailModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderReturnDetailModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateOrderReturnDetail();
        RemoveIsInvalidClassCreateOrderReturnDetail();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateOrderReturnDetail(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateOrderReturnDetail();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderReturnDetailModal').modal('hide');
    }
}

function AddIsValidClassCreateOrderReturnDetail() {
    if (!$('#observation_c').hasClass('is-invalid')) {
        $('#observation_c').addClass('is-valid');
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

function RemoveIsValidClassCreateOrderReturnDetail() {
    $('#observation_c').removeClass('is-valid');
    $(`span[aria-labelledby="select2-product_id_c-container"]`).removeClass('is-valid');
    $(`span[aria-labelledby="select2-color_id_tone_id_c-container"]`).removeClass('is-valid');
    $('#sizes_c').find('input').each(function() {
        $(this).removeClass('is-valid');
    });
}

function AddIsInvalidClassCreateOrderReturnDetail(input) {
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
        if(input === `order_return_detail_quantities.${index}.quantity`) {
            $(this).addClass('is-invalid');
        }
    });
}

function RemoveIsInvalidClassCreateOrderReturnDetail() {
    $('#observation_c').removeClass('is-invalid');
    $(`span[aria-labelledby="select2-product_id_c-container"]`).removeClass('is-invalid');
    $(`span[aria-labelledby="select2-color_id_tone_id_c-container"]`).removeClass('is-invalid');
    $('#sizes_c').find('input').each(function() {
        $(this).removeClass('is-invalid');
    });
}
