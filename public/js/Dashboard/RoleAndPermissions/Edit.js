function EditRoleAndPermissionsModal(id, role, permissions) {
    RemoveIsValidClassEditRoleAndPermissions();
    RemoveIsInvalidClassEditRoleAndPermissions();

    $('#role_e').val(role);
    $('.permissions_e').empty();
    $('#EditRoleAndPermissionsButton').attr({
        'onclick': `EditRoleAndPermissions(${id})`
    });

    $.each(permissions, function (i, permission) {
        let permissionGroup = $('<div>').attr({
            'class': 'form-group permission-group'
        });

        let inputGroup = $('<div>').attr({
            'class': 'input-group'
        });

        let input = $('<input>').attr({
            'type': 'text',
            'class': 'form-control',
            'id': `permission_e${i}`,
            'name': 'permissions_e[]',
            'value': permission
        });

        let inputGroupAppend = $('<div>');
        inputGroupAppend.attr('class', 'input-group-append');

        let span = $('<span>').attr({
            'class': 'input-group-text bg-red permission-toggle',
            'data-id': i,
            'onclick': 'EditRoleAndPermissionsRemovePermission(this)'
        });

        let icon = $('<i>').attr({
            'class': 'fas fa-minus'
        });

        // Construir la estructura de elementos
        span.append(icon);
        inputGroupAppend.append(span);
        inputGroup.append(input, inputGroupAppend);
        permissionGroup.append(inputGroup);

        // Agregar al contenedor
        $('.permissions_e').append(permissionGroup);
        $('#EditRoleAndPermissionsAddPermissionButton').attr({
            'data-count': i++
        });
    })
    $('#EditRoleAndPermissionsModal').modal('show');
}

function EditRoleAndPermissionsAddPermission(permission) {
    let permissionCount = $(permission).data('count');
    let newPermissionGroup = $('<div>');
    newPermissionGroup.attr({
        'class': 'form-group permission-group'
    });

    let inputGroup = $('<div>').attr({
        'class': 'input-group'
    });

    let input = $('<input>').attr({
        'type': 'text',
        'class': 'form-control',
        'id': `permission_e${permissionCount}`,
        'name': 'permissions_e[]'
    });

    let inputGroupAppend = $('<div>').attr({
        'class': 'input-group-append'
    });

    let span = $('<span>').attr({
        'class': 'input-group-text bg-red permission-toggle',
        'data-id': permissionCount,
        'onclick': 'EditRoleAndPermissionsRemovePermission(this)'
    });

    let icon = $('<i>').attr({
        'class': 'fas fa-minus'
    });

    // Construir la estructura de elementos
    span.append(icon);
    inputGroupAppend.append(span);
    inputGroup.append(input, inputGroupAppend);
    newPermissionGroup.append(inputGroup);

    // Agregar al contenedor
    $('.permissions_e').append(newPermissionGroup);
    permissionCount++;
    $(permission).data('count', permissionCount);
}

function EditRoleAndPermissionsRemovePermission(permission) {
    let permissionId = $(permission).data('id');
    let permissionGroup = $(`#permission_e${permissionId}`).closest('.permission-group');
    permissionGroup.remove();
}

function EditRoleAndPermissions(id) {
    Swal.fire({
        title: '¿Desea actualizar el rol y los permisos?',
        text: 'El rol y los permisos serán actualizados.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/RolesAndPermissions/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'role': $('#role_e').val(),
                    'permissions': $('input[name="permissions_e[]"]').map(function() {
                        return $(this).val();
                    }).get()
                },
                success: function(response) {
                    tableRolesAndPermissions.ajax.reload();
                    toastr.success(response.message);
                    $('#EditRoleAndPermissionsModal').modal('hide');
                },
                error: function(xhr, textStatus, errorThrown) {
                    RemoveIsInvalidClassEditRoleAndPermissions();
                    tableRolesAndPermissions.ajax.reload();
                    EditRoleAndPermissionsAjaxError(xhr);
                    AddIsValidClassEditRoleAndPermissions();
                }
            });
        } else {
            toastr.info('El rol y los permisos no fueron actualizados.')
        }
    });
}

function EditRoleAndPermissionsAjaxError(xhr) {
    if(xhr.responseJSON.error){
        toastr.error(xhr.responseJSON.error.message);
        toastr.error(xhr.responseJSON.error.error);
    } else if(xhr.responseJSON.errors){
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassEditRoleAndPermissions(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
    } else {
        toastr.error(xhr.responseJSON.message);
    }
}

function AddIsValidClassEditRoleAndPermissions() {
    if (!$('#role_e').hasClass('is-invalid')) {
      $('#role_e').addClass('is-valid');
    }

    // Itera sobre los inputs dentro del div
    $('.permissions_e').find('input').each(function() {

        // Verifica si el input no tiene la clase 'is-invalid'
        if (!$(this).hasClass('is-invalid')) {
            // Agrega la clase 'is-valid'
            $(this).addClass('is-valid');
        }
    });
}

function RemoveIsValidClassEditRoleAndPermissions() {
    $('#role_e').removeClass('is-valid');

    // Itera sobre los inputs dentro del div
    $('.permissions_e').find('input').each(function() {
        // Agrega la clase 'is-valid'
        $(this).removeClass('is-valid');
    });
}

function AddIsInvalidClassEditRoleAndPermissions(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
    $('.permissions_e').find('input').each(function(index) {
        // Agrega la clase 'is-invalid'
        if(input === `permissions.${index}`) {
            $(this).addClass('is-invalid');
        }
    });
}

function RemoveIsInvalidClassEditRoleAndPermissions() {
    $('#role_e').removeClass('is-invalid');

    // Itera sobre los inputs dentro del div
    $('.permissions_e').find('input').each(function() {
        // Remover la clase 'is-invalid'
        $(this).removeClass('is-invalid');
    });
}
