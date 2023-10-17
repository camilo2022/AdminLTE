function CreateRoleAndPermissionsModal() {
    RemoveIsValidClassCreateRoleAndPermissions();
    RemoveIsInvalidClassCreateRoleAndPermissions();

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

    let inputGroupAppend = $('<div>');
    inputGroupAppend.attr('class', 'input-group-append');

    let inputGroupText = $('<span>');
    inputGroupText.attr('class', 'input-group-text');

    let inputIcon = $('<i>');
    inputIcon.attr('class', 'fas fa-key');
  
    inputGroupText.append(inputIcon);
    inputGroupAppend.append(inputGroupText);
    // Construir la estructura de elementos
    inputGroup.append(input);
    inputGroup.append(inputGroupAppend);
    permissionGroup.append(inputGroup);

    // Agregar al contenedor
    $('.permissions_c').append(permissionGroup);
}

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
                    CreateRoleAndPermissionsModal();
                },
                error: function(xhr, textStatus, errorThrown) {
                    RemoveIsInvalidClassCreateRoleAndPermissions();
                    tableRolesAndPermissions.ajax.reload();
                    CreateRoleAndPermissionsAjaxError(xhr);
                    AddIsValidClassCreateRoleAndPermissions();
                }
            });
        } else {
            toastr.info('El rol y los permisos no fueron creados.')
        }
    });
}

function CreateRoleAndPermissionsAjaxError(xhr) {
    if(xhr.responseJSON.error){
        toastr.error(xhr.responseJSON.error.message);
        toastr.error(xhr.responseJSON.error.error);
    } else if(xhr.responseJSON.errors){
        $.each(xhr.responseJSON.errors, function(field, messages) {
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
    } else {
        toastr.error(xhr.responseJSON.message);
    }
}

function AddIsValidClassCreateRoleAndPermissions() {
    if (!$('#role_c').hasClass('is-invalid')) {
      $('#role_c').addClass('is-valid');
    }

    // Itera sobre los inputs dentro del div
    $('.permissions_c').find('input').each(function() {
    
        // Verifica si el input no tiene la clase 'is-invalid'
        if (!$(this).hasClass('is-invalid')) {
            // Agrega la clase 'is-valid'
            $(this).addClass('is-valid');
        }
    });
}

function RemoveIsValidClassCreateRoleAndPermissions() {
    $('#role_c').removeClass('is-valid');
  
    // Itera sobre los inputs dentro del div
    $('.permissions_c').find('input').each(function() {
        // Agrega la clase 'is-valid'
        $(this).removeClass('is-valid');
    });
}

function AddIsInvalidClassCreateRoleAndPermissions(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
    $('.permissions_c').find('input').each(function(index) {
        // Agrega la clase 'is-invalid'
        if(input === `permissions.${index}`) {
            $(this).addClass('is-invalid');
        }
    });
}

function RemoveIsInvalidClassCreateRoleAndPermissions() {
    $('#role_c').removeClass('is-invalid');
  
    // Itera sobre los inputs dentro del div
    $('.permissions_c').find('input').each(function() {
        // Remover la clase 'is-invalid'
        $(this).removeClass('is-invalid');
    });
}