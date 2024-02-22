function CreatePaymentTypeModal() {
    $.ajax({
        url: `/Dashboard/PaymentTypes/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            CreatePaymentTypeModalCleaned();
            CreatePaymentTypeAjaxSuccess(response);
            $('#CreatePaymentTypeModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            CreatePaymentTypeAjaxError(xhr);
        }
    });
}

function CreatePaymentTypeModalCleaned() {
    RemoveIsValidClassCreatePaymentType();
    RemoveIsInvalidClassCreatePaymentType();

    $('#name_c').val('');
    $('#code_c').val('');
}

function CreatePaymentType() {
    Swal.fire({
        title: '¿Desea guardar el metodo de pago?',
        text: 'El metodo de pago será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
        html: '<div class="icheck-primary"><input type="checkbox" id="require_banks_c" name="require_banks_c"><label for="require_banks_c">¿Requiere indicar el banco?</label></div>',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/PaymentTypes/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                    'code': $('#code_c').val(),
                    'require_banks': $('#require_banks_c').is(':checked')
                },
                success: function(response) {
                    tablePaymentTypes.ajax.reload();
                    CreatePaymentTypeAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tablePaymentTypes.ajax.reload();
                    CreatePaymentTypeAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El metodo de pago no fue creado.')
        }
    });
}

function CreatePaymentTypeAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.info(response.message);
        $('#CreatePaymentTypeModal').modal('hide');
    }

    if(response.status === 201) {
        toastr.success(response.message);
        $('#CreatePaymentTypeModal').modal('hide');
    }
}

function CreatePaymentTypeAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreatePaymentTypeModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreatePaymentTypeModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreatePaymentTypeModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassCreatePaymentType();
        RemoveIsInvalidClassCreatePaymentType();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassCreatePaymentType(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreatePaymentType();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreatePaymentTypeModal').modal('hide');
    }
}

function AddIsValidClassCreatePaymentType() {
    if (!$('#name_c').hasClass('is-invalid')) {
      $('#name_c').addClass('is-valid');
    }
    if (!$('#code_c').hasClass('is-invalid')) {
      $('#code_c').addClass('is-valid');
    }
}

function RemoveIsValidClassCreatePaymentType() {
    $('#name_c').removeClass('is-valid');
    $('#code_c').removeClass('is-valid');
}

function AddIsInvalidClassCreatePaymentType(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreatePaymentType() {
    $('#name_c').removeClass('is-invalid');
    $('#code_c').removeClass('is-invalid');
}
