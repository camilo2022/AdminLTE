function EditTransporterModal(id) {
    $.ajax({
        url: `/Dashboard/Transporters/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            tableTransporters.ajax.reload();
            EditTransporterModalCleaned(response.data);
            EditTransporterAjaxSuccess(response);
            $('#EditTransporterModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            tableTransporters.ajax.reload();
            EditTransporterAjaxError(xhr);
        }
    });
}

function EditTransporterModalCleaned(transporter) {
    RemoveIsValidClassEditTransporter();
    RemoveIsInvalidClassEditTransporter();

    $('#EditTransporterButton').attr('onclick', `EditTransporter(${transporter.id})`);

    $('#name_e').val(transporter.name);
    $('#document_number_e').val(transporter.document_number);
    $('#telephone_number_e').val(transporter.telephone_number);
    $('#email_e').val(transporter.email);
}

function EditTransporter(id) {
    Swal.fire({
        title: '¿Desea actualizar la transportadora?',
        text: 'La transportadora se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Transporters/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_e').val(),
                    'document_number': $('#document_number_e').val(),
                    'telephone_number': $('#telephone_number_e').val(),
                    'email': $('#email_e').val(),
                },
                success: function(response) {
                    tableTransporters.ajax.reload();
                    EditTransporterAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableTransporters.ajax.reload();
                    EditTransporterAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La transportadora no fue actualizado.')
        }
    });
}

function EditTransporterAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
        $('#EditTransporterModal').modal('hide');
    }
}

function EditTransporterAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditTransporterModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditTransporterModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditTransporterModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassEditTransporter();
        RemoveIsInvalidClassEditTransporter();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassEditTransporter(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditTransporter();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditTransporterModal').modal('hide');
    }
}

function AddIsValidClassEditTransporter() {
    if (!$('#name_e').hasClass('is-invalid')) {
        $('#name_e').addClass('is-valid');
    }
    if (!$('#document_number_e').hasClass('is-invalid')) {
        $('#document_number_e').addClass('is-valid');
    }
    if (!$('#telephone_number_e').hasClass('is-invalid')) {
        $('#telephone_number_e').addClass('is-valid');
    }
    if (!$('#email_e').hasClass('is-invalid')) {
        $('#email_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditTransporter() {
    $('#name_e').removeClass('is-valid');
    $('#document_number_e').removeClass('is-valid');
    $('#telephone_number_e').removeClass('is-valid');
    $('#email_e').removeClass('is-valid');
}

function AddIsInvalidClassEditTransporter(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditTransporter() {
    $('#name_e').removeClass('is-invalid');
    $('#document_number_e').removeClass('is-invalid');
    $('#telephone_number_e').removeClass('is-invalid');
    $('#email_e').removeClass('is-invalid');
}
