function FinishOrderPacked(id) {
    Swal.fire({
        title: '¿Desea finalizar la orden de alistamiento y empacado?',
        text: 'La orden de alistamiento y empacado será finalizada.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, finalizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Packed/Finish`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id
                },
                success: function(response) {
                    window.location.href = response.data.url;
                    FinishOrderPackedAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    FinishOrderPackedAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La orden de alistamiento y empacado no fue finalizada.')
        }
    });
}

function FinishOrderPackedAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.success(response.message);
    }
}

function FinishOrderPackedAjaxError(xhr) {
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
