function ShowPersonTypeModal(id) {
    $.ajax({
        url: `/Dashboard/PersonTypes/Show/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            tablePersonTypes.ajax.reload();
            ShowPersonTypeModalCleaned(response.data);
            ShowPersonTypeAjaxSuccess(response);
            $('#ShowPersonTypeModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tablePersonTypes.ajax.reload();
            ShowPersonTypeAjaxError(xhr);
        }
    });
}

function ShowPersonTypeModalCleaned(data) {
    $("#name_s").val(data.personType.name);
    $("#code_s").val(data.personType.code);
    $('#document_types_s').empty();
    $.each(data.documentTypes, function (index, documentType) {
        let personTypeDiv = $('<div>').addClass('row pl-2 icheck-primary');
        let documentTypeCheckbox = $(`<input>`).attr({
            'type': 'checkbox',
            'id': documentType.id,
            'checked': documentType.admin,
            'onchange': `ShowPersonType(${documentType.id}, ${data.personType.id}, this)`
        });
        let documentTypeLabel = $('<label>').text(documentType.name).attr({
            'for': documentType.id,
            'class': 'mt-3 ml-3'
        });
        // Agregar elementos al cardBody
        personTypeDiv.append(documentTypeCheckbox);
        personTypeDiv.append(documentTypeLabel);
        $('#document_types_s').append(personTypeDiv);
    });
}

function ShowPersonType(document_type_id, person_type_id, checkbox) {
    if ($(checkbox).prop('checked')) {
        ShowPersonTypeAssignDocumentType(document_type_id, person_type_id);
    } else {
        ShowPersonTypeRemoveDocumentType(document_type_id, person_type_id);
    }
}

function ShowPersonTypeAssignDocumentType(document_type_id, person_type_id) {
    $.ajax({
        url: `/Dashboard/PersonTypes/AssignDocumentType`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'person_type_id': person_type_id,
            'document_type_id': document_type_id,
        },
        success: function (response) {
            tablePersonTypes.ajax.reload();
            ShowPersonTypeAjaxSuccess(response);
        },
        error: function (xhr, textStatus, errorThrown) {
            tablePersonTypes.ajax.reload();
            ShowPersonTypeAjaxError(xhr);
        }
    });
}

function ShowPersonTypeRemoveDocumentType(document_type_id, person_type_id) {
    $.ajax({
        url: `/Dashboard/PersonTypes/RemoveDocumentType`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'person_type_id': person_type_id,
            'document_type_id': document_type_id,
        },
        success: function (response) {
            tablePersonTypes.ajax.reload();
            ShowPersonTypeAjaxSuccess(response);
        },
        error: function (xhr, textStatus, errorThrown) {
            tablePersonTypes.ajax.reload();
            ShowPersonTypeAjaxError(xhr);
        }
    });
}

function ShowPersonTypeAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
        $('#PasswordUserModal').modal('hide');
    }

    if(response.status === 204) {
        toastr.info(response.message);
        $('#PasswordUserModal').modal('hide');
    }
}

function ShowPersonTypeAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowPersonTypeModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowPersonTypeModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowPersonTypeModal').modal('hide');
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
        $('#ShowPersonTypeModal').modal('hide');
    }
}
