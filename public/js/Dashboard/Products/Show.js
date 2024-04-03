function ShowProductModal(id) {
    $.ajax({
        url: `/Dashboard/Products/Show/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            ShowProductModalCleaned(response.data);
            $('#ShowProductModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            ShowProductAjaxError(xhr);
        }
    });
}

function ShowProductModalCleaned(data) {
    /* $.each(product.photos, function(index, photo) {
        $('#photos_indicators').append(`<li data-target="#carouselExampleIndicators" data-slide-to="${index}" ${index == 0 ? 'class="active"' : ''}></li>`);
        $('#photos_carousel').append(`<div class="carousel-item ${index == 0 ? 'active' : ''}">
                <img class="d-block w-100" src="${photo.path}" alt="${photo.name}">
            </div>`);
    }); */
    $('#sizes_s').empty();
    $.each(data.sizes, function (index, size) {
        // Crear un nuevo div para cada talla
        let sizeDiv = $('<div>').addClass('col-lg-4 pl-2 icheck-primary');
        
        let sizeCheckbox = $(`<input>`).attr({
            'type': 'checkbox',
            'id': size.id,
            'checked': size.admin,
            'onchange': `ShowProductSize(${size.id}, ${data.product.id}, this)`
        });
        
        let sizeLabel = $('<label>').text(`${size.name}`).attr({
            'for': size.id,
            'class': 'mt-3 ml-3'
        });
        
        sizeDiv.append(sizeCheckbox);
        sizeDiv.append(sizeLabel);
        
        let currentRow = $('#sizes_s').children('.row').last();
        if (currentRow.length === 0 || currentRow.children().length >= 3) {
            currentRow = $('<div>').addClass('row');
            $('#sizes_s').append(currentRow);
        }
        currentRow.append(sizeDiv);
    });

    $.each(data.colors_tones, function (index, color_tone) {
        let color_toneDiv = $('<div>').addClass('col-lg-6 pl-2 icheck-primary');
        
        let color_toneCheckbox = $(`<input>`).attr({
            'type': 'checkbox',
            'id': `${color_tone.color_id}-${color_tone.tone_id}`,
            'checked': color_tone.admin,
            'onchange': `ShowProductColorTone(${color_tone.color_id}, ${color_tone.tone_id}, ${data.product.id}, this)`
        });
        
        let color_toneLabel = $('<label>').text(`${color_tone.color_name} ${color_tone.tone_name}`).attr({
            'for': `${color_tone.color_id}-${color_tone.tone_id}`,
            'class': 'mt-3 ml-3'
        });
        
        color_toneDiv.append(color_toneCheckbox);
        color_toneDiv.append(color_toneLabel);
        
        let currentRow = $('#colors_tones_s').children('.row').last();
        if (currentRow.length === 0 || currentRow.children().length >= 2) {
            currentRow = $('<div>').addClass('row');
            $('#colors_tones_s').append(currentRow);
        }
        currentRow.append(color_toneDiv);
    });
}

function ShowProductSize(size, product, checkbox) {
    if ($(checkbox).prop('checked')) {
        ShowProductAssignSize(size, product);
    } else {
        ShowProductRemoveSize(size, product);
    }
}

function ShowProductAssignSize(size, product) {
    $.ajax({
        url: `/Dashboard/Products/AssignSize`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'size_id': size,
            'product_id': product,
        },
        success: function (response) {
            tableProducts.ajax.reload();
            ShowProductAjaxSuccess(response);
        },
        error: function (xhr, textStatus, errorThrown) {
            ShowProductAjaxError(xhr);
        }
    });
}

function ShowProductRemoveSize(size, product) {
    $.ajax({
        url: `/Dashboard/Products/RemoveSize`,
        type: 'DELETE',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'size_id': size,
            'product_id': product,
        },
        success: function (response) {
            tableProducts.ajax.reload();
            ShowProductAjaxSuccess(response);
        },
        error: function (xhr, textStatus, errorThrown) {
            ShowProductAjaxError(xhr);
        }
    });
}

function ShowProductColorTone(color, tone, product, checkbox) {
    if ($(checkbox).prop('checked')) {
        ShowProductAssignSize(color, tone, product);
    } else {
        ShowProductRemoveSize(color, tone, product);
    }
}

function ShowProductAssignColorTone(color, tone, product) {
    $.ajax({
        url: `/Dashboard/Products/AssignColorTone`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'color_id': color,
            'tone_id': tone,
            'product_id': product,
        },
        success: function (response) {
            tableProducts.ajax.reload();
            ShowProductAjaxSuccess(response);
        },
        error: function (xhr, textStatus, errorThrown) {
            ShowProductAjaxError(xhr);
        }
    });
}

function ShowProductRemoveColorTone(color, tone, product) {
    $.ajax({
        url: `/Dashboard/Products/RemoveColorTone`,
        type: 'DELETE',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'color_id': color,
            'tone_id': tone,
            'product_id': product,
        },
        success: function (response) {
            tableProducts.ajax.reload();
            ShowProductAjaxSuccess(response);
        },
        error: function (xhr, textStatus, errorThrown) {
            ShowProductAjaxError(xhr);
        }
    });
}

function ShowProductAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
    }
}

function ShowProductAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowProductModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowProductModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowProductModal').modal('hide');
    }

    if(xhr.status === 422){
        $.each(xhr.responseJSON.errors, function(field, messages) {
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowProductModal').modal('hide');
    }
}
