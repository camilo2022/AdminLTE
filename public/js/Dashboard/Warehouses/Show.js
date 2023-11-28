function ShowWarehouseModal(id) {
    $.ajax({
        url: `/Dashboard/Warehouses/Show/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            tableWarehouses.ajax.reload();
            ShowWarehouseModalCleaned(response.data);
            ShowWarehouseAjaxSuccess(response);
            $('#ShowWarehouseModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tableWarehouses.ajax.reload();
            ShowWarehouseAjaxError(xhr);
        }
    });
}

function ShowWarehouseModalCleaned(data) {
    $("#name_s").val(data.warehouse.name);
    $("#code_s").val(data.warehouse.code);
    $('#users_s').empty();
    $.each(data.admins, function (index, user) {
        let userDiv = $('<div>').addClass('row pl-2 icheck-primary');
        let userCheckbox = $(`<input>`).attr({
            'type': 'checkbox',
            'id': user.id,
            'checked': user.admin,
            'onchange': `ShowWarehouse(${user.id}, ${data.warehouse.id}, this)`
        });
        let userLabel = $('<label>').text(`${user.name} ${user.last_name}`).attr({
            'for': user.id,
            'class': 'mt-3 ml-3'
        });
        // Agregar elementos al cardBody
        userDiv.append(userCheckbox);
        userDiv.append(userLabel);
        $('#users_s').append(userDiv);
    });
}

function ShowWarehouse(user, warehouse, checkbox) {
    if ($(checkbox).prop('checked')) {
        ShowWarehouseAssignGestor(user, warehouse);
    } else {
        ShowWarehouseRemoveGestor(user, warehouse);
    }
}

function ShowWarehouseAssignGestor(user, warehouse) {
    $.ajax({
        url: `/Dashboard/Warehouses/AssignGestor`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'user_id': user,
            'warehouse_id': warehouse,
        },
        success: function (response) {
            tableWarehouses.ajax.reload();
            ShowWarehouseAjaxSuccess(response);
        },
        error: function (xhr, textStatus, errorThrown) {
            tableWarehouses.ajax.reload();
            ShowWarehouseAjaxError(xhr);
        }
    });
}

function ShowWarehouseRemoveGestor(user, warehouse) {
    $.ajax({
        url: `/Dashboard/Warehouses/RemoveGestor`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'user_id': user,
            'warehouse_id': warehouse,
        },
        success: function (response) {
            tableWarehouses.ajax.reload();
            ShowWarehouseAjaxSuccess(response);
        },
        error: function (xhr, textStatus, errorThrown) {
            tableWarehouses.ajax.reload();
            ShowWarehouseAjaxError(xhr);
        }
    });
}

function ShowWarehouseAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
        $('#PasswordUserModal').modal('hide');
    }

    if(response.status === 204) {
        toastr.info(response.message);
        $('#PasswordUserModal').modal('hide');
    }
}

function ShowWarehouseAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowWarehouseModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowWarehouseModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowWarehouseModal').modal('hide');
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
        $('#ShowWarehouseModal').modal('hide');
    }
}
