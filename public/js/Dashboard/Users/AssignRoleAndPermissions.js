function AssignRoleAndPermissionUserModal(id, email) {
    $.ajax({
        url: `/Dashboard/Users/AssignRoleAndPermissions/Query`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'id': id
        },
        success: function(response) {
            if (response.data.length === 0) {
                $('#AssignRoleAndPermissionUserModal').modal('hide');
                toastr.info('Tiene todos los roles y permisos ya asignados.');
                return false;
            }

            $('#email_a').val(email);
            $('#permissions-container-remove').empty();
            $('#permissions-container-assign').empty();
            $.each(response.data, function (index, item) {
                // Crear el div del card
                let card = $('<div>').attr({
                    'class': 'card collapsed-card'
                });

                // Crear el div del card-header
                let cardHeader = $('<div>').attr({
                    'class': 'card-header border-0 ui-sortable-handle',
                });
                let cardTitle = $('<h3>').attr({
                    'class': 'card-title mt-1'
                });
                let cardIcon = $('<i>').attr({
                    'class': 'fas fa-shield-check fa-lg mr-1'
                });

                cardTitle.append(cardIcon);
                cardTitle.append(item.role);

                // Crear el div de card-tools
                let cardTools = $('<div class="card-tools">');

                var saveButton = $('<button type="button" class="btn btn-primary btn-sm" title="Remover rol y permisos.">');
                saveButton.append('<i class="fas fa-floppy-disk"></i>');

                let collapseButton = $('<button type="button" class="btn btn-info btn-sm ml-2" data-card-widget="collapse">');
                collapseButton.append('<i class="fas fa-plus"></i>');

                let removeButton = $('<button type="button" class="btn btn-danger btn-sm ml-2" data-card-widget="remove">');
                removeButton.append('<i class="fas fa-times"></i>');

                // Agregar elementos al cardHeader
                cardTools.append(saveButton);
                cardTools.append(collapseButton);
                cardTools.append(removeButton);
                cardHeader.append(cardTitle);
                cardHeader.append(cardTools);

                // Crear el div del card-body
                let cardBody = $('<div>').attr({
                    'class': 'card-body',
                    'style': 'display: none'
                });

                // Crear el div para checkboxes
                let checkboxesDiv = $('<div>').attr({
                    'class': 'row icheck-primary'
                });
                let selectAllCheckbox = $('<input type="checkbox">').attr({
                    'id': `selectAllCheckbox${index}`
                });

                let selectAllLabel = $('<label>').text('Seleccionar todos los permisos').attr({
                    'for': `selectAllCheckbox${index}`,
                });

                selectAllCheckbox.change(function() {
                    let checkboxes = cardBody.find('input[type="checkbox"]');
                    checkboxes.prop('checked', selectAllCheckbox.prop('checked'));
                });

                // Agregar elementos al cardBody

                checkboxesDiv.append(selectAllCheckbox);
                checkboxesDiv.append(selectAllLabel);
                checkboxesDiv.append('<br>');
                cardBody.append(checkboxesDiv);

                // Crear checkboxes para permisos
                $.each(item.permissions, function (i, permission) {
                    let permissionDiv = $('<div>').attr({
                        'class': 'row pl-2 icheck-primary'
                    });
                    let permissionCheckbox = $(`<input type="checkbox">`).attr({
                        'id': permission
                    });

                    let permissionLabel = $('<label>').text(permission).attr({
                        'for': permission, 'class': 'mt-3 ml-3'
                    });

                    // Agregar elementos al cardBody
                    permissionDiv.append(permissionCheckbox);
                    permissionDiv.append(permissionLabel);
                    cardBody.append(permissionDiv);
                });

                // Agregar evento click al botón de guardar
                saveButton.click(function() {
                    let selectedPermissions = [];
                    cardBody.find('input[type="checkbox"]:checked').each(function() {
                        if($(this).attr('id') !== `selectAllCheckbox${index}`) {
                            selectedPermissions.push($(this).attr('id'));
                        }
                    });

                    // Llamar a la función RemoveRoleAndPermission con el nombre del rol y los permisos
                    AssignRoleAndPermission(id, item.role, selectedPermissions, email);
                });

                // Agregar cardHeader, cardBody y cardFooter al card
                card.append(cardHeader);
                card.append(cardBody);

                // Agregar el card al contenedor
                $('#permissions-container-assign').append(card);

                // Mostrar el modal
                $('#AssignRoleAndPermissionUserModal').modal('show');
            });
        },
        error: function(xhr, textStatus, errorThrown) {
            tableUsers.ajax.reload();
            AssignRoleAndPermissionAjaxError(xhr);
        }
    });
}

function AssignRoleAndPermission(id, role, permissions, email) {
    Swal.fire({
        title: '¿Desea asignar el rol y los permisos al usuario?',
        text: 'Se asignara al usuario el rol y los permisos especificados.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, asignar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Users/AssignRoleAndPermissions`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id,
                    'role': role,
                    'permissions': permissions
                },
                success: function(response) {
                    tableUsers.ajax.reload();
                    toastr.success(response.message);
                    AssignRoleAndPermissionUserModal(id, email);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableUsers.ajax.reload();
                    AssignRoleAndPermissionAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El rol y los permisos no fueron asignados al usuario.');
        }
    });
}

function AssignRoleAndPermissionAjaxError(xhr) {
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
