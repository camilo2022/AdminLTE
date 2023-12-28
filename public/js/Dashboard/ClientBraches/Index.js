function IndexClientBranchModal(client_id) {
    $.ajax({
        url: `/Dashboard/Clients/Branches/Index`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'client_id': client_id
        },
        success: function (response) {
            tableClients.ajax.reload();
            IndexClientBranchModalCleaned(response.data);
            tableClientBranches.ajax.reload();
            IndexClientBranchAjaxSuccess(response);
            $('#IndexClientBranchModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tableClientBranches.ajax.reload();
            IndexClientBranchAjaxError(xhr);
        }
    });
}

function IndexClientBranchModalCleaned(client) {
    $('#IndexClientBranchButton').attr('data-client_id', client.id);
}

function IndexClientBranchAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
        $('#IndexClientBranchModal').modal('hide');
    }

    if(response.status === 204) {
        toastr.info(response.message);
        $('#IndexClientBranchModal').modal('hide');
    }
}

function IndexClientBranchAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#IndexClientBranchModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#IndexClientBranchModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#IndexClientBranchModal').modal('hide');
    }

    if (xhr.status === 422) {
        $.each(xhr.responseJSON.errors, function (field, messages) {
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#IndexClientBranchModal').modal('hide');
    }
}
