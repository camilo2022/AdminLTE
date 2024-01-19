function EditCorreriaAndCollectionModal(id) {
    $.ajax({
        url: `/Dashboard/CorreriasAndCollections/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            tableCorreriasAndCollections.ajax.reload();
            EditCorreriaAndCollectionModalCleaned(response.data);
            EditCorreriaAndCollectionAjaxSuccess(response);
            $('#EditCorreriaAndCollectionModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            tableCorreriasAndCollections.ajax.reload();
            EditCorreriaAndCollectionAjaxError(xhr);
        }
    });
}

function EditCorreriaAndCollectionModalCleaned(correria) {
    RemoveIsValidClassEditCorreriaAndCollection();
    RemoveIsInvalidClassEditCorreriaAndCollection();

    $('#EditCorreriaAndCollectionButton').attr('onclick', `EditCorreriaAndCollection(${correria.id})`);

    $("#name_e").val(correria.name);
    $("#code_e").val(correria.code);
    $("#start_date_e").val(moment(correria.start_date).format('MM/DD/YYYY'));
    $("#end_date_e").val(moment(correria.end_date).format('MM/DD/YYYY'));
}

function EditCorreriaAndCollection(id) {
    Swal.fire({
        title: '¿Desea actualizar la correria?',
        text: 'La correria se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/CorreriasAndCollections/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id,
                    'name': $("#name_e").val(),
                    'code': $("#code_e").val(),
                    'start_date': $("#start_date_e").val(),
                    'end_date': $("#end_date_e").val()
                },
                success: function(response) {
                    tableCorreriasAndCollections.ajax.reload();
                    EditCorreriaAndCollectionAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableCorreriasAndCollections.ajax.reload();
                    EditCorreriaAndCollectionAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La correria no fue actualizada.')
        }
    });
}

function EditCorreriaAndCollectionAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
        $('#EditCorreriaAndCollectionModal').modal('hide');
    }

    if(response.status === 204) {
        toastr.info(response.message);
        $('#PasswordCorreriaModal').modal('hide');
    }
}

function EditCorreriaAndCollectionAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditCorreriaAndCollectionModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditCorreriaAndCollectionModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditCorreriaAndCollectionModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassEditCorreriaAndCollection();
        RemoveIsInvalidClassEditCorreriaAndCollection();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassEditCorreriaAndCollection(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditCorreriaAndCollection();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditCorreriaAndCollectionModal').modal('hide');
    }
}

function AddIsValidClassEditCorreriaAndCollection() {
    if (!$('#name_e').hasClass('is-invalid')) {
      $('#name_e').addClass('is-valid');
    }
    if (!$('#code_e').hasClass('is-invalid')) {
      $('#code_e').addClass('is-valid');
    }
    if (!$('#start_date_e').hasClass('is-invalid')) {
      $('#start_date_e').addClass('is-valid');
    }
    if (!$('#end_date_e').hasClass('is-invalid')) {
      $('#end_date_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditCorreriaAndCollection() {
    $('#name_e').removeClass('is-valid');
    $('#code_e').removeClass('is-valid');
    $('#start_date_e').removeClass('is-valid');
    $('#end_date_e').removeClass('is-valid');
}

function AddIsInvalidClassEditCorreriaAndCollection(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditCorreriaAndCollection() {
    $('#name_e').removeClass('is-invalid');
    $('#code_e').removeClass('is-invalid');
    $('#start_date_e').removeClass('is-invalid');
    $('#end_date_e').removeClass('is-invalid');
}

$('#start_date_edit').datetimepicker({
    format: 'L'
});

$('#end_date_edit').datetimepicker({
    format: 'L'
});
