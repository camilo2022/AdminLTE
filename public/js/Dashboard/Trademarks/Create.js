function CreateTrademarkModal() {
    $.ajax({
        url: `/Dashboard/Trademarks/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            CreateTrademarkModalCleaned();
            CreateTrademarkAjaxSuccess(response);
            $('#CreateTrademarkModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            CreateTrademarkAjaxError(xhr);
        }
    });
}

function CreateTrademarkModalCleaned() {
    RemoveIsValidClassCreateTrademark();
    RemoveIsInvalidClassCreateTrademark();

    $('#name_c').val('');
    $('#code_c').val('');
}

function CreateTrademark() {
    Swal.fire({
        title: '¿Desea guardar la marca de producto?',
        text: 'La marca de producto será creada.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Trademarks/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                    'code': $('#code_c').val(),
                    'start_date': $('#start_date_c').val(),
                    'end_date': $('#end_date_c').val()
                },
                success: function(response) {
                    tableTrademarks.ajax.reload();
                    CreateTrademarkAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableTrademarks.ajax.reload();
                    CreateTrademarkAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La marca de producto no fue creada.')
        }
    });
}

function CreateTrademarkAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.info(response.message);
        $('#CreateTrademarkModal').modal('hide');
    }

    if(response.status === 201) {
        toastr.success(response.message);
        $('#CreateTrademarkModal').modal('hide');
    }
}

function CreateTrademarkAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error.message);
        $('#CreateTrademarkModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateTrademarkModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error.message);
        $('#CreateTrademarkModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassCreateTrademark();
        RemoveIsInvalidClassCreateTrademark();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassCreateTrademark(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateTrademark();
    }

    if(xhr.status === 500){
        if(xhr.responseJSON.error) {
            toastr.error(xhr.responseJSON.error.message);
        }

        if(xhr.responseJSON.message) {
            toastr.error(xhr.responseJSON.message);
        }
        $('#CreateTrademarkModal').modal('hide');
    }
}

function AddIsValidClassCreateTrademark() {
    if (!$('#name_c').hasClass('is-invalid')) {
      $('#name_c').addClass('is-valid');
    }
    if (!$('#code_c').hasClass('is-invalid')) {
      $('#code_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateTrademark() {
    $('#name_c').removeClass('is-valid');
    $('#code_c').removeClass('is-valid');
}

function AddIsInvalidClassCreateTrademark(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).removeClass('is-valid');
    }
    $(`#${input}_c`).addClass('is-invalid');
}

function RemoveIsInvalidClassCreateTrademark() {
    $('#name_c').removeClass('is-invalid');
    $('#code_c').removeClass('is-invalid');
}
