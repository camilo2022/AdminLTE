function EditOrderSellerModal(id) {
    $.ajax({
        url: `/Dashboard/Orders/Seller/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            EditOrderSellerModalCleaned(response.data.order);
            EditOrderSellerModalClient(response.data.clients);
            EditOrderSellerAjaxSuccess(response);
            $('#EditOrderSellerModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            EditOrderSellerAjaxError(xhr);
        }
    });
}

function EditOrderSellerModalCleaned(order) {
    EditOrderSellerModalResetSelect('client_id_e');
    RemoveIsValidClassEditOrderSeller();
    RemoveIsInvalidClassEditOrderSeller();

    $('#EditOrderSellerButton').attr('onclick', `EditOrderSeller(${order.id})`);
    $('#EditOrderSellerButton').attr('data-id', order.id);
    $('#EditOrderSellerButton').attr('data-client_id', order.client_id);
    $('#EditOrderSellerButton').attr('data-client_branch_id', order.client_branch_id);

    $('#dispatch_e').val(order.dispatch).trigger('change');
    $('#dispatch_date_e').val(order.dispatch_date);
    $('#seller_observation_e').val(order.seller_observation);
}

function EditOrderSellerModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function EditOrderSellerModalClient(clients) {
    clients.forEach(client => {
        $('#client_id_e').append(new Option(`${client.name} - ${client.document_number}`, client.id, false, false));
    });

    let client_id = $('#EditOrderSellerButton').attr('data-client_id');
    if(client_id != '') {
        $("#client_id_e").val(client_id).trigger('change');
        $('#EditOrderSellerButton').attr('data-client_id', '');
    }
}

function EditOrderSellerModalClientGetClientBranch(select) {
    if($(select).val() == '') {
        EditOrderSellerModalResetSelect('client_branch_id_e');
    } else {
        let id = $('#EditOrderSellerButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Orders/Seller/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'client_id':  $(select).val()
            },
            success: function(response) {
                EditOrderSellerModalResetSelect('client_branch_id_e');
                EditOrderSellerModalClienteBranch(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditOrderSellerAjaxError(xhr);
            }
        });
    }
}

function EditOrderSellerModalClienteBranch(clientBranches) {
    clientBranches.forEach(clientBranch => {
        $('#client_branch_id_e').append(new Option(`${clientBranch.name} - ${clientBranch.code}`, clientBranch.id, false, false));
    });

    let client_branch_id = $('#EditOrderSellerButton').attr('data-client_branch_id');
    if(client_branch_id != '') {
        $("#client_branch_id_e").val(client_branch_id).trigger('change');
        $('#EditOrderSellerButton').attr('data-client_branch_id', '');
    }
}

function EditOrderSellerModalDispatchGetDispatchDate(select) {
    if($(select).val() == '' || $(select).val() == 'De inmediato') {
        $('#div_dispatch_date_e').hide();
        $('#dispatch_date_e').val('')
    } else {
        $('#div_dispatch_date_e').show();
    }
}

function EditOrderSeller(id) {
    Swal.fire({
        title: '¿Desea actualizar el pedido?',
        text: 'El pedido se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Orders/Seller/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'client_id': $('#client_id_e').val(),
                    'client_branch_id': $('#client_branch_id_e').val(),
                    'seller_observation': $('#seller_observation_e').val(),
                    'dispatch': $('#dispatch_e').val(),
                    'dispatch_date': $('#dispatch_e').val() == 'De inmediato' ? new Date().toISOString().split('T')[0] : $('#dispatch_date_e').val()
                },
                success: function (response) {
                    tableOrderSellers.ajax.reload();
                    EditOrderSellerAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    EditOrderSellerAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El pedido no fue actualizada.')
        }
    });
}

function EditOrderSellerAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#EditOrderSellerModal').modal('hide');
    }

    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditOrderSellerModal').modal('hide');
    }
}

function EditOrderSellerAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderSellerModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderSellerModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderSellerModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditOrderSeller();
        RemoveIsInvalidClassEditOrderSeller();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditOrderSeller(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditOrderSeller();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditOrderSellerModal').modal('hide');
    }
}

function AddIsValidClassEditOrderSeller() {
    if (!$('#seller_observation_e').hasClass('is-invalid')) {
        $('#seller_observation_e').addClass('is-valid');
    }
    if (!$('#dispatch_e').hasClass('is-invalid')) {
        $('#dispatch_e').addClass('is-valid');
    }
    if (!$('#dispatch_date_e').hasClass('is-invalid')) {
        $('#dispatch_date_e').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-client_id_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-client_id_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-client_branch_id_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-client_branch_id_e-container"]').addClass('is-valid');
    }
}

function RemoveIsValidClassEditOrderSeller() {
    $('#seller_observation_e').removeClass('is-valid');
    $('#dispatch_e').removeClass('is-valid');
    $('#dispatch_date_e').removeClass('is-valid');
    $('span[aria-labelledby="select2-client_id_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-client_branch_id_e-container"]').removeClass('is-valid');
}

function AddIsInvalidClassEditOrderSeller(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_e-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_e-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditOrderSeller() {
    $('#seller_observation_e').removeClass('is-invalid');
    $('#dispatch_e').removeClass('is-invalid');
    $('#dispatch_date_e').removeClass('is-invalid');
    $('span[aria-labelledby="select2-client_id_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-client_branch_id_e-container"]').removeClass('is-invalid');
}

$('#dispatch_date_e').datetimepicker({
    format: 'L'
});
