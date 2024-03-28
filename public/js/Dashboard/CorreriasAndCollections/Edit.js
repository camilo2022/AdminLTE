function EditCorreriaAndCollectionModal(id) {
    $.ajax({
        url: `/Dashboard/CorreriasAndCollections/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            EditCorreriaAndCollectionModalCleaned(response.data);
            EditCorreriaAndCollectionAjaxSuccess(response);
            $('#EditCorreriaAndCollectionModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
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
    $("#proyection_stop_warehouse_e").val(correria.collection.proyection_stop_warehouse);
    $("#number_samples_include_suitcase_e").val(correria.collection.number_samples_include_suitcase);
    $("#date_definition_start_pilots_e").val(moment(correria.collection.date_definition_start_pilots).format('MM/DD/YYYY'));
    $("#date_definition_start_samples_e").val(moment(correria.collection.date_definition_start_samples).format('MM/DD/YYYY'));
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
                    'end_date': $("#end_date_e").val(),
                    'date_definition_start_pilots': $('#date_definition_start_pilots_e').val(),
                    'date_definition_start_samples': $('#date_definition_start_samples_e').val(),
                    'proyection_stop_warehouse': $('#proyection_stop_warehouse_e').val(),
                    'number_samples_include_suitcase': $('#number_samples_include_suitcase_e').val()
                },
                success: function(response) {
                    tableCorreriasAndCollections.ajax.reload();
                    EditCorreriaAndCollectionAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
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
    if (!$('#date_definition_start_pilots_e').hasClass('is-invalid')) {
      $('#date_definition_start_pilots_e').addClass('is-valid');
    }
    if (!$('#date_definition_start_samples_e').hasClass('is-invalid')) {
      $('#date_definition_start_samples_e').addClass('is-valid');
    }
    if (!$('#proyection_stop_warehouse_e').hasClass('is-invalid')) {
      $('#proyection_stop_warehouse_e').addClass('is-valid');
    }
    if (!$('#number_samples_include_suitcase_e').hasClass('is-invalid')) {
      $('#number_samples_include_suitcase_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditCorreriaAndCollection() {
    $('#name_e').removeClass('is-valid');
    $('#code_e').removeClass('is-valid');
    $('#start_date_e').removeClass('is-valid');
    $('#end_date_e').removeClass('is-valid');
    $('#date_definition_start_pilots_e').removeClass('is-valid');
    $('#date_definition_start_samples_e').removeClass('is-valid');
    $('#proyection_stop_warehouse_e').removeClass('is-valid');
    $('#number_samples_include_suitcase_e').removeClass('is-valid');
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
    $('#date_definition_start_pilots_e').removeClass('is-invalid');
    $('#date_definition_start_samples_e').removeClass('is-invalid');
    $('#proyection_stop_warehouse_e').removeClass('is-invalid');
    $('#number_samples_include_suitcase_e').removeClass('is-invalid');
}

$('#start_date_edit').datetimepicker({
    format: 'L'
});

$('#end_date_edit').datetimepicker({
    format: 'L'
});

$('#date_definition_start_pilots_edit').datetimepicker({
    format: 'L'
});

$('#date_definition_start_samples_edit').datetimepicker({
    format: 'L'
});
