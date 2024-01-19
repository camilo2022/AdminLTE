function EditReturnTypeModal(id) {
    $.ajax({
        url: `/Dashboard/ReturnTypes/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            tableReturnTypes.ajax.reload();
            EditReturnTypeModalCleaned(response.data);
            EditReturnTypeAjaxSuccess(response);
            $('#EditReturnTypeModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            tableReturnTypes.ajax.reload();
            EditReturnTypeAjaxError(xhr);
        }
    });
}

function EditReturnTypeModalCleaned(returnType) {
    RemoveIsValidClassEditReturnType();
    RemoveIsInvalidClassEditReturnType();

    $('#EditReturnTypeButton').attr('onclick', `EditReturnType(${returnType.id})`);

    $("#name_e").val(returnType.name);
}

function EditReturnType(id) {
    Swal.fire({
        title: '¿Desea actualizar el tipo de devolucion?',
        text: 'El tipo de devolucion se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/ReturnTypes/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id,
                    'name': $("#name_e").val(),
                    'code': $("#code_e").val()
                },
                success: function(response) {
                    tableReturnTypes.ajax.reload();
                    EditReturnTypeAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableReturnTypes.ajax.reload();
                    EditReturnTypeAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El tipo de devolucion no fue actualizado.')
        }
    });
}

function EditReturnTypeAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.info(response.message);
        $('#EditReturnTypeModal').modal('hide');
    }

    if(response.status === 200) {
        toastr.success(response.message);
        $('#EditReturnTypeModal').modal('hide');
    }
}

function EditReturnTypeAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditReturnTypeModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditReturnTypeModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditReturnTypeModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassEditReturnType();
        RemoveIsInvalidClassEditReturnType();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassEditReturnType(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditReturnType();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditReturnTypeModal').modal('hide');
    }
}

function AddIsValidClassEditReturnType() {
    if (!$('#name_e').hasClass('is-invalid')) {
      $('#name_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditReturnType() {
    $('#name_e').removeClass('is-valid');
}

function AddIsInvalidClassEditReturnType(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditReturnType() {
    $('#name_e').removeClass('is-invalid');
}
