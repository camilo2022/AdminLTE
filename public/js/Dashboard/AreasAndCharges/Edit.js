function EditAreaAndChargesModal(id) {
    $.ajax({
        url: `/Dashboard/AreasAndCharges/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function(response) {
            tableAreasAndCharges.ajax.reload();
            EditAreaAndChargesModalCleaned(response.data);
            EditAreaAndChargesAjaxSuccess(response);
            $('#EditAreaAndChargesModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            tableAreasAndCharges.ajax.reload();
            EditAreaAndChargesAjaxError(xhr);
        }
    });
}

function EditAreaAndChargesModalCleaned(areaAndCharges) {
    RemoveIsInvalidClassEditAreaAndCharges();
    RemoveIsValidClassEditAreaAndCharges();
    $('.charges_e').empty();
    $('#name_e').val(areaAndCharges.name);
    $('#description_e').val(areaAndCharges.description);
    $('#EditAreaAndChargesAddChargeButton').attr('data-count', 0);
    $('#EditAreaAndChargesButton').attr('onclick', `EditAreaAndCharges(${areaAndCharges.id})`);
    $.each(areaAndCharges.charges, function (i, charge) {
        EditAreaAndChargesAddCharge(charge.id, charge.name, charge.description, charge.deleted_at);
    })
}

function EditAreaAndCharges(id) {
    Swal.fire({
        title: '¿Desea actualizar el area y los cargos?',
        text: 'El area y los cargos serán actualizados.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/AreasAndCharges/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_e').val(),
                    'description': $('#description_e').val(),
                    'charges': $('.charges_e').find('div.charge_e').map(function(index) {
                        const id = $(this).find('input.name_e').attr('data-id');
                        const charge = {
                            'name': $(this).find('input.name_e').val(),
                            'description': $(this).find('textarea.description_e').val(),
                            'status': $(this).find('button.status').attr('data-status')
                        };
                        if (id != undefined) {
                            charge['id'] = id;
                        };
                        return charge;
                    }).get()
                },
                success: function(response) {
                    tableAreasAndCharges.ajax.reload();
                    EditAreaAndChargesAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableAreasAndCharges.ajax.reload();
                    EditAreaAndChargesAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El area y los cargos no fueron actualizados.')
        }
    });
}

function EditAreaAndChargesAddCharge(id, name, description, deleted) {
    // Crear el nuevo elemento HTML con jQuery
    let data = $('#EditAreaAndChargesAddChargeButton').attr('data-count');
    let newCharge = $('<div>').attr({
        'id': `group-charge${data}`,
        'class': 'form-group charge_e'
    });
    let card = $('<div>').addClass('card collapsed-card');
    let cardHeader = $('<div>').addClass('card-header border-0 ui-sortable-handle');
    let cardTitle = $('<h3>').addClass('card-title mt-1').css({'width':'70%'});
    let inputGroup = $('<div>').addClass('input-group');
    let input = $('<input>').attr({
        'type': 'text',
        'class': 'form-control name_e',
        'id': `name${data}_e`,
        'value': name,
        'data-id': id
    });
    let inputGroupAppend = $('<div>').addClass('input-group-append');
    let inputGroupText = $('<span>').addClass('input-group-text');
    let signatureIcon = $('<i>').addClass('fas fa-signature');
    let cardTools = $('<div>').addClass('card-tools');
    let collapseButton = $('<button>').attr({
        'type': 'button',
        'class': 'btn btn-info btn-sm ml-2 mt-2',
        'data-card-widget': 'collapse'
    });
    let plusIcon = $('<i>').addClass('fas fa-plus');
    let removeButton = $('<button>').attr({
        'type': 'button',
        'class': `btn btn-${deleted == null ? 'danger' : 'success'} btn-sm ml-2 mt-2 status`,
        'data-status': deleted == null ? 'true' : 'false',
        'onclick': `EditAreaAndCharges${deleted == null ? 'Inactive' : 'Active'}Charge(this)`
    });
    let timesIcon = $('<i>').addClass(`${deleted == null ? 'fas fa-times' : 'fas fa-check'}`);

    // Anidar elementos
    inputGroupText.append(signatureIcon);
    inputGroupAppend.append(inputGroupText);
    inputGroup.append(input, inputGroupAppend);
    cardTitle.append(inputGroup);
    collapseButton.append(plusIcon);
    removeButton.append(timesIcon);
    cardTools.append(collapseButton, removeButton);
    cardHeader.append(cardTitle, cardTools);
    card.append(cardHeader);

    let cardBody = $('<div>').addClass('card-body').addClass('table-responsive').css('display', 'none');

    let descriptionForm = $('<div>').addClass('form-group');
    let descriptionLabel = $('<label>').attr('for', '').text('Descripcion');
    let descriptionInputGroup = $('<div>').addClass('input-group');
    let descriptionInput = $('<textarea>').attr({
        'type': 'text',
        'id': `description${data}_e`,
        'class': 'form-control description_e',
        'cols': '30',
        'rows': '3'
    }).text(description);
    let descriptionInputAppend = $('<div>').addClass('input-group-append');
    let descriptionIcon = $('<span>').addClass('input-group-text').append($('<i>').addClass('fas fa-text-size'));
    descriptionInputAppend.append(descriptionIcon);
    descriptionInputGroup.append(descriptionInput, descriptionInputAppend);
    descriptionForm.append(descriptionLabel, descriptionInputGroup);


    cardBody.append(descriptionForm);
    card.append(cardBody);

    newCharge.append(card);

    // Agregar el nuevo elemento al elemento con clase "charges_e"
    $('.charges_e').append(newCharge);
    data++;
    $('#EditAreaAndChargesAddChargeButton').attr('data-count', data)
}

function EditAreaAndChargesActiveCharge(button) {
    $(button).removeClass('btn-success').addClass('btn-danger')
    $(button).attr('onclick', 'EditAreaAndChargesInactiveCharge(this)');
    $(button).attr('data-status', 'true');
    $(button).find('i').removeClass().addClass('fas fa-times');
}

function EditAreaAndChargesInactiveCharge(button) {
    $(button).removeClass('btn-danger').addClass('btn-success')
    $(button).attr('onclick', 'EditAreaAndChargesActiveCharge(this)');
    $(button).attr('data-status', 'false');
    $(button).find('i').removeClass().addClass('fas fa-check');
}

function EditAreaAndChargesAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
        $('#EditAreaAndChargesModal').modal('hide');
    }

    if(response.status === 204) {
        toastr.info(response.message);
        $('#EditAreaAndChargesModal').modal('hide');
    }
}

function EditAreaAndChargesAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditAreaAndChargesModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditAreaAndChargesModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditAreaAndChargesModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassEditAreaAndCharges();
        RemoveIsInvalidClassEditAreaAndCharges();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassEditAreaAndCharges(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditAreaAndCharges();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditAreaAndChargesModal').modal('hide');
    }
}

function AddIsValidClassEditAreaAndCharges() {
    if (!$('#name_e').hasClass('is-invalid')) {
        $('#name_e').addClass('is-valid');
    }
    if (!$('#description_e').hasClass('is-invalid')) {
        $('#description_e').addClass('is-valid');
    }

    $('.charges_e').find('div.charge_e').each(function(index) {
        if (!$(this).find('input.name_e').hasClass('is-invalid')) {
            $(this).find('input.name_e').addClass('is-valid');
        }
        if (!$(this).find('textarea.description_e').hasClass('is-invalid')) {
            $(this).find('textarea.description_e').addClass('is-valid');
        }
    });
}

function RemoveIsValidClassEditAreaAndCharges() {
    $('#name_e').removeClass('is-valid');
    $('#description_e').removeClass('is-valid');

    $('.charges_e').find('div.charge_e').each(function(index) {
        $(this).find('input.name_e').removeClass('is-valid');
        $(this).find('textarea.description_e').removeClass('is-valid');
    });
}

function AddIsInvalidClassEditAreaAndCharges(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }

    if (!$(`span[aria-labelledby="select2-${input}_e-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_e-container"]`).addClass('is-invalid');
    }

    $('.charges_e').find('div.charge_e').each(function(index) {
        // Agrega la clase 'is-invalid'
        if(input === `charges.${index}.name`) {
            if (!$(this).find('input.name_e').hasClass('is-valid')) {
                $(this).find('input.name_e').addClass('is-invalid');
            }
        }
        if(input === `charges.${index}.description`) {
            if (!$(this).find('textarea.description_e').hasClass('is-valid')) {
                $(this).find('textarea.description_e').addClass('is-invalid');
            }
        }
    });
}

function RemoveIsInvalidClassEditAreaAndCharges() {
    $('#name_e').removeClass('is-invalid');
    $('#description_e').removeClass('is-invalid');

    $('.charges_e').find('div.charge_e').each(function(index) {
        $(this).find('input.name_e').removeClass('is-invalid');
        $(this).find('textarea.description_e').removeClass('is-invalid');
    });
}
