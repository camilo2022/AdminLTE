function CreateCorreriaAndCollectionModal() {
    $.ajax({
        url: `/Dashboard/CorreriasAndCollections/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            CreateCorreriaAndCollectionModalCleaned();
            CreateCorreriaAndCollectionAjaxSuccess(response);
            $('#CreateCorreriaAndCollectionModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            CreateCorreriaAndCollectionAjaxError(xhr);
        }
    });
}

function CreateCorreriaAndCollectionModalCleaned() {
    RemoveIsValidClassCreateCorreriaAndCollection();
    RemoveIsInvalidClassCreateCorreriaAndCollection();

    $('#name_c').val('');
    $('#code_c').val('');
    $('#start_date_c').val('');
    $('#end_date_c').val('');
}

function CreateCorreriaAndCollection() {
    Swal.fire({
        title: '¿Desea guardar la correria?',
        text: 'La correria será creada.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/CorreriasAndCollections/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                    'code': $('#code_c').val(),
                    'start_date': $('#start_date_c').val(),
                    'end_date': $('#end_date_c').val(),
                    'date_definition_start_pilots': $('#date_definition_start_pilots_c').val(),
                    'date_definition_start_samples': $('#date_definition_start_samples_c').val(),
                    'proyection_stop_warehouse': $('#proyection_stop_warehouse_c').val(),
                    'number_samples_include_suitcase': $('#number_samples_include_suitcase_c').val()
                },
                success: function(response) {
                    tableCorreriasAndCollections.ajax.reload();
                    CreateCorreriaAndCollectionAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    CreateCorreriaAndCollectionAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La correria no fue creada.')
        }
    });
}

function CreateCorreriaAndCollectionAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.info(response.message);
        $('#CreateCorreriaAndCollectionModal').modal('hide');
    }

    if(response.status === 201) {
        toastr.success(response.message);
        $('#CreateCorreriaAndCollectionModal').modal('hide');
    }
}

function CreateCorreriaAndCollectionAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateCorreriaAndCollectionModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateCorreriaAndCollectionModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateCorreriaAndCollectionModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassCreateCorreriaAndCollection();
        RemoveIsInvalidClassCreateCorreriaAndCollection();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassCreateCorreriaAndCollection(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateCorreriaAndCollection();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateCorreriaAndCollectionModal').modal('hide');
    }
}

function AddIsValidClassCreateCorreriaAndCollection() {
    if (!$('#name_c').hasClass('is-invalid')) {
      $('#name_c').addClass('is-valid');
    }
    if (!$('#code_c').hasClass('is-invalid')) {
      $('#code_c').addClass('is-valid');
    }
    if (!$('#start_date_c').hasClass('is-invalid')) {
      $('#start_date_c').addClass('is-valid');
    }
    if (!$('#end_date_c').hasClass('is-invalid')) {
      $('#end_date_c').addClass('is-valid');
    }
    if (!$('#date_definition_start_pilots_c').hasClass('is-invalid')) {
      $('#date_definition_start_pilots_c').addClass('is-valid');
    }
    if (!$('#date_definition_start_samples_c').hasClass('is-invalid')) {
      $('#date_definition_start_samples_c').addClass('is-valid');
    }
    if (!$('#proyection_stop_warehouse_c').hasClass('is-invalid')) {
      $('#proyection_stop_warehouse_c').addClass('is-valid');
    }
    if (!$('#number_samples_include_suitcase_c').hasClass('is-invalid')) {
      $('#number_samples_include_suitcase_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateCorreriaAndCollection() {
    $('#name_c').removeClass('is-valid');
    $('#code_c').removeClass('is-valid');
    $('#start_date_c').removeClass('is-valid');
    $('#end_date_c').removeClass('is-valid');
    $('#date_definition_start_pilots_c').removeClass('is-valid');
    $('#date_definition_start_samples_c').removeClass('is-valid');
    $('#proyection_stop_warehouse_c').removeClass('is-valid');
    $('#number_samples_include_suitcase_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateCorreriaAndCollection(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateCorreriaAndCollection() {
    $('#name_c').removeClass('is-invalid');
    $('#code_c').removeClass('is-invalid');
    $('#start_date_c').removeClass('is-invalid');
    $('#end_date_c').removeClass('is-invalid');
    $('#date_definition_start_pilots_c').removeClass('is-invalid');
    $('#date_definition_start_samples_c').removeClass('is-invalid');
    $('#proyection_stop_warehouse_c').removeClass('is-invalid');
    $('#number_samples_include_suitcase_c').removeClass('is-invalid');
}

$('#start_date_create').datetimepicker({
    format: 'L'
});

$('#end_date_create').datetimepicker({
    format: 'L'
});

$('#date_definition_start_pilots_create').datetimepicker({
    format: 'L'
});

$('#date_definition_start_samples_create').datetimepicker({
    format: 'L'
});
