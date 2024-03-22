function CreateOrderInvoiceModal(order_dispatch_id) {
    $.ajax({
        url: `/Dashboard/Orders/Invoice/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function(response) {
            CreateOrderInvoiceModalCleaned(order_dispatch_id);
            CreateOrderInvoiceAjaxSuccess(response);
            $('#CreateOrderInvoiceModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            CreateOrderInvoiceAjaxError(xhr);
        }
    });
}

function CreateOrderInvoiceModalCleaned(order_dispatch_id) {
    RemoveIsInvalidClassCreateOrderInvoice();
    RemoveIsValidClassCreateOrderInvoice();
    $('.invoices_c').empty();
    $('#CreateOrderInvoiceButton').attr('onclick', `CreateOrderInvoice(${order_dispatch_id})`);
    $('#CreateOrderInvoiceAddInvoiceButton').attr('data-count', 0);
    CreateOrderInvoiceAddInvoice();
}

function CreateOrderInvoice(order_dispatch_id) {

    let formData = new FormData();
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('order_dispatch_id', order_dispatch_id);
    $('.invoices_c').find('div.invoice_c').each(function(i) {
        formData.append(`invoices[${i}][reference]`, $(this).find('input.reference_c').val());
        formData.append(`invoices[${i}][value]`, $(this).find('input.value_c').val());
        formData.append(`invoices[${i}][date]`, $(this).find('input.date_c').val() == '' ? '' : new Date($(this).find('input.date_c').val()).toISOString().slice(0, 19).replace('T', ' '));    
        for (let j = 0; j < $(this).find('input.supports_c')[0].files.length; j++) {
            formData.append(`invoices[${i}][supports][${j}]`, $(this).find('input.supports_c')[0].files[j]);
        }
    });
    formData.forEach(function(value, key) {
        console.log(key, value);
    });
    Swal.fire({
        title: '¿Desea guardar la categoria y subcategorias?',
        text: 'La categoria y subcategorias serán creados.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Invoice/Store`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    tableOrderInvoices.ajax.reload();
                    window.open(response.data.url, '_blank');
                    CreateOrderInvoiceAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableOrderInvoices.ajax.reload();
                    CreateOrderInvoiceAjaxError(xhr);
                    console.log(xhr);
                }
            });
        } else {
            toastr.info('La categoria y subcategorias no fueron creados.')
        }
    });
}

function CreateOrderInvoiceAddInvoice() {
    // Crear el nuevo elemento HTML con jQuery
    let id = $('#CreateOrderInvoiceAddInvoiceButton').attr('data-count');
    let newInvoice = $('<div>').attr({
        'id': `group-invoice${id}`,
        'class': 'form-group invoice_c'
    });
    let card = $('<div>').addClass('card collapsed-card');
    let cardHeader = $('<div>').addClass('card-header border-0 ui-sortable-handle');
    let cardTitle = $('<h3>').addClass('card-title mt-1').css({'width':'70%'});
    let inputGroup = $('<div>').addClass('input-group');
    let input = $('<input>').attr({
        'type': 'text',
        'class': 'form-control reference_c',
        'id': `reference${id}_c`,
        'name': `reference${id}_c`,
    });
    let inputGroupAppend = $('<div>').addClass('input-group-append');
    let inputGroupText = $('<span>').addClass('input-group-text');
    let paperclipIcon = $('<i>').addClass('fas fa-paperclip');
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
        'onclick': `CreateOrderInvoiceRemoveInvoice(${id})`
    });
    let timesIcon = $('<i>').addClass('fas fa-times');

    // Anidar elementos
    inputGroupText.append(paperclipIcon);
    inputGroupAppend.append(inputGroupText);
    inputGroup.append(input, inputGroupAppend);
    cardTitle.append(inputGroup);
    collapseButton.append(plusIcon);
    removeButton.append(timesIcon);
    cardTools.append(collapseButton, removeButton);
    cardHeader.append(cardTitle, cardTools);
    card.append(cardHeader);

    let cardBody = $('<div>').addClass('card-body').addClass('table-responsive').css('display', 'none');
    let rowBody = $('<div>').addClass('row');
    let colFirst = $('<div>').addClass('col-lg-6');
    let colSecond = $('<div>').addClass('col-lg-6');

    let valueForm = $('<div>').addClass('form-group');
    let valueLabel = $('<label>').attr('for', '').text('Valor');
    let valueInputGroup = $('<div>').addClass('input-group');
    let valueInput = $('<input>').attr({
        'type': 'number',
        'id': `value${id}_c`,
        'class': 'form-control value_c',
    });
    let valueInputAppend = $('<div>').addClass('input-group-append');
    let valueIcon = $('<span>').addClass('input-group-text').append($('<i>').addClass('fas fa-dollar-sign'));
    valueInputAppend.append(valueIcon);
    valueInputGroup.append(valueInput, valueInputAppend);
    valueForm.append(valueLabel, valueInputGroup);

    let dateForm = $('<div>').addClass('form-group');
    let dateLabel = $('<label>').attr('for', '').text('Fecha');
    let dateInputGroup = $('<div>').addClass('input-group').addClass('date');
    
    let dateInput = $('<input>').attr({
        'type': 'datetime-local',
        'id': `date${id}_c`,
        'name': `date${id}_c`,
        'class': 'form-control date_c',
    });
    let dateInputAppend = $('<div>').addClass('input-group-append');
    let dateIcon = $('<span>').addClass('input-group-text').append($('<i>').addClass('fas fa-calendar'));
    dateInputAppend.append(dateIcon);
    dateInputGroup.append(dateInput, dateInputAppend);
    dateForm.append(dateLabel, dateInputGroup);

    let supportsForm = $('<div>').addClass('form-group');
    let supportsLabel = $('<label>').attr('for', '').text('Soportes');
    let supportsInputGroup = $('<div>').addClass('input-group');
    let supportsInput = $('<input>').attr({
        'type': 'file',
        'id': `supports${id}_c`,
        'name': `supports${id}_c`,
        'class': 'form-control dropify supports_c',
        'accept': '.jpeg, .jpg, .png, .gif, .pdf, .txt, .docx, .xlsx, .xlsm, .xlsb, .xltx',
        'multiple': true
    });
    supportsInputGroup.append(supportsInput);
    supportsForm.append(supportsLabel, supportsInputGroup);

    colFirst.append(valueForm, dateForm);
    colSecond.append(supportsForm);
    rowBody.append(colFirst, colSecond);
    cardBody.append(rowBody);
    card.append(cardBody);

    newInvoice.append(card);

    // Agregar el nuevo elemento al elemento con clase "subcategories_c"
    $('.invoices_c').append(newInvoice);

    $(`#supports${id}_c`).dropify();

    id++;
    $('#CreateOrderInvoiceAddInvoiceButton').attr('data-count', id);
}

