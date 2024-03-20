function OpenOrderPackedPackage(id, status = true) {
    Swal.fire({
        title: '¿Desea abrir el empaque de la orden de alistamiento y empacado?',
        text: 'El empaque de la orden de alistamiento y empacado se abrirá.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, abrir!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Packed/Packages/Open`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id,
                },
                success: function(response) {
                    status ? window.location.href = response.data.url : $('#IndexOrderPackedDetail').trigger('click') ;
                    OpenOrderPackedPackageAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    OpenOrderPackedPackageAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El empaque de la orden de alistamiento y empacado no fue abierto.')
        }
    });
}

function OpenOrderPackedPackageAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.success(response.message);
    }
}

function OpenOrderPackedPackageAjaxError(xhr) {
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
