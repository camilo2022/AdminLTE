function ShowSaleChannelModal(id) {
    $.ajax({
        url: `/Dashboard/SaleChannels/Show/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            ShowSaleChannelModalCleaned(response.data);
            ShowSaleChannelAjaxSuccess(response);
            $('#ShowSaleChannelModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            ShowSaleChannelAjaxError(xhr);
        }
    });
}

function ShowSaleChannelModalCleaned(data) {
    console.log(data);
    $("#name_s").val(data.saleChannel.name);
    $('#return_types_s').empty();
    $.each(data.returnTypes, function (index, returnType) {
        let returnTypeDiv = $('<div>').addClass('row pl-2 icheck-primary');
        let returnTypeCheckbox = $(`<input>`).attr({
            'type': 'checkbox',
            'id': returnType.id,
            'checked': returnType.exists,
            'onchange': `ShowSaleChannel(${returnType.id}, ${data.saleChannel.id}, this)`
        });
        let returnTypeLabel = $('<label>').text(returnType.name).attr({
            'for': returnType.id,
            'class': 'mt-3 ml-3'
        });
        // Agregar elementos al cardBody
        returnTypeDiv.append(returnTypeCheckbox);
        returnTypeDiv.append(returnTypeLabel);
        $('#return_types_s').append(returnTypeDiv);
    });
}

function ShowSaleChannel(returnType, saleChannel, checkbox) {
    if ($(checkbox).prop('checked')) {
        ShowSaleChannelAssignReturnType(returnType, saleChannel);
    } else {
        ShowSaleChannelRemoveReturnType(returnType, saleChannel);
    }
}

function ShowSaleChannelAssignReturnType(returnType, saleChannel) {
    $.ajax({
        url: `/Dashboard/SaleChannels/AssignReturnType`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'return_type_id': returnType,
            'sale_channel_id': saleChannel,
        },
        success: function (response) {
            tableSaleChannels.ajax.reload();
            ShowSaleChannelAjaxSuccess(response);
        },
        error: function (xhr, textStatus, errorThrown) {
            ShowSaleChannelAjaxError(xhr);
        }
    });
}

function ShowSaleChannelRemoveReturnType(returnType, saleChannel) {
    $.ajax({
        url: `/Dashboard/SaleChannels/RemoveReturnType`,
        type: 'DELETE',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'return_type_id': returnType,
            'sale_channel_id': saleChannel,
        },
        success: function (response) {
            tableSaleChannels.ajax.reload();
            ShowSaleChannelAjaxSuccess(response);
        },
        error: function (xhr, textStatus, errorThrown) {
            ShowSaleChannelAjaxError(xhr);
        }
    });
}

function ShowSaleChannelAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
        $('#PasswordUserModal').modal('hide');
    }

    if(response.status === 204) {
        toastr.info(response.message);
        $('#PasswordUserModal').modal('hide');
    }
}

function ShowSaleChannelAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowSaleChannelModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowSaleChannelModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowSaleChannelModal').modal('hide');
    }

    if (xhr.status === 422) {
        $.each(xhr.responseJSON.errors, function (field, messages) {
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowSaleChannelModal').modal('hide');
    }
}
