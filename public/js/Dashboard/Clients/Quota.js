function QuotaClientModal(id) {
    $.ajax({
        url: `/Dashboard/Clients/Quota/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            tableClients.ajax.reload();
            QuotaClientModalCleaned(response.data);
            QuotaClientAjaxSuccess(response);
            $('#QuotaClientModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tableClients.ajax.reload();
            QuotaClientAjaxError(xhr);
        }
    });
}

function QuotaClientModalCleaned(client) {
    RemoveIsValidClassQuotaClient();
    RemoveIsInvalidClassQuotaClient();

    $('#QuotaClientButton').attr('onclick', `QuotaClient(${client.id})`);
    $('#QuotaClientButton').attr('data-id', client.id);

    $('#name_c_q').val(client.name);
    $('#document_number_c_q').val(client.document_number);
    $('#quota_c_q').val(client.quota);
}

function QuotaClient(id) {
    Swal.fire({
        title: '¿Desea actualizar el cupo disponible del cliente?',
        text: 'El el cupo disponible del cliente se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Clients/Quota/Query/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'quota': $('#quota_c_q').val(),
                },
                success: function (response) {
                    tableClients.ajax.reload();
                    QuotaClientAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableClients.ajax.reload();
                    QuotaClientAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El el cupo disponible del cliente no fue actualizado.')
        }
    });
}

function QuotaClientAjaxSuccess(response) {
    if (response.status === 200) {
        toastr.success(response.message);
        $('#QuotaClientModal').modal('hide');
    }
}

function QuotaClientAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#QuotaClientModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#QuotaClientModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#QuotaClientModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassQuotaClient();
        RemoveIsInvalidClassQuotaClient();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassQuotaClient(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassQuotaClient();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#QuotaClientModal').modal('hide');
    }
}

function AddIsValidClassQuotaClient() {
    if (!$('#name_c_q').hasClass('is-invalid')) {
        $('#name_c_q').addClass('is-valid');
    }
    if (!$('#document_number_c_q').hasClass('is-invalid')) {
        $('#document_number_c_q').addClass('is-valid');
    }
    if (!$('#quota_c_q').hasClass('is-invalid')) {
        $('#quota_c_q').addClass('is-valid');
    }
}

function RemoveIsValidClassQuotaClient() {
    $('#name_c_q').removeClass('is-valid');
    $('#document_number_c_q').removeClass('is-valid');
    $('#quota_c_q').removeClass('is-valid');
}

function AddIsInvalidClassQuotaClient(input) {
    if (!$(`#${input}_c_q`).hasClass('is-valid')) {
        $(`#${input}_c_q`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassQuotaClient() {
    $('#name_c_q').removeClass('is-invalid');
    $('#document_number_c_q').removeClass('is-invalid');
    $('#quota_c_q').removeClass('is-invalid');
}
