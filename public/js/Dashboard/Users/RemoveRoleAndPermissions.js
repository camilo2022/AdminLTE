function RemoveRoleAndPermissionUserModal(id, email) {
    $.ajax({
        url: `/Dashboard/Users/RemoveRoleAndPermissions/Query`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'id': id
        },
        success: function(response) {
            if($('meta[name="user-id"]').attr('content') === id) {
                toastr.warning('Cuidado, vas a remover el rol y los permisos de tu usuario.');
            }

            if (response.data.length === 0) {
                $('#RemoveRoleAndPermissionUserModal').modal('hide');
                toastr.info('No tiene roles y permisos asignados para remover.');
                return false;
            }

            $('#email_r').val(email);
            $('#permissions-container-assign').empty();
            $('#permissions-container-remove').empty();
            $.each(response.data, function (index, item) {
                // Crear el div del card
                var card = $('<div class="card collapsed-card">');

                // Crear el div del card-header
                var cardHeader = $('<div class="card-header border-0 ui-sortable-handle">');
                var cardTitle = $('<h3 class="card-title mt-1">');
                var cardIcon = $('<i class="fas fa-shield-xmark fa-lg mr-1"></i>');

                cardTitle.append(cardIcon);
                cardTitle.append(item.role);

                // Crear el div de card-tools
                var cardTools = $('<div class="card-tools">');

                var saveButton = $('<button type="button" class="btn btn-primary btn-sm" title="Remover rol y permisos.">');
                saveButton.append('<i class="fas fa-floppy-disk"></i>');

                var collapseButton = $('<button type="button" class="btn btn-info btn-sm ml-2" data-card-widget="collapse">');
                collapseButton.append('<i class="fas fa-plus"></i>');

                var removeButton = $('<button type="button" class="btn btn-danger btn-sm ml-2" data-card-widget="remove">');
                removeButton.append('<i class="fas fa-times"></i>');

                // Agregar elementos al cardHeader
                cardTools.append(saveButton);
                cardTools.append(collapseButton);
                cardTools.append(removeButton);
                cardHeader.append(cardTitle);
                cardHeader.append(cardTools);

                // Crear el div del card-body
                var cardBody = $('<div class="card-body" style="display: none;">');

                // Crear el div para checkboxes
                let checkboxesDiv = $('<div class="row icheck-primary">');
                let selectAllCheckbox = $('<input type="checkbox">');
                selectAllCheckbox.attr('id', `selectAllCheckbox${index}`);

                let selectAllLabel = $('<label>').text('Seleccionar todos los permisos');
                selectAllLabel.attr('for', `selectAllCheckbox${index}`);

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
                    let permissionDiv = $('<div class="row pl-2 icheck-primary">');
                    let permissionCheckbox = $(`<input type="checkbox">`);
                    permissionCheckbox.attr('id', permission);

                    let permissionLabel = $('<label>').text(permission);
                    permissionLabel.attr('for', permission);
                    permissionLabel.attr('class', 'mt-3 ml-3');

                    // Agregar elementos al cardBody
                    permissionDiv.append(permissionCheckbox);
                    permissionDiv.append(permissionLabel);
                    cardBody.append(permissionDiv);
                });

                // Agregar evento click al botón de guardar
                saveButton.click(function() {
                    var selectedPermissions = [];
                    cardBody.find('input[type="checkbox"]:checked').each(function() {
                        if($(this).attr('id') !== `selectAllCheckbox${index}`) {
                            selectedPermissions.push($(this).attr('id'));
                        }
                    });

                    // Llamar a la función RemoveRoleAndPermission con el nombre del rol y los permisos
                    RemoveRoleAndPermission(id, item.role, selectedPermissions, email);
                });

                // Agregar cardHeader, cardBody y cardFooter al card
                card.append(cardHeader);
                card.append(cardBody);

                // Agregar el card al contenedor
                $('#permissions-container-remove').append(card);

                // Mostrar el modal
                $('#RemoveRoleAndPermissionUserModal').modal('show');
            });
        },
        error: function(xhr, textStatus, errorThrown) {
            tableUsers.ajax.reload();
            if(xhr.responseJSON.error){
                toastr.error(xhr.responseJSON.error.message);
                toastr.error(xhr.responseJSON.error.error);
            } else if(xhr.responseJSON.errors){
                $.each(xhr.responseJSON.errors, function(field, messages) {
                    $.each(messages, function(index, message) {
                        toastr.error(message)
                    });
                });
            } else {
                toastr.error(xhr.responseJSON.message);
            }
        }
    });
}

function RemoveRoleAndPermission(id, role, permissions, email) {
    if($('meta[name="user-id"]').attr('content') === id) {
        toastr.warning('Cuidado, vas a remover el rol y los permisos de tu usuario.');
    }
    Swal.fire({
        title: '¿Desea remover el rol y los permisos al usuario?',
        text: 'Se removera al usuario el rol y los permisos especificados.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, remover!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Users/RemoveRoleAndPermissions`,
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
                    RemoveRoleAndPermissionUserModal(id, email);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableUsers.ajax.reload();
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
            });
        } else {
            toastr.info('El rol y los permisos no fueron removidos al usuario.')
        }
    });
}
