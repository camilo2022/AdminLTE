function CreateCollectionModal() {
    $.ajax({
        url: `/Dashboard/Collections/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            CreateCollectionModalCleaned();
            CreateCollectionAjaxSuccess(response);
            $('#CreateCollectionModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            CreateCollectionAjaxError(xhr);
        }
    });
}

function CreateCollectionModalCleaned() {
    RemoveIsValidClassCreateCollection();
    RemoveIsInvalidClassCreateCollection();

    $('#name_c').val('');
    $('#code_c').val('');
    $('#start_date_c').val('');
    $('#end_date_c').val('');
}

function CreateCollection() {
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
                url: `/Dashboard/Collections/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                    'code': $('#code_c').val(),
                    'start_date': $('#start_date_c').val(),
                    'end_date': $('#end_date_c').val()
                },
                success: function(response) {
                    tableCollections.ajax.reload();
                    CreateCollectionAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableCollections.ajax.reload();
                    CreateCollectionAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La correria no fue creada.')
        }
    });
}

function CreateCollectionAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.info(response.message);
        $('#CreateCollectionModal').modal('hide');
    }

    if(response.status === 201) {
        toastr.success(response.message);
        $('#CreateCollectionModal').modal('hide');
    }
}

function CreateCollectionAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateCollectionModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateCollectionModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateCollectionModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassCreateCollection();
        RemoveIsInvalidClassCreateCollection();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassCreateCollection(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateCollection();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateCollectionModal').modal('hide');
    }
}

function AddIsValidClassCreateCollection() {
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
}

function RemoveIsValidClassCreateCollection() {
    $('#name_c').removeClass('is-valid');
    $('#code_c').removeClass('is-valid');
    $('#start_date_c').removeClass('is-valid');
    $('#end_date_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateCollection(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).removeClass('is-valid');
    }
    $(`#${input}_c`).addClass('is-invalid');
}

function RemoveIsInvalidClassCreateCollection() {
    $('#name_c').removeClass('is-invalid');
    $('#code_c').removeClass('is-invalid');
    $('#start_date_c').removeClass('is-invalid');
    $('#end_date_c').removeClass('is-invalid');
}

$('#start_date_create').datetimepicker({
    format: 'L'
});

$('#end_date_create').datetimepicker({
    format: 'L'
});
