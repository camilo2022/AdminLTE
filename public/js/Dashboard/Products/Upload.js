function UploadProductModal() {
    UploadProductModalCleaned();
    $('#UploadProductModal').modal('show');
}

function UploadProductModalCleaned() {
    $('#file_u').val('');
    $('#file_u').dropify().data('dropify').destroy();
    $('#file_u').dropify().data('dropify').init();
}

function UploadProduct() {
    let formData = new FormData();
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('products', $('#file_u')[0].files[0]);

    Swal.fire({
        title: '¿Desea cargar el archivo de productos?',
        text: 'El archivo de productos se procesara y cargara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, cargar!',
        cancelButtonText: 'No, cancelar!'
    }).then((result) => {
        if (result.value) {
            $('#content').append(
                `<div class="overlay d-flex justify-content-center align-items-center" id="loading">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                </div>`
            );
            toastr.info('Por favor espere un momento a que se cargue, valide y procese el archivo porfavor.');
            $.ajax({
                url: '/Dashboard/Products/Upload',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    tableProducts.ajax.reload();
                    UploadProductAjaxSuccess(response);
                    $('#loading').remove();
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableProducts.ajax.reload();
                    UploadProductAjaxError(xhr);
                    $('#loading').remove();
                }
            });
        } else {
            toastr.info('El archivo de productos no fue cargado.')
        }
    });
}

function UploadProductAjaxSuccess(response) {
    if(response.status === 201) {
        toastr.success(response.message);
        $('#UploadProductModal').modal('hide');
    }

    $('#loading').remove();
}

function UploadProductAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#UploadProductModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#UploadProductModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#UploadProductModal').modal('hide');
    }

    if(xhr.status === 422){
        if(xhr.responseJSON.errors) {
            $.each(xhr.responseJSON.errors, function(field, messages) {
                $.each(messages, function(index, message) {
                    toastr.error(message);
                });
            });
        }
        if(xhr.responseJSON.error) {
            $.each(xhr.responseJSON.error.errors, function(field, messages) {
                $.each(messages, function(index, message) {
                    toastr.error(message);
                });
            });

            let errorInfo = [];

            for (let key in xhr.responseJSON.error.errors) {
                console.log(key);
                if (xhr.responseJSON.error.errors.hasOwnProperty(key)) {
                    let matchs = key.match(/products\.(\d+)\.(\w+)/);
                    console.log(matchs);
                    if (matchs) {
                        let rowIndex = parseInt(matchs[1]) + 1;
                        let fieldName = matchs[2];
                        let errorMessages = xhr.responseJSON.error.errors[key];
                        errorMessages.forEach(errorMessage => {
                            errorInfo.push(`Fila ${rowIndex}: ${fieldName} - ${errorMessage}`);
                        });
                    }
                    let match = key.match(/products\.(\d+)/);
                    if (match) {
                        let rowIndex = parseInt(match[1]) + 1;
                        let errorMessages = xhr.responseJSON.error.errors[key];
                        errorMessages.forEach(errorMessage => {
                            errorInfo.push(`Fila ${rowIndex}: ${errorMessage}`);
                        });
                    }
                }
            }
            let errorString = errorInfo.join('\n');
            let blob = new Blob([errorString], { type: 'text/plain' });
            let link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'errores.txt';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#UploadProductModal').modal('hide');
    }
}
