function CreateReturnTypeModal() {
    $.ajax({
        url: `/Dashboard/ReturnTypes/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            CreateReturnTypeModalCleaned();
            CreateReturnTypeAjaxSuccess(response);
            $('#CreateReturnTypeModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            CreateReturnTypeAjaxError(xhr);
        }
    });
}

function CreateReturnTypeModalCleaned() {
    RemoveIsValidClassCreateReturnType();
    RemoveIsInvalidClassCreateReturnType();

    $('#name_c').val('');
    $('#code_c').val('');
}

function CreateReturnType() {
    Swal.fire({
        title: '¿Desea guardar el tipo de devolucion?',
        text: 'El tipo de devolucion será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/ReturnTypes/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                },
                success: function(response) {
                    tableReturnTypes.ajax.reload();
                    CreateReturnTypeAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableReturnTypes.ajax.reload();
                    CreateReturnTypeAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El tipo de devolucion no fue creado.')
        }
    });
}

function CreateReturnTypeAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.info(response.message);
        $('#CreateReturnTypeModal').modal('hide');
    }

    if(response.status === 201) {
        toastr.success(response.message);
        $('#CreateReturnTypeModal').modal('hide');
    }
}

function CreateReturnTypeAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateReturnTypeModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateReturnTypeModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateReturnTypeModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassCreateReturnType();
        RemoveIsInvalidClassCreateReturnType();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassCreateReturnType(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateReturnType();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateReturnTypeModal').modal('hide');
    }
}

function AddIsValidClassCreateReturnType() {
    if (!$('#name_c').hasClass('is-invalid')) {
      $('#name_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateReturnType() {
    $('#name_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateReturnType(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateReturnType() {
    $('#name_c').removeClass('is-invalid');
}
