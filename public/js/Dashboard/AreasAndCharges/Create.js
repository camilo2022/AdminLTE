function CreateAreaAndChargesModal() {
    $.ajax({
        url: `/Dashboard/AreasAndCharges/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function(response) {
            CreateAreaAndChargesModalCleaned();
            CreateAreaAndChargesAjaxSuccess(response);
            $('#CreateAreaAndChargesModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            CreateAreaAndChargesAjaxError(xhr);
        }
    });
}

function CreateAreaAndChargesModalCleaned() {
    RemoveIsInvalidClassCreateAreaAndCharges();
    RemoveIsValidClassCreateAreaAndCharges();
    $('.charges_c').empty();
    $('#name_c').val('');
    $('#description_c').val('');
    $('#CreateAreaAndChargesAddChargeButton').attr('data-count', 0);
    CreateAreaAndChargesAddCharge();
}

function CreateAreaAndCharges() {
    Swal.fire({
        title: '¿Desea guardar el area y los cargos?',
        text: 'El area y los cargos serán creados.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/AreasAndCharges/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                    'description': $('#description_c').val(),
                    'charges': $('.charges_c').find('div.charge_c').map(function(index) {
                        return {
                            'name': $(this).find('input.name_c').val(),
                            'description': $(this).find('textarea.description_c').val()
                        };
                    }).get()
                },
                success: function(response) {
                    tableAreasAndCharges.ajax.reload();
                    CreateAreaAndChargesAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    CreateAreaAndChargesAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El area y los cargos no fueron creados.')
        }
    });
}

function CreateAreaAndChargesAddCharge() {
    // Crear el nuevo elemento HTML con jQuery
    let id = $('#CreateAreaAndChargesAddChargeButton').attr('data-count');
    let newCharge = $('<div>').attr({
        'id': `group-charge${id}`,
        'class': 'form-group charge_c'
    });
    let card = $('<div>').addClass('card collapsed-card');
    let cardHeader = $('<div>').addClass('card-header border-0 ui-sortable-handle');
    let cardTitle = $('<h3>').addClass('card-title mt-1').css({'width':'70%'});
    let inputGroup = $('<div>').addClass('input-group');
    let input = $('<input>').attr({
        'type': 'text',
        'class': 'form-control name_c',
        'id': `name${id}_c`,
        'name': ''
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
        'class': 'btn btn-danger btn-sm ml-2 mt-2',
        'data-card-widget': 'remove',
        'onclick': `CreateAreaAndChargesRemoveCharge(${id})`
    });
    let timesIcon = $('<i>').addClass('fas fa-times');

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
        'id': `description${id}_c`,
        'class': 'form-control description_c',
        'cols': '30',
        'rows': '3'
    });
    let descriptionInputAppend = $('<div>').addClass('input-group-append');
    let descriptionIcon = $('<span>').addClass('input-group-text').append($('<i>').addClass('fas fa-text-size'));
    descriptionInputAppend.append(descriptionIcon);
    descriptionInputGroup.append(descriptionInput, descriptionInputAppend);
    descriptionForm.append(descriptionLabel, descriptionInputGroup);


    cardBody.append(descriptionForm);
    card.append(cardBody);

    newCharge.append(card);

    // Agregar el nuevo elemento al elemento con clase "charges_c"
    $('.charges_c').append(newCharge);
    id++;
    $('#CreateAreaAndChargesAddChargeButton').attr('data-count', id)
}

function CreateAreaAndChargesRemoveCharge(index) {
    $(`#group-charge${index}`).remove();
}

function CreateAreaAndChargesAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.info(response.message);
        $('#CreateAreaAndChargesModal').modal('hide');
    }

    if(response.status === 201) {
        toastr.success(response.message);
        $('#CreateAreaAndChargesModal').modal('hide');
    }
}

function CreateAreaAndChargesAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateAreaAndChargesModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateAreaAndChargesModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateAreaAndChargesModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassCreateAreaAndCharges();
        RemoveIsInvalidClassCreateAreaAndCharges();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassCreateAreaAndCharges(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateAreaAndCharges();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateAreaAndChargesModal').modal('hide');
    }
}

function AddIsValidClassCreateAreaAndCharges() {
    if (!$('#name_c').hasClass('is-invalid')) {
        $('#name_c').addClass('is-valid');
    }
    if (!$('#description_c').hasClass('is-invalid')) {
        $('#description_c').addClass('is-valid');
    }

    $('.charges_c').find('div.charge_c').each(function(index) {
        if (!$(this).find('input.name_c').hasClass('is-invalid')) {
            $(this).find('input.name_c').addClass('is-valid');
        }
        if (!$(this).find('textarea.description_c').hasClass('is-invalid')) {
            $(this).find('textarea.description_c').addClass('is-valid');
        }
    });
}

function RemoveIsValidClassCreateAreaAndCharges() {
    $('#name_c').removeClass('is-valid');
    $('#description_c').removeClass('is-valid');

    $('.charges_c').find('div.charge_c').each(function(index) {
        $(this).find('input.name_c').removeClass('is-valid');
        $(this).find('textarea.description_c').removeClass('is-valid');
    });
}

function AddIsInvalidClassCreateAreaAndCharges(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }

    $('.charges_c').find('div.charge_c').each(function(index) {
        // Agrega la clase 'is-invalid'
        if(input === `charges.${index}.name`) {
            if (!$(this).find('input.name_c').hasClass('is-valid')) {
                $(this).find('input.name_c').addClass('is-invalid');
            }
        }
        if(input === `charges.${index}.description`) {
            if (!$(this).find('textarea.description_c').hasClass('is-valid')) {
                $(this).find('textarea.description_c').addClass('is-invalid');
            }
        }
    });
}

function RemoveIsInvalidClassCreateAreaAndCharges() {
    $('#name_c').removeClass('is-invalid');
    $('#description_c').removeClass('is-invalid');

    $('.charges_c').find('div.charge_c').each(function(index) {
        $(this).find('input.name_c').removeClass('is-invalid');
        $(this).find('textarea.description_c').removeClass('is-invalid');
    });
}
