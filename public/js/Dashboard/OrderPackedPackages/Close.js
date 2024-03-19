function CloseOrderPackedPackage(id, status = true) {
    Swal.fire({
        title: '¿Desea cerrar el empaque de la orden de alistamiento y empacado?',
        text: 'El empaque de la orden de alistamiento y empacado será cerrado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, cerrar!',
        cancelButtonText: 'No, cancelar!',
        html:`<div class="input-group">
            <input type="number" class="form-control" id="weight_val" name="weight_val" placeholder="Ingrese el peso.">
            <select class="form-control" id="weight_uni" name="weight_uni">
                <option value="">Seleccione</option>
                <option value=" KG">KG</option>
                <option value=" LB">LB</option>
                <option value=" OZ">OZ</option>
            </select>
        </div>`,
        footer: '<div class="text-center">Ingresa el peso y selecciona la unidad de medida.</div>'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Packed/Packages/Close`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id,
                    'weight': $('#weight_val').val() + $('#weight_uni').val()
                },
                success: function(response) {
                    status ? window.location.href = response.data.url : $('#IndexOrderPackedDetail').trigger('click') ;
                    CloseOrderPackedPackageAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    CloseOrderPackedPackageAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El empaque de la orden de alistamiento y empacado no fue eliminada.')
        }
    });
}

function CloseOrderPackedPackageAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.success(response.message);
    }
}

function CloseOrderPackedPackageAjaxError(xhr) {
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
