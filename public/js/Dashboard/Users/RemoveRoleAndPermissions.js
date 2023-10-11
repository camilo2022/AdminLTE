function RemoveRoleAndPermissionUserModal(id, email) {
    $.ajax({
        url: `/Dashboard/Users/RemoveRoleAndPermissions/Query`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'id': id
        },
        success: function(response) {
            if($('meta[name="user-id"]').attr('content') == id) {
                toastr.warning('Cuidado, vas a remover el rol y los permisos de tu usuario.');
            }

            if (response.data.length == 0) {
                toastr.info('No tiene roles y permisos asignados para remover.')
                return false;
            }

            $('#email_r').val(email);
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

                var collapseButton = $('<button type="button" class="btn btn-info btn-sm" data-card-widget="collapse">');
                collapseButton.append('<i class="fas fa-plus"></i>');

                var removeButton = $('<button type="button" class="btn btn-danger btn-sm ml-2" data-card-widget="remove">');
                removeButton.append('<i class="fas fa-times"></i>');

                // Agregar elementos al cardHeader
                cardTools.append(collapseButton);
                cardTools.append(removeButton);
                cardHeader.append(cardTitle);
                cardHeader.append(cardTools);

                // Crear el div del card-body
                var cardBody = $('<div class="card-body" style="display: none;">');

                // Crear el div para checkboxes
                var checkboxesDiv = $('<div class="row pl-2 ml-2">');
                var selectAllCheckbox = $('<input type="checkbox">');
                var selectAllLabel = $('<label>').text('Seleccionar todos los permisos');
                selectAllCheckbox.change(function() {
                    var checkboxes = cardBody.find('input[type="checkbox"]');
                    checkboxes.prop('checked', selectAllCheckbox.prop('checked'));
                });

                // Agregar elementos al cardBody
                checkboxesDiv.append(selectAllCheckbox);
                checkboxesDiv.append(selectAllLabel);
                cardBody.append(checkboxesDiv);

                // Crear checkboxes para permisos
                $.each(item.permissions, function (i, permission) {
                    var permissionDiv = $('<div class="row pl-4 ml-2">');
                    var permissionCheckbox = $(`<input type="checkbox" id="${permission}">`);
                    var permissionLabel = $('<label>').text(permission);

                    // Agregar elementos al cardBody
                    permissionDiv.append(permissionCheckbox);
                    permissionDiv.append(permissionLabel);
                    cardBody.append(permissionDiv);
                });

                // Crear el div del card-footer
                var cardFooter = $('<div class="card-footer bg-transparent" style="display: none;">');
                var footerRow = $('<div class="row d-flex justify-content-end mr-4 pb-4">');
                var saveButton = $('<button type="button" class="btn btn-primary" title="Remover rol y permisos.">');
                saveButton.append('<i class="fas fa-floppy-disk"></i>');

                // Agregar evento click al botón de guardar
                saveButton.click(function() {
                    var selectedPermissions = [];
                    cardBody.find('input[type="checkbox"]:checked').each(function() {
                        if($(this).attr('id') !== undefined) {
                            selectedPermissions.push($(this).attr('id'));
                        }
                    });

                    // Llamar a la función RemoveRoleAndPermission con el nombre del rol y los permisos
                    RemoveRoleAndPermission(id, item.role, selectedPermissions, email);
                });

                // Agregar elementos al cardFooter
                footerRow.append(saveButton);
                cardFooter.append(footerRow);

                // Agregar cardHeader, cardBody y cardFooter al card
                card.append(cardHeader);
                card.append(cardBody);
                card.append(cardFooter);

                // Agregar el card al contenedor
                $('#permissions-container-remove').append(card);

                // Mostrar el modal
                $('#RemoveRoleAndPermissionUserModal').modal('show');
            });

        },
        error: function(xhr, textStatus, errorThrown) {
            tableUsers.ajax.reload();
            if(xhr.responseJSON.error){
                toastr.error(xhr.responseJSON.error.message)
            }
            if(xhr.responseJSON.errors){
                $.each(xhr.responseJSON.errors, function(field, messages) {
                    $.each(messages, function(index, message) {
                        toastr.error(message)
                    });
                });
            }
        }
    });
}

function RemoveRoleAndPermission(id, role, permissions, email) {
    if($('meta[name="user-id"]').attr('content') == id) {
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
                    $('#RemoveRoleAndPermissionUserModal').modal('hide');
                    RemoveRoleAndPermissionUserModal(id, email);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableUsers.ajax.reload();
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
            toastr.error('El rol y los permisos no fueron removidos al usuario.')
        }
    });
}
