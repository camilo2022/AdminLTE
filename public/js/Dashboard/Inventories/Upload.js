function UploadInventoryModal() {
    UploadInventoryModalCleaned();
    $('#UploadInventoryModal').modal('show');
}

function UploadInventoryModalCleaned() {
    $('#file_u').val('');
    $('#file_u').dropify().data('dropify').destroy();
    $('#file_u').dropify().data('dropify').init();
}

function UploadInventory() {
    let formData = new FormData();
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('inventories', $('#file_u')[0].files[0]);

    Swal.fire({
        title: '¿Desea cargar el archivo de inventarios?',
        text: 'El archivo de inventarios se procesara y cargara.',
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
            toastr.info('Por favor espere un momento a que se cargue, valide y procese el archivo.');
            $.ajax({
                url: '/Dashboard/Inventories/Upload',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    tableInventories.ajax.reload();
                    UploadInventoryAjaxSuccess(response);
                    $('#loading').remove();
                },
                error: function(xhr, textStatus, errorThrown) {
                    UploadInventoryAjaxError(xhr);
                    $('#loading').remove();
                }
            });
        } else {
            toastr.info('El archivo de inventarios no fue cargado.')
        }
    });
}

function UploadInventoryAjaxSuccess(response) {
    if(response.status === 201) {
        toastr.success(response.message);
        $('#UploadInventoryModal').modal('hide');
    }

    $('#loading').remove();
}

function UploadInventoryAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#UploadInventoryModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#UploadInventoryModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#UploadInventoryModal').modal('hide');
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

            $.each(xhr.responseJSON.error.errors, function (key, value) {
                if (xhr.responseJSON.error.errors.hasOwnProperty(key)) {
                    let matchs = key.match(/inventories\.(\d+)\.(\w+)/);
                    if (matchs) {
                        let rowIndex = parseInt(matchs[1]) + 1;
                        let fieldName = matchs[2];
                        let errorMessages = xhr.responseJSON.error.errors[key];
                        $.each(errorMessages, function (_, errorMessage) {
                            errorInfo.push(`Fila ${rowIndex}: ${fieldName} - ${errorMessage}`);
                        });
                    }
                    let match = key.match(/inventories\.(\d+)/);
                    if (match) {
                        let rowIndex = parseInt(match[1]) + 1;
                        let errorMessages = xhr.responseJSON.error.errors[key];
                        $.each(errorMessages, function (index, errorMessage) {
                            errorInfo.push(`Fila ${rowIndex}: ${errorMessage}`);
                        });
                    }
                }
            });

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
        $('#UploadInventoryModal').modal('hide');
    }
}