function CreateOrderInvoiceRemoveInvoice(index) {
    $(`#group-invoice${index}`).remove();
}

function CreateOrderInvoiceAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.info(response.message);
        $('#CreateOrderInvoiceModal').modal('hide');
    }

    if(response.status === 201) {
        toastr.success(response.message);
        $('#CreateOrderInvoiceModal').modal('hide');
    }
}

function CreateOrderInvoiceAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderInvoiceModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderInvoiceModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderInvoiceModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassCreateOrderInvoice();
        RemoveIsInvalidClassCreateOrderInvoice();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassCreateOrderInvoice(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateOrderInvoice();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateOrderInvoiceModal').modal('hide');
    }
}

function AddIsValidClassCreateOrderInvoice() {
    $('.invoices_c').find('div.invoice_c').each(function(index) {
        if (!$(this).find('input.reference_c').hasClass('is-invalid')) {
            $(this).find('input.reference_c').addClass('is-valid');
        }
        if (!$(this).find('input.value_c').hasClass('is-invalid')) {
            $(this).find('input.value_c').addClass('is-valid');
        }
        if (!$(this).find('input.date_c').hasClass('is-invalid')) {
            $(this).find('input.date_c').addClass('is-valid');
        }
    });
}

function RemoveIsValidClassCreateOrderInvoice() {
    $('.invoices_c').find('div.invoice_c').each(function(index) {
        $(this).find('input.reference_c').removeClass('is-valid');
        $(this).find('input.value_c').removeClass('is-valid');
        $(this).find('input.date_c').removeClass('is-valid');
    });
}

function AddIsInvalidClassCreateOrderInvoice(input) {
    $('.invoices_c').find('div.invoice_c').each(function(index) {
        // Agrega la clase 'is-invalid'
        if(input === `invoices.${index}.reference`) {
            if (!$(this).find('input.reference_c').hasClass('is-valid')) {
                $(this).find('input.reference_c').addClass('is-invalid');
            }
        }
        if(input === `invoices.${index}.value`) {
            if (!$(this).find('input.value_c').hasClass('is-valid')) {
                $(this).find('input.value_c').addClass('is-invalid');
            }
        }
        if(input === `invoices.${index}.date`) {
            if (!$(this).find('input.date_c').hasClass('is-valid')) {
                $(this).find('input.date_c').addClass('is-invalid');
            }
        }
    });
}

function RemoveIsInvalidClassCreateOrderInvoice() {
    $('.invoices_c').find('div.invoice_c').each(function(index) {
        $(this).find('input.reference_c').removeClass('is-invalid');
        $(this).find('input.value_c').removeClass('is-invalid');
        $(this).find('input.code_c').removeClass('is-invalid');
    });
}

