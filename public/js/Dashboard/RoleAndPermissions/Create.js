function CreateRoleAndPermissionsAddPermission(permission) {
    let permissionCount = $(permission).data('count');

    let newPermissionGroup = $('<div>');
    newPermissionGroup.attr('class', 'form-group permission-group');

    let inputGroup = $('<div>');
    inputGroup.attr('class', 'input-group');

    let input = $('<input>');
    input.attr('type', 'text');
    input.attr('class', 'form-control');
    input.attr('id', `permission_c${permissionCount}`);
    input.attr('name', 'permissions_c[]');

    let inputGroupAppend = $('<div>');
    inputGroupAppend.attr('class', 'input-group-append');

    let span = $('<span>');
    span.attr('class', 'input-group-text bg-red permission-toggle');
    span.attr('data-id', permissionCount);
    span.attr('onclick', 'CreateRoleAndPermissionsRemovePermission(this)');

    let icon = $('<i>');
    icon.attr('class', 'fas fa-minus');

    // Construir la estructura de elementos
    span.append(icon);
    inputGroupAppend.append(span);
    inputGroup.append(input, inputGroupAppend);
    newPermissionGroup.append(inputGroup);

    // Agregar al contenedor
    $('.permissions_c').append(newPermissionGroup);
    permissionCount++
    $(permission).data('count', permissionCount);
}

function CreateRoleAndPermissionsRemovePermission(permission) {
    let permissionId = $(permission).data('id');
    let permissionGroup = $(`#permission_c${permissionId}`).closest('.permission-group');
    permissionGroup.remove();
}

function CreateRoleAndPermissions() {
    Swal.fire({
        title: '¿Desea guardar el rol y los permisos?',
        text: 'El rol y los permisos serán creados.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/RolesAndPermissions/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'role': $('#role_c').val(),
                    'permissions': $('input[name="permissions_c[]"]').map(function() {
                        return $(this).val();
                    }).get()
                },
                success: function(response) {
                    tableRolesAndPermissions.ajax.reload();
                    toastr.success(response.message);
                    $('#CreateRoleAndPermissionsModal').modal('hide');
                    CreateRoleAndPermissionsModalClean();
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
            toastr.info('El rol y los permisos no fueron creados.')
        }
    });
}

function CreateRoleAndPermissionsModalClean() {
    $('#role_c').val('');
    $('.permissions_c').empty();

    let permissionGroup = $('<div>');
    permissionGroup.attr('class', 'form-group');

    let inputGroup = $('<div>');
    inputGroup.attr('class', 'input-group');

    let input = $('<input>');
    input.attr('type', 'text');
    input.attr('class', 'form-control');
    input.attr('id', `permission_c0`);
    input.attr('name', 'permissions_c[]');

    // Construir la estructura de elementos
    inputGroup.append(input);
    permissionGroup.append(inputGroup);

    // Agregar al contenedor
    $('.permissions_c').append(permissionGroup);
}
