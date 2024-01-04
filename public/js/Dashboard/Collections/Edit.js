function EditCollectionModal(id) {
    $.ajax({
        url: `/Dashboard/Correrias/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            tableCollections.ajax.reload();
            EditCollectionModalCleaned(response.data);
            EditCollectionAjaxSuccess(response);
            $('#EditCollectionModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            tableCollections.ajax.reload();
            EditCollectionAjaxError(xhr);
        }
    });
}

function EditCollectionModalCleaned(collection) {
    RemoveIsValidClassEditCollection();
    RemoveIsInvalidClassEditCollection();

    $('#EditCollectionButton').attr('onclick', `EditCollection(${collection.id})`);

    $("#name_e").val(collection.name);
    $("#code_e").val(collection.code);
}

function EditCollection(id) {
    Swal.fire({
        title: '¿Desea actualizar la collection?',
        text: 'La collection se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Correrias/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id,
                    'name': $("#name_e").val(),
                    'code': $("#code_e").val()
                },
                success: function(response) {
                    tableCollections.ajax.reload();
                    EditCollectionAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableCollections.ajax.reload();
                    EditCollectionAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La collection no fue actualizada.')
        }
    });
}

function EditCollectionAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
        $('#EditCollectionModal').modal('hide');
    }

    if(response.status === 204) {
        toastr.info(response.message);
        $('#PasswordCorreriaModal').modal('hide');
    }
}

function EditCollectionAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditCollectionModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditCollectionModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditCollectionModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassEditCollection();
        RemoveIsInvalidClassEditCollection();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassEditCollection(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditCollection();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditCollectionModal').modal('hide');
    }
}

function AddIsValidClassEditCollection() {
    if (!$('#name_e').hasClass('is-invalid')) {
      $('#name_e').addClass('is-valid');
    }
    if (!$('#code_e').hasClass('is-invalid')) {
      $('#code_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditCollection() {
    $('#name_e').removeClass('is-valid');
    $('#code_e').removeClass('is-valid');
}

function AddIsInvalidClassEditCollection(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditCollection() {
    $('#name_e').removeClass('is-invalid');
    $('#code_e').removeClass('is-invalid');
}
