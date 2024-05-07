function RemovePaymentOrderSeller(id) {
    Swal.fire({
        title: '¿Desea remover el pago al pedido?',
        text: 'El pago será removido al pedido.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, cancelar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Seller/RemovePayment`,
                type: 'DELETE',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id
                },
                success: function(response) {
                    tableOrderSellerPayments.ajax.reload();
                    RemovePaymentOrderSellerAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    RemovePaymentOrderSellerAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El pago no fue removido al pedido.')
        }
    });
}

function RemovePaymentOrderSellerAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.info(response.message);
    }

    if(response.status === 204) {
        toastr.success(response.message);
    }
}

function RemovePaymentOrderSellerAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
    }

    if(xhr.status === 422){
        $.each(xhr.responseJSON.errors, function(field, messages) {
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
    }
}
