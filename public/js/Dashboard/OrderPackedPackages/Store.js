function StoreOrderPackedPackage(id, packageTypes) {
    let html = `<select class="form-control select2" id="package_type_id_c">
        <option value="">Seleccione</option>`;
    
    $.each(packageTypes, function(index, packageType) {
        html += `<option value="${packageType.id}">${packageType.name}</option>`;
    });

    html += `</select>` 
    Swal.fire({
        title: '¿Desea crear un empaque para alistar y empacar la orden de desacho?',
        text: 'El empaque será creado a la orden de despacho.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, empacar!',
        cancelButtonText: 'No, cancelar!',
        html: html,
        footer: '<div class="text-center">Selecciona un empaque para crearlo y poder empacar la mercancia de la orden de despacho.</div>'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Packed/Packages/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id,
                    'package_type_id': $('#package_type_id_c').val()
                },
                success: function(response) {
                    window.location.href = response.data.url;
                    StoreOrderPackedPackageAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    StoreOrderPackedPackageAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El empaque de la orden de despacho no fue creado.')
        }
    });
}

function StoreOrderPackedPackageAjaxSuccess(response) {
    if(response.status === 201) {
        toastr.success(response.message);
    }
}

function StoreOrderPackedPackageAjaxError(xhr) {
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
