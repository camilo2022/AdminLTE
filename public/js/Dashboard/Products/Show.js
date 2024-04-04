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

    $('#colors_tones_s').empty();
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

    $('#accordion').empty();
    $.each(data.product.colors_tones, function(i, color_tone) {
        let cardPrimary = $('<div>').addClass('card card-primary');
        let cardHeader = $('<div>').addClass('card-header');
        let cardTitle = $('<h4>').addClass('card-title w-100').attr({
            'data-toggle': 'collapse',
            'data-parent': '#accordion',
            'href': `#collapse${i}`
        }).css({
            'cursor': 'pointer'
        });
        let label = $('<label>').text(`${data.product.code.toUpperCase()} | ${color_tone.color.name} - ${color_tone.color.code} | ${color_tone.tone.name} - ${color_tone.tone.code}`).css({
            'cursor': 'pointer'
        });

        cardTitle.append(label);
        cardHeader.append(cardTitle);

        let collapse= $('<div>').addClass('panel-collapse collapse in').attr({
            'id': `collapse${i}`
        });
        let cardBody = $('<div>').addClass('card-body');
        let divRow = $('<div>').addClass('row');
        let divColFiles = $('<div>').addClass('col-lg-6').css({
            'align-items': 'center'
        });
        let divColTable = $('<div>').addClass('col-lg-6');

        collapse.append(cardBody);
        
        let filesForm = $('<div>').addClass('form-group').css({
            'text-align': 'right'
        });
        let filesLabel = $('<label>').attr('for', '');
        let filesInputGroup = $('<div>').addClass('input-group');
        let filesInput = $('<input>').attr({
            'type': 'file',
            'id': `files${i}_s`,
            'name': `files${i}_s`,
            'class': 'form-control dropify files_c',
            'accept': '.jpeg, .jpg, .png, .gif, .mp4, .avi, .wmv, .mov, .mkv, .flv, .webm, .mpeg',
            'multiple': true
        });
        let button = $('<button>').addClass('btn btn-primary mt-2 w-100').attr({
            'id': `ShowProductColorToneChargeFileChargeButton${i}`,
            'onclick': `ShowProductColorToneFileCharge(${color_tone.id}, 'files${i}_s', ${data.product.id})`,
            'title': `Anexar archivos al producto ${data.product.code.toUpperCase()} en el color ${color_tone.color.name} - ${color_tone.color.code} en el tono ${color_tone.tone.name} - ${color_tone.tone.code}.`
        })
        let icon = $('<i>').addClass('fas fa-floppy-disk');

        button.append(icon);
        filesInputGroup.append(filesInput);
        filesForm.append(filesLabel, filesInputGroup, button);

        let table = `<div class="table-responsive">
            <table id="productFiles${i}" class="table table-bordered table-hover dataTable dtr-inline w-100">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Extension</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>`;
        $.each(color_tone.files, function(j, file) {
            table += `<tr>
                    <td>${file.id}</td>
                    <td>${file.name}</td>
                    <td>${file.extension}</td>
                    <td>
                        <div class="text-center" style="width: 100px;">
                            <a href="${file.path}" target="_blank"
                            class="btn btn-info btn-sm mr-2" title="Ver archivo de producto.">
                                <i class="fas fa-eye text-white"></i>
                            </a>
                            <a onclick="ShowProductColorToneFileDelete(${file.id}, ${data.product.id})" type="button" 
                            class="btn btn-danger btn-sm mr-2" title="Eliminar archivo de producto">
                                <i class="fas fa-trash text-white"></i>
                            </a>
                        </div>
                    </td>
                </tr>`;
        })
        table += `</tbody>
            </table>
        </div>`;

        divColFiles.append(filesForm);
        divColTable.append(table);
        divRow.append(divColFiles, divColTable);
        cardBody.append(divRow);
        cardPrimary.append(cardHeader, collapse);
        $('#accordion').append(cardPrimary);
        $(`#files${i}_s`).dropify();
        $(`#productFiles${i}`).DataTable({
            "lengthMenu": [ [3, 5, 7, 10], [3, 5, 7, 10] ],
            "pageLength": 3
        });
    })
}

function ShowProductColorToneFileCharge(product_color_tone_id, dropify, product) {
    let formData = new FormData();
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('product_color_tone_id', product_color_tone_id);
    for (let i = 0; i < $(`#${dropify}`)[0].files.length; i++) {
        formData.append('files[]', $(`#${dropify}`)[0].files[i]);
    }

    Swal.fire({
        title: '¿Desea cargar los archivos al producto?',
        text: 'Los archivos se cargaran al producto.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Products/Charge`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    tableProducts.ajax.reload();
                    ShowProductAjaxSuccess(response);
                    ShowProductModal(product);
                },
                error: function (xhr, textStatus, errorThrown) {
                    ShowProductAjaxError(xhr);
                }
            });
        } else {
            toastr.info('Los archivos no se cargaron al producto.')
        }
    });
}

function ShowProductColorToneFileDelete(id, product) {
    Swal.fire({
        title: '¿Desea eliminar el archivo del producto?',
        text: 'El archivo del producto será eliminado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, eliminar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Products/Destroy`,
                type: 'DELETE',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id
                },
                success: function(response) {
                    tableProducts.ajax.reload();
                    ShowProductAjaxSuccess(response);
                    ShowProductModal(product);
                },
                error: function(xhr, textStatus, errorThrown) {
                    ShowProductAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El archivo del producto seleccionado no fue eliminado.');
        }
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
            ShowProductModal(product);
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
            ShowProductModal(product);
        },
        error: function (xhr, textStatus, errorThrown) {
            ShowProductAjaxError(xhr);
        }
    });
}

function ShowProductColorTone(color, tone, product, checkbox) {
    if ($(checkbox).prop('checked')) {
        ShowProductAssignColorTone(color, tone, product);
    } else {
        ShowProductRemoveColorTone(color, tone, product);
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
            ShowProductModal(product);
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
            ShowProductModal(product);
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
