function EditRoleAndPermissionsModal(id, role, permissions) {
    $('#role_e').val(role);
    $('.permissions_e').empty();
    $('#EditRoleAndPermissionsButton').attr('onclick', `EditRoleAndPermissions(${id})`);

    $.each(permissions, function (i, permission) {
        let permissionGroup = $('<div>');
        permissionGroup.attr('class', 'form-group permission-group');

        let inputGroup = $('<div>');
        inputGroup.attr('class', 'input-group');

        let input = $('<input>');
        input.attr('type', 'text');
        input.attr('class', 'form-control');
        input.attr('id', `permission_e${i}`);
        input.attr('name', 'permissions_e[]');
        input.attr('value', permission);

        let inputGroupAppend = $('<div>');
        inputGroupAppend.attr('class', 'input-group-append');

        let span = $('<span>');
        span.attr('class', 'input-group-text bg-red permission-toggle');
        span.attr('data-id', i);
        span.attr('onclick', 'EditRoleAndPermissionsRemovePermission(this)');

        let icon = $('<i>');
        icon.attr('class', 'fas fa-minus');

        // Construir la estructura de elementos
        span.append(icon);
        inputGroupAppend.append(span);
        inputGroup.append(input, inputGroupAppend);
        permissionGroup.append(inputGroup);

        // Agregar al contenedor
        $('.permissions_e').append(permissionGroup);
        $('#EditRoleAndPermissionsAddPermissionButton').attr('data-count', i + 1);
    })
    $('#EditRoleAndPermissionsModal').modal('show');
}

function EditRoleAndPermissionsAddPermission(permission) {
    let permissionCount = $(permission).data('count');
    console.log(permissionCount);
    let newPermissionGroup = $('<div>');
    newPermissionGroup.attr('class', 'form-group permission-group');

    let inputGroup = $('<div>');
    inputGroup.attr('class', 'input-group');

    let input = $('<input>');
    input.attr('type', 'text');
    input.attr('class', 'form-control');
    input.attr('id', `permission_e${permissionCount}`);
    input.attr('name', 'permissions_e[]');

    let inputGroupAppend = $('<div>');
    inputGroupAppend.attr('class', 'input-group-append');

    let span = $('<span>');
    span.attr('class', 'input-group-text bg-red permission-toggle');
    span.attr('data-id', permissionCount);
    span.attr('onclick', 'EditRoleAndPermissionsRemovePermission(this)');

    let icon = $('<i>');
    icon.attr('class', 'fas fa-minus');

    // Construir la estructura de elementos
    span.append(icon);
    inputGroupAppend.append(span);
    inputGroup.append(input, inputGroupAppend);
    newPermissionGroup.append(inputGroup);

    // Agregar al contenedor
    $('.permissions_e').append(newPermissionGroup);
    permissionCount++
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
                    tableRolesAndPermissions.ajax.reload();
                    if(xhr.responseJSON.error){
                        toastr.error(xhr.responseJSON.error.message);
                    }
                    if(xhr.responseJSON.errors){
                        $.each(xhr.responseJSON.errors, function(field, messages) {
                            $.each(messages, function(index, message) {
                                toastr.error(message);
                            });
                        });
                    }
                }
            });
        } else {
            toastr.info('El rol y los permisos no fueron actualizados.')
        }
    });
}
