function EditPackageModal(id) {
    $.ajax({
        url: `/Dashboard/Packages/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            EditPackageModalCleaned(response.data);
            EditPackageAjaxSuccess(response);
            $('#EditPackageModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            EditPackageAjaxError(xhr);
        }
    });
}

function EditPackageModalCleaned(package) {
    RemoveIsValidClassEditPackage();
    RemoveIsInvalidClassEditPackage();

    $('#EditPackageButton').attr('onclick', `EditPackage(${package.id})`);

    $("#name_e").val(package.name);
    $("#code_e").val(package.code);
}

function EditPackage(id) {
    Swal.fire({
        title: '¿Desea actualizar el tipo de empaque?',
        text: 'El tipo de empaque se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Packages/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id,
                    'name': $("#name_e").val(),
                    'code': $("#code_e").val()
                },
                success: function(response) {
                    tablePackages.ajax.reload();
                    EditPackageAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tablePackages.ajax.reload();
                    EditPackageAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El tipo de empaque no fue actualizado.')
        }
    });
}

function EditPackageAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
        $('#EditPackageModal').modal('hide');
    }
}

function EditPackageAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error.message);
        $('#EditPackageModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditPackageModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error.message);
        $('#EditPackageModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassEditPackage();
        RemoveIsInvalidClassEditPackage();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassEditPackage(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditPackage();
    }

    if(xhr.status === 500){
        if(xhr.responseJSON.error) {
            toastr.error(xhr.responseJSON.error.message);
        }

        if(xhr.responseJSON.message) {
            toastr.error(xhr.responseJSON.message);
        }
        $('#EditPackageModal').modal('hide');
    }
}

function AddIsValidClassEditPackage() {
    if (!$('#name_e').hasClass('is-invalid')) {
      $('#name_e').addClass('is-valid');
    }
    if (!$('#code_e').hasClass('is-invalid')) {
      $('#code_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditPackage() {
    $('#name_e').removeClass('is-valid');
    $('#code_e').removeClass('is-valid');
}

function AddIsInvalidClassEditPackage(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).removeClass('is-valid');
    }
    $(`#${input}_e`).addClass('is-invalid');
}

function RemoveIsInvalidClassEditPackage() {
    $('#name_e').removeClass('is-invalid');
    $('#code_e').removeClass('is-invalid');
}