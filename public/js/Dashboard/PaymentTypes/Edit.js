function EditPaymentTypeModal(id) {
    $.ajax({
        url: `/Dashboard/PaymentTypes/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            tablePaymentTypes.ajax.reload();
            EditPaymentTypeModalCleaned(response.data);
            EditPaymentTypeAjaxSuccess(response);
            $('#EditPaymentTypeModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            tablePaymentTypes.ajax.reload();
            EditPaymentTypeAjaxError(xhr);
        }
    });
}

function EditPaymentTypeModalCleaned(paymentType) {
    RemoveIsValidClassEditPaymentType();
    RemoveIsInvalidClassEditPaymentType();

    $('#EditPaymentTypeButton').attr('onclick', `EditPaymentType(${paymentType.id}, ${paymentType.require_banks})`);

    $("#name_e").val(paymentType.name);
    $("#code_e").val(paymentType.code);
}

function EditPaymentType(id, require_banks) {
    Swal.fire({
        title: '¿Desea actualizar el metodo de pago?',
        text: 'El metodo de pago se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
        html: `<div class="icheck-primary"><input type="checkbox" id="require_banks_e" name="require_banks_e" ${require_banks ? 'checked' : ''}><label for="require_banks_e">¿Requiere indicar el banco?</label></div>`,
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/PaymentTypes/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id,
                    'name': $("#name_e").val(),
                    'code': $("#code_e").val(),
                    'require_banks': $('#require_banks_e').is(':checked')
                },
                success: function(response) {
                    tablePaymentTypes.ajax.reload();
                    EditPaymentTypeAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tablePaymentTypes.ajax.reload();
                    EditPaymentTypeAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El metodo de pago no fue actualizado.')
        }
    });
}

function EditPaymentTypeAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.info(response.message);
        $('#EditPaymentTypeModal').modal('hide');
    }

    if(response.status === 200) {
        toastr.success(response.message);
        $('#EditPaymentTypeModal').modal('hide');
    }
}

function EditPaymentTypeAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditPaymentTypeModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditPaymentTypeModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditPaymentTypeModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassEditPaymentType();
        RemoveIsInvalidClassEditPaymentType();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassEditPaymentType(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditPaymentType();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditPaymentTypeModal').modal('hide');
    }
}

function AddIsValidClassEditPaymentType() {
    if (!$('#name_e').hasClass('is-invalid')) {
      $('#name_e').addClass('is-valid');
    }
    if (!$('#code_e').hasClass('is-invalid')) {
      $('#code_e').addClass('is-valid');
    }
}

function RemoveIsValidClassEditPaymentType() {
    $('#name_e').removeClass('is-valid');
    $('#code_e').removeClass('is-valid');
}

function AddIsInvalidClassEditPaymentType(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditPaymentType() {
    $('#name_e').removeClass('is-invalid');
    $('#code_e').removeClass('is-invalid');
}
