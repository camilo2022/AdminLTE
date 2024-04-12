function ApproveOrderSeller(id, status = true) {
    Swal.fire({
        title: '¿Desea aprobar el pedido?',
        text: 'El pedido será aprobado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, aprobar!',
        cancelButtonText: 'No, cancelar!',
        html: `<div class="icheck-primary"><input type="checkbox" id="email_a" name="email_a"><label for="email_a">¿Enviar correo electronico?</label></div>
        <div class="icheck-primary"><input type="checkbox" id="download_a" name="download_a"><label for="download_a">¿Descargar pdf del pedido?</label></div>`,
        footer: '<div class="text-center">Puedes notificar via correo electronico al correo registrado del cliente y de la surcursal la confirmacion del pedido. Ademas puedes descargarlo en formato pdf para enviarselo por whatsapp.</div>'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Seller/Approve`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id,
                    'email': $('#email_a').is(':checked'),
                    'download': $('#download_a').is(':checked')
                },
                success: function(response) {
                    status ? tableOrderSellers.ajax.reload() : location.reload() ;
                    if(response.data.urlEmail !== null) {
                        window.location.href = response.data.urlEmail;
                    }
                    if(response.data.urlDownload !== null) {
                        window.open(response.data.urlDownload, '_blank');
                    }
                    ApproveOrderSellerAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    ApproveOrderSellerAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El pedido seleccionada no fue aprobado.')
        }
    });
}

function ApproveOrderSellerAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
    }

    if(response.status === 422) {
        toastr.warning(response.message);
    }
}

function ApproveOrderSellerAjaxError(xhr) {
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
                if(field === 'quota_available') {
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Cupo disponible insuficiente',
                        body: message
                    });
                } else {
                    toastr.error(message);
                }
            });
        });
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
    }
}
