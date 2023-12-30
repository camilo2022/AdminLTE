function CreateModelModal() {
    $.ajax({
        url: `/Dashboard/Models/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            CreateModelModalCleaned();
            CreateModelAjaxSuccess(response);
            $('#CreateModelModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            CreateModelAjaxError(xhr);
        }
    });
}

function CreateModelModalCleaned() {
    RemoveIsValidClassCreateModel();
    RemoveIsInvalidClassCreateModel();

    $('#name_c').val('');
    $('#code_c').val('');
    $('#description_c').val('');
}

function CreateModel() {
    Swal.fire({
        title: '¿Desea guardar la modelo de producto?',
        text: 'El modelo de producto será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Models/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                    'code': $('#code_c').val(),
                    'description': $('#description_c').val()
                },
                success: function (response) {
                    tableModels.ajax.reload();
                    CreateModelAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableModels.ajax.reload();
                    CreateModelAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El modelo de producto no fue creado.')
        }
    });
}

function CreateModelAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#CreateModelModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateModelModal').modal('hide');
    }
}

function CreateModelAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateModelModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateModelModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateModelModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateModel();
        RemoveIsInvalidClassCreateModel();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateModel(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateModel();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateModelModal').modal('hide');
    }
}

function AddIsValidClassCreateModel() {
    if (!$('#name_c').hasClass('is-invalid')) {
        $('#name_c').addClass('is-valid');
    }
    if (!$('#code_c').hasClass('is-invalid')) {
        $('#code_c').addClass('is-valid');
    }
    if (!$('#description_c').hasClass('is-invalid')) {
        $('#description_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateModel() {
    $('#name_c').removeClass('is-valid');
    $('#code_c').removeClass('is-valid');
    $('#description_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateModel(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).removeClass('is-valid');
    }
    $(`#${input}_c`).addClass('is-invalid');
}

function RemoveIsInvalidClassCreateModel() {
    $('#name_c').removeClass('is-invalid');
    $('#code_c').removeClass('is-invalid');
    $('#description_c').removeClass('is-valid');
}
