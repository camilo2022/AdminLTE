function ObservationOrderWallet(id) {
    $.ajax({
        url: `/Dashboard/Orders/Wallet/Observation`,
        type: 'PUT',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'id': id,
            'wallet_observation': $('#wallet_observation').val()
        },
        success: function (response) {
            ObservationOrderWalletAjaxSuccess(response);
        },
        error: function (xhr, textStatus, errorThrown) {
            ObservationOrderWalletAjaxError(xhr);
        }
    });
}

function ObservationOrderWalletAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
    }

    if (response.status === 200) {
        toastr.success(response.message);
    }
}

function ObservationOrderWalletAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
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
    }
}
