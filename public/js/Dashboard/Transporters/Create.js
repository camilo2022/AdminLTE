function CreateTransporterModal() {
    $.ajax({
        url: `/Dashboard/Transporters/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            CreateTransporterModalCleaned();
            CreateTransporterAjaxSuccess(response);
            $('#CreateTransporterModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            CreateTransporterAjaxError(xhr);
        }
    });
}

function CreateTransporterModalCleaned() {
    RemoveIsValidClassCreateTransporter();
    RemoveIsInvalidClassCreateTransporter();

    $('#name_c').val('');
    $('#document_number_c').val('');
    $('#telephone_number_c').val('');
    $('#email_c').val('');
}

function CreateTransporter() {
    Swal.fire({
        title: '¿Desea guardar la transportadora?',
        text: 'La transportadora será creada.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Transporters/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                    'document_number': $('#document_number_c').val(),
                    'telephone_number': $('#telephone_number_c').val(),
                    'email': $('#email_c').val(),
                },
                success: function(response) {
                    tableTransporters.ajax.reload();
                    CreateTransporterAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableTransporters.ajax.reload();
                    CreateTransporterAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La transportadora no fue creada.')
        }
    });
}

function CreateTransporterAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.info(response.message);
        $('#CreateTransporterModal').modal('hide');
    }

    if(response.status === 201) {
        toastr.success(response.message);
        $('#CreateTransporterModal').modal('hide');
    }
}

function CreateTransporterAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateTransporterModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateTransporterModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateTransporterModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassCreateTransporter();
        RemoveIsInvalidClassCreateTransporter();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassCreateTransporter(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateTransporter();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateTransporterModal').modal('hide');
    }
}

function AddIsValidClassCreateTransporter() {
    if (!$('#name_c').hasClass('is-invalid')) {
      $('#name_c').addClass('is-valid');
    }
    if (!$('#document_number_c').hasClass('is-invalid')) {
      $('#document_number_c').addClass('is-valid');
    }
    if (!$('#telephone_number_c').hasClass('is-invalid')) {
        $('#telephone_number_c').addClass('is-valid');
    }
    if (!$('#email_c').hasClass('is-invalid')) {
        $('#email_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateTransporter() {
    $('#name_c').removeClass('is-valid');
    $('#document_number_c').removeClass('is-valid');
    $('#telephone_number_c').removeClass('is-valid');
    $('#email_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateTransporter(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateTransporter() {
    $('#name_c').removeClass('is-invalid');
    $('#document_number_c').removeClass('is-invalid');
    $('#telephone_number_c').removeClass('is-invalid');
    $('#email_c').removeClass('is-invalid');
}
