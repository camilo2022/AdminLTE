function ObservationOrderWallet(id) {
    Swal.fire({
        title: '¿Desea actualizar la observacion del pedido?',
        text: 'La observacion del pedido se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
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
        } else {
            toastr.info('La observacion del pedido no fue actualizada.')
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
