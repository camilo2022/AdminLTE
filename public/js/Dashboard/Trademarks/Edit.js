function EditTrademarkModal(id) {
    $.ajax({
        url: `/Dashboard/Trademarks/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            EditTrademarkModalCleaned(response.data);
            EditTrademarkAjaxSuccess(response);
            $('#EditTrademarkModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            EditTrademarkAjaxError(xhr);
        }
    });
}

function EditTrademarkModalCleaned(trademark) {
    RemoveIsValidClassEditTrademark();
    RemoveIsInvalidClassEditTrademark();

    $('#EditTrademarkButton').attr('onclick', `EditTrademark(${trademark.id})`);

    $("#name_e").val(trademark.name);
    $("#code_e").val(trademark.code);
    $('#description_e').val(trademark.description);
    var drEvent = $('#logo_e').dropify({
        defaultFile: trademark.path
    });
    drEvent = drEvent.data('dropify');
    drEvent.resetPreview();
    drEvent.clearElement();
    drEvent.settings.defaultFile = trademark.path;
    drEvent.destroy();
    drEvent.init();
    $('#logo_e').val('');
}

function EditTrademark(id) {
    let formData = new FormData();
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('name', $('#name_e').val());
    formData.append('code', $('#code_e').val());
    formData.append('description', $('#description_e').val());
    formData.append('logo', $('#logo_e')[0].files[0] != undefined ? $('#logo_e')[0].files[0] : null );
    
    Swal.fire({
        title: '¿Desea actualizar la marca de producto?',
        text: 'La marca de producto se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Trademarks/Update/${id}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    tableTrademarks.ajax.reload();
                    EditTrademarkAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    EditTrademarkAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La marca de producto no fue actualizado.')
        }
    });
}

function EditTrademarkAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#EditTrademarkModal').modal('hide');
    }
    
    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditTrademarkModal').modal('hide');
    }
}

function EditTrademarkAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditTrademarkModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditTrademarkModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditTrademarkModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditTrademark();
        RemoveIsInvalidClassEditTrademark();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditTrademark(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditTrademark();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditTrademarkModal').modal('hide');
    }
}

function AddIsValidClassEditTrademark() {
    if (!$('#name_e').hasClass('is-invalid')) {
        $('#name_e').addClass('is-valid');
    }
    if (!$('#code_e').hasClass('is-invalid')) {
        $('#code_e').addClass('is-valid');
    }
    if (!$('#description_e').hasClass('is-invalid')) {
        $('#description_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditTrademark() {
    $('#name_e').removeClass('is-valid');
    $('#code_e').removeClass('is-valid');
    $('#description_e').removeClass('is-valid');
}

function AddIsInvalidClassEditTrademark(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).removeClass('is-valid');
    }
    $(`#${input}_e`).addClass('is-invalid');
}

function RemoveIsInvalidClassEditTrademark() {
    $('#name_e').removeClass('is-invalid');
    $('#code_e').removeClass('is-invalid');
    $('#description_e').removeClass('is-invalid');
}
