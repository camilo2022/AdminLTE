function EditOrderSellerDetailModal(id) {
    $.ajax({
        url: `/Dashboard/Orders/Seller/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $('#IndexOrderSellerDetail').trigger('click');
            EditOrderSellerDetailModalCleaned(response.data.orderDetail);
            EditOrderSellerDetailModalClient(response.data.clients);
            EditOrderSellerDetailAjaxSuccess(response);
            $('#EditOrderSellerDetailModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            $('#IndexOrderSellerDetail').trigger('click');
            EditOrderSellerDetailAjaxError(xhr);
        }
    });
}

function EditOrderSellerDetailModalCleaned(orderDetail) {
    EditOrderSellerDetailModalResetSelect('product_id_e');
    RemoveIsValidClassEditOrderSellerDetail();
    RemoveIsInvalidClassEditOrderSellerDetail();
    $('#sizes_e').html('');

    $('#EditOrderSellerDetailButton').attr('onclick', `EditOrderSellerDetail(${orderDetail.id})`);
    $('#EditOrderSellerDetailButton').attr('data-id', orderDetail.id);
    $('#EditOrderSellerDetailButton').attr('data-product_id', orderDetail.product_id);
    $('#EditOrderSellerDetailButton').attr('data-color_id', orderDetail.color_id);
    $('#EditOrderSellerDetailButton').attr('data-tone_id', orderDetail.tone_id);
    $('#EditOrderSellerDetailButton').attr('data-quantities', orderDetail.quantities);
}

function EditOrderSellerDetailModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function EditOrderSellerDetailModalProduct(products) {
    $.each(products, function(index, product) {
        $('#product_id_e').append(new Option(product.code, product.id, false, false));
    });

    let product_id = $('#EditOrderSellerDetailButton').attr('data-product_id');
    if(product_id != '') {
        $("#product_id_e").val(product_id).trigger('change');
        $('#EditOrderSellerDetailButton').attr('data-product_id', '');
    }
}

function EditOrderSellerDetailModalProductGetColorTone(select) {
    if($(select).val() == '') {
        $('#sizes_e').html('');
        EditOrderSellerDetailModalResetSelect('color_id_tone_id_e');
    } else {
        let id = $('#EditOrderSellerDetailButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Orders/Seller/Details/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'product_id':  $(select).val()
            },
            success: function(response) {
                EditOrderSellerDetailModalResetSelect('color_id_tone_id_e');
                EditOrderSellerDetailModalColorTone(response.data.colors_tones);
                $('#sizes_e').html('');
            },
            error: function(xhr, textStatus, errorThrown) {
                EditOrderSellerDetailAjaxError(xhr);
            }
        });
    }
};

function EditOrderSellerDetailModalColorTone(colors_tones) {
    colors_tones.forEach(color_tone => {
        $('#color_id_tone_id_e').append(new Option(`${color_tone.color.name} - ${color_tone.tone.name}`, `${color_tone.color.id}-${color_tone.tone.id}`, false, false));
    });

    let color_id = $('#EditOrderSellerDetailButton').attr('data-color_id');
    let tone_id = $('#EditOrderSellerDetailButton').attr('data-tone_id');
    if(color_id != '' && tone_id != '') {
        $("#color_id_tone_id_e").val(`${color_id}-${tone_id}`).trigger('change');
        $('#EditOrderSellerDetailButton').attr('data-color_id', '');
        $('#EditOrderSellerDetailButton').attr('data-tone_id', '');
    }
}

function EditOrderSellerDetailModalColorToneGetSizesQuantity() {
    if($('#color_id_tone_id_e').val() == '') {
        $('#sizes_e').html('');
    } else {
        let id = $('#EditOrderSellerDetailButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Orders/Seller/Details/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'product_id':  $('#product_id_e').val(),
                'color_id':  $('#color_id_tone_id_e').val().split('-')[0],
                'tone_id':  $('#color_id_tone_id_e').val().split('-')[1],
                'size_id':  $('#size_id_e').val(),
            },
            success: function(response) {
                EditOrderSellerDetailModalSizes(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditOrderSellerDetailAjaxError(xhr);
            }
        });
    }
}

function EditOrderSellerDetailModalSizes(sizes) {
    let inputs = '';
    $.each(sizes, function(index, size) {
        inputs += `<div class="form-group">
                        <label for="size_${size.size.id}_e">${size.size.name}</label>
                        <input type="number" class="form-control" id="size_${size.size.id}_e" name="size_${size.size.id}" data-size_id="${size.size.id}" value="0">
                        <small id="message_size_${size.size.id}_e">${size.quantity} unidades disponibles.</small>
                    </div>`;
    });
    $('#sizes_e').html(inputs);
}

function EditOrderSellerDetail(id) {
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
                url: `/Dashboard/Orders/Seller/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'order_id': $('#IndexOrderSellerDetail').attr('data-id'),
                    'product_id':  $('#product_id_e').val(),
                    'color_id':  $('#color_id_tone_id_e').val().split('-')[0],
                    'tone_id':  $('#color_id_tone_id_e').val().split('-')[1],
                    'order_detail_quantities': $('#sizes_e').find('div.form-group').map(function(index) {
                        return {
                            'quantity': $(this).find('input').val() == '' ? 0 : $(this).find('input').val(),
                            'size_id': $(this).find('input').attr('data-size_id')
                        };
                    }).get()
                },
                success: function (response) {
                    $('#IndexOrderSellerDetail').trigger('click');
                    EditOrderSellerDetailAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    $('#IndexOrderSellerDetail').trigger('click');
                    EditOrderSellerDetailAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El detalle del pedido no fue actualizado.')
        }
    });
}

function EditOrderSellerDetailAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#EditOrderSellerDetailModal').modal('hide');
    }

    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditOrderSellerDetailModal').modal('hide');
    }
}

function EditOrderSellerDetailAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderSellerDetailModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderSellerDetailModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderSellerDetailModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditOrderSellerDetail();
        RemoveIsInvalidClassEditOrderSellerDetail();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditOrderSellerDetail(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditOrderSellerDetail();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderSellerDetailModal').modal('hide');
    }
}

function AddIsValidClassEditOrderSellerDetail() {
    if (!$('#seller_observation_e').hasClass('is-invalid')) {
        $('#seller_observation_e').addClass('is-valid');
    }
    if (!$('#dispatch_e').hasClass('is-invalid')) {
        $('#dispatch_e').addClass('is-valid');
    }
    if (!$('#dispatch_date_e').hasClass('is-invalid')) {
        $('#dispatch_date_e').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-client_id_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-client_id_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-client_branch_id_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-client_branch_id_e-container"]').addClass('is-valid');
    }
}

function RemoveIsValidClassEditOrderSellerDetail() {
    $('#seller_observation_e').removeClass('is-valid');
    $('#dispatch_e').removeClass('is-valid');
    $('#dispatch_date_e').removeClass('is-valid');
    $('span[aria-labelledby="select2-client_id_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-client_branch_id_e-container"]').removeClass('is-valid');
}

function AddIsInvalidClassEditOrderSellerDetail(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_e-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_e-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditOrderSellerDetail() {
    $('#seller_observation_e').removeClass('is-invalid');
    $('#dispatch_e').removeClass('is-invalid');
    $('#dispatch_date_e').removeClass('is-invalid');
    $('span[aria-labelledby="select2-client_id_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-client_branch_id_e-container"]').removeClass('is-invalid');
}
