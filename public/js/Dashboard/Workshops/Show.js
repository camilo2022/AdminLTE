function ShowWorkshopModal(id) {
    $.ajax({
        url: `/Dashboard/Workshops/Show/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            ShowWorkshopModalCleaned(response.data);
            ShowWorkshopAjaxSuccess(response);
            $('#ShowWorkshopModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            ShowWorkshopAjaxError(xhr);
        }
    });
}

function ShowWorkshopModalCleaned(data) {
    $("#name_s").val(data.workshop.name);
    $('#process_s').empty();
    $.each(data.processes, function (index, process) {
        let processDiv = $('<div>').addClass('row pl-2 icheck-primary');
        let processCheckbox = $(`<input>`).attr({
            'type': 'checkbox',
            'id': process.id,
            'checked': process.admin,
            'onchange': `ShowWorkshop(${process.id}, ${data.workshop.id}, this)`
        });
        let processLabel = $('<label>').text(process.name).attr({
            'for': process.id,
            'class': 'mt-3 ml-3'
        });
        // Agregar elementos al cardBody
        processDiv.append(processCheckbox);
        processDiv.append(processLabel);
        $('#process_s').append(processDiv);
    });
}

function ShowWorkshop(process, workshop, checkbox) {
    if ($(checkbox).prop('checked')) {
        ShowWorkshopAssignProcess(process, workshop);
    } else {
        ShowWorkshopRemoveProcess(process, workshop);
    }
}

function ShowWorkshopAssignProcess(process, workshop) {
    $.ajax({
        url: `/Dashboard/Workshops/AssignProcess`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'process_id': process,
            'workshop_id': workshop,
        },
        success: function (response) {
            tableWorkshops.ajax.reload();
            ShowWorkshopAjaxSuccess(response);
        },
        error: function (xhr, textStatus, errorThrown) {
            ShowWorkshopAjaxError(xhr);
        }
    });
}

function ShowWorkshopRemoveProcess(process, workshop) {
    $.ajax({
        url: `/Dashboard/Workshops/RemoveProcess`,
        type: 'DELETE',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'process_id': process,
            'workshop_id': workshop,
        },
        success: function (response) {
            tableWorkshops.ajax.reload();
            ShowWorkshopAjaxSuccess(response);
        },
        error: function (xhr, textStatus, errorThrown) {
            ShowWorkshopAjaxError(xhr);
        }
    });
}

function ShowWorkshopAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
    }

    if(response.status === 204) {
        toastr.info(response.message);
    }
}

function ShowWorkshopAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
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
    }
}
