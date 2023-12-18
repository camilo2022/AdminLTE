function ShowTransferModal(id) {
    $.ajax({
        url: `/Dashboard/Transfers/Show/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            tableTransfers.ajax.reload();
            ShowTransferModalCleaned(response.data);
            tableTransferDetails.ajax.reload();
            ShowTransferAjaxSuccess(response);
            $('#ShowTransferModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tableTransfers.ajax.reload();
            ShowTransferAjaxError(xhr);
        }
    });
}

function ShowTransferModalCleaned(transfer) {
    $('#ShowTransferButton').attr('data-id', transfer.id);
    $('#ShowTransferButton').attr('data-from_warehouse_id', transfer.from_warehouse_id);
    /*  $("#name_s").val(data.transfer.name);
    $("#code_s").val(data.transfer.code);
    $('#users_s').empty(); */
}


function ShowTransferAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
        $('#ShowTransferModal').modal('hide');
    }

    if(response.status === 204) {
        toastr.info(response.message);
        $('#ShowTransferModal').modal('hide');
    }
}

function ShowTransferAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowTransferModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowTransferModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowTransferModal').modal('hide');
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
        $('#ShowTransferModal').modal('hide');
    }
}
