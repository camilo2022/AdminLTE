function CreatePackageModal() {
    $.ajax({
        url: `/Dashboard/Packages/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            CreatePackageModalCleaned();
            CreatePackageAjaxSuccess(response);
            $('#CreatePackageModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            CreatePackageAjaxError(xhr);
        }
    });
}

function CreatePackageModalCleaned() {
    RemoveIsValidClassCreatePackage();
    RemoveIsInvalidClassCreatePackage();

    $('#name_c').val('');
    $('#code_c').val('');
}

function CreatePackage() {
    Swal.fire({
        title: '¿Desea guardar el tipo de empaque?',
        text: 'El tipo de empaque será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Packages/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                    'code': $('#code_c').val(),
                    'start_date': $('#start_date_c').val(),
                    'end_date': $('#end_date_c').val()
                },
                success: function(response) {
                    tablePackages.ajax.reload();
                    CreatePackageAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tablePackages.ajax.reload();
                    CreatePackageAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El tipo de empaque no fue creado.')
        }
    });
}

function CreatePackageAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.info(response.message);
        $('#CreatePackageModal').modal('hide');
    }

    if(response.status === 201) {
        toastr.success(response.message);
        $('#CreatePackageModal').modal('hide');
    }
}

function CreatePackageAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error.message);
        $('#CreatePackageModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreatePackageModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error.message);
        $('#CreatePackageModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassCreatePackage();
        RemoveIsInvalidClassCreatePackage();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassCreatePackage(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreatePackage();
    }

    if(xhr.status === 500){
        if(xhr.responseJSON.error) {
            toastr.error(xhr.responseJSON.error.message);
        }

        if(xhr.responseJSON.message) {
            toastr.error(xhr.responseJSON.message);
        }
        $('#CreatePackageModal').modal('hide');
    }
}

function AddIsValidClassCreatePackage() {
    if (!$('#name_c').hasClass('is-invalid')) {
      $('#name_c').addClass('is-valid');
    }
    if (!$('#code_c').hasClass('is-invalid')) {
      $('#code_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreatePackage() {
    $('#name_c').removeClass('is-valid');
    $('#code_c').removeClass('is-valid');
}

function AddIsInvalidClassCreatePackage(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).removeClass('is-valid');
    }
    $(`#${input}_c`).addClass('is-invalid');
}

function RemoveIsInvalidClassCreatePackage() {
    $('#name_c').removeClass('is-invalid');
    $('#code_c').removeClass('is-invalid');
}
