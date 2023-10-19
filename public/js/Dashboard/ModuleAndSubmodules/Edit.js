function EditModuleAndSubmodulesModal() {
    RemoveIsInvalidClassEditModuleAndSubmodules();
    RemoveIsValidClassEditModuleAndSubmodules();
    $('.submodules_e').empty();
    $('#roles_access_e').empty();
    $('#module_e').val('');
    $('#icon_e').val('');
    $('#EditModuleAndSubmodulesAddPermissionButton').attr('data-count', 0)
    EditModuleAndSubmodulesAddSubmodule();

    $.ajax({
        url: `/Dashboard/ModulesAndSubmodules/Update/Query`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'roles': true
        },
        success: function(response) {
            EditModuleAndSubmodulesQueryRoles(response.data);
        },
        error: function(xhr, textStatus, errorThrown) {
            EditModuleAndSubmodulesAjaxError(xhr);
        }
    });
}

function EditModuleAndSubmodules(id) {
    Swal.fire({
        title: '¿Desea guardar el modulos y los submodulos?',
        text: 'El modulo y los submodulos serán creados.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/ModulesAndSubmodules/Update/${id}`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'module': $('#module_e').val(),
                    'icon': $('#icon_e').val(),
                    'roles': $('#roles_access_e .icheck-primary input[type="checkbox"]:checked').map(function () {
                        return $(this).attr('data-id');
                    }).get(),
                    'submodules': $('.submodules_e').map(function(index) {
                        return {
                            'submodule': $(this).find('.submodule_e .name_e').val(),
                            'url': $(this).find('.submodule_e .url_e').val(),
                            'icon': $(this).find('.submodule_e .subicon_e').val(),
                            'permission_id': $(this).find('.submodule_e .permission_e').val()
                        };
                    }).get()
                },
                success: function(response) {
                    tableModulesAndSubmodules.ajax.reload();
                    toastr.success(response.message);
                    $('#EditModuleAndSubmodulesModal').modal('hide');
                },
                error: function(xhr, textStatus, errorThrown) {
                    RemoveIsValidClassEditModuleAndSubmodules();
                    RemoveIsInvalidClassEditModuleAndSubmodules();
                    tableModulesAndSubmodules.ajax.reload();
                    EditModuleAndSubmodulesAjaxError(xhr);
                    AddIsValidClassEditModuleAndSubmodules();
                }
            });
        } else {
            toastr.info('El modulo y los submodulos no fueron creados.')
        }
    });
}

function EditModuleAndSubmodulesAddSubmodule(id, submodule, role, permission, url, icon) {
    // Crear el nuevo elemento HTML con jQuery
    let data = $('#EditModuleAndSubmodulesAddPermissionButton').attr('data-count');
    let newSubmodule = $('<div>').attr({
        'id': `group-submodule${data}`,
        'class': 'form-group submodule_e'
    });
    let card = $('<div>').addClass('card collapsed-card');
    let cardHeader = $('<div>').addClass('card-header border-0 ui-sortable-handle');
    let cardTitle = $('<h3>').addClass('card-title mt-1');
    let inputGroup = $('<div>').addClass('input-group');
    let input = $('<input>').attr({
        'type': 'text',
        'class': 'form-control name_e',
        'id': `name${data}_e`,
        'value': submodule,
        'data-id': id
    });
    let inputGroupAppend = $('<div>').addClass('input-group-append');
    let inputGroupText = $('<span>').addClass('input-group-text');
    let sliderIcon = $('<i>').addClass('fas fa-slider');
    let cardTools = $('<div>').addClass('card-tools');
    let collapseButton = $('<button>').attr({
        'type': 'button',
        'class': 'btn btn-info btn-sm ml-2 mt-2',
        'data-card-widget': 'collapse'
    });
    let plusIcon = $('<i>').addClass('fas fa-plus');
    let removeButton = $('<button>').attr({
        'type': 'button',
        'class': 'btn btn-danger btn-sm ml-2 mt-2',
        'data-card-widget': 'remove',
        'onclick': `EditModuleAndSubmodulesRemoveSubmodule(${data})`
    });
    let timesIcon = $('<i>').addClass('fas fa-times');

    // Anidar elementos
    inputGroupText.append(sliderIcon);
    inputGroupAppend.append(inputGroupText);
    inputGroup.append(input, inputGroupAppend);
    cardTitle.append(inputGroup);
    collapseButton.append(plusIcon);
    removeButton.append(timesIcon);
    cardTools.append(collapseButton, removeButton);
    cardHeader.append(cardTitle, cardTools);
    card.append(cardHeader);

    let cardBody = $('<div>').addClass('card-body').css('display', 'none');
    let roleForm = $('<div>').addClass('form-group');
    let roleLabel = $('<label>').attr('for', '').text('Role');
    let roleIcon = $('<i>').attr({
        'class': 'ml-2 far fa-circle-question',
        'onclick': 'SuggestionModuleRoles()'
    });
    let roleSelect = $('<select>').attr({
        'id': `role${data}_e`,
        'class': 'form-control role_e',
        'onchange': `EditModuleAndSubmodulesQueryPermissions(this, 'permission${data}_e')`
    });
    let roleOption = $('<option>').attr('value', '').text('Seleccione');
    roleSelect.append(roleOption);
    $('#roles_access_e .icheck-primary input[type="checkbox"]:checked').map(function() {
        roleSelect.append(
            $('<option>')
            .attr('value', $(this).attr('id'))
            .text($(this).attr('id'))
            .prop('selected', $(this).attr('id') === role)
        );
        $(this).attr('id') === role ? roleSelect.val(role).change() : '' ;
    });
    roleForm.append(roleLabel, roleIcon, roleSelect);
    let permissionForm = $('<div>').addClass('form-group');
    let permissionLabel = $('<label>').attr('for', '').text('Permission');
    let permissionIcon = $('<i>').attr({
        'class': 'ml-2 far fa-circle-question',
        'onclick': 'SuggestionSumodulePermission()'
    });
    let permissionSelect = $('<select>').attr({
        'id': `permission${data}_e`,
        'class': 'form-control permission_e',
        'onchange': `EditModuleAndSubmodulesWriteUrl(this, 'url${data}_e')`
    });
    let permissionOption = $('<option>').attr('value', '').text('Seleccione');
    permissionSelect.append(permissionOption);
    permissionForm.append(permissionLabel, permissionIcon, permissionSelect);
    let urlForm = $('<div>').addClass('form-group');
    let urlLabel = $('<label>').attr('for', '').text('Ruta');
    let urlSuggestion = $('<i>').attr({
        'class': 'ml-2 far fa-circle-question',
        'onclick': 'SuggestionSumoduleRoute()'
    });
    let urlInputGroup = $('<div>').addClass('input-group');
    let urlInput = $('<input>').attr({
        'type': 'text', 
        'id': `url${data}_e`,
        'class': 'form-control url_e',
        'readonly': 'readonly'
    });
    let urlInputAppend = $('<div>').addClass('input-group-append');
    let urlIcon = $('<span>').addClass('input-group-text').append($('<i>').addClass('fas fa-route-highway'));
    urlInputAppend.append(urlIcon);
    urlInputGroup.append(urlInput, urlInputAppend);
    urlForm.append(urlLabel, urlSuggestion, urlInputGroup);
    let subIconForm = $('<div>').addClass('form-group');
    let subIconLabel = $('<label>').attr('for', '').text('Icono Submodulo');
    let subIconSuggestion = $('<i>').attr({
        'class': 'ml-2 far fa-circle-question',
        'onclick': 'SuggestionSubmoduleIcon()'
    });
    let subIconInputGroup = $('<div>').addClass('input-group');
    let subIconInput = $('<input>').attr({
        'type': 'text', 
        'id': `subicon${data}_e`,
        'class': 'form-control subicon_e',
        'onkeyup': `EditModuleAndSubmodulesChangeClassIcon(this, 'icono${data}_e')`
    });
    let subIconInputAppend = $('<div>').addClass('input-group-append');
    let subIconInputIcon = $('<span>').addClass('input-group-text').append($('<i>').attr('id', `icono${data}_e`));
    subIconInputAppend.append(subIconInputIcon);
    subIconInputGroup.append(subIconInput, subIconInputAppend);
    subIconForm.append(subIconLabel, subIconSuggestion, subIconInputGroup);

    cardBody.append(roleForm, permissionForm, urlForm, subIconForm);
    card.append(cardBody);

    newSubmodule.append(card);

    // Agregar el nuevo elemento al elemento con clase "submodules_e"
    $('.submodules_e').append(newSubmodule);
    data++;
    $('#EditModuleAndSubmodulesAddPermissionButton').attr('data-count', data)
}

function EditModuleAndSubmodulesRemoveSubmodule(index) {
    $(`#group-submodule${index}`).remove();
}

function EditModuleAndSubmodulesChangeClassIcon(input, icon) {
    $(`#${icon}`).attr('class', input.value);
}

function EditModuleAndSubmodulesWriteUrl(selectPermission, inputUrl) {
    if($(selectPermission).val() === '') {
        $(`#${inputUrl}`).val('');
    } else {
        $(`#${inputUrl}`).val(`/${$(selectPermission).find('option:selected').text().replace(/\./g, '/')}`);
    }
}

function EditModuleAndSubmodulesQueryRoles(roles) {
    let rolesDiv = $('#roles_access_e');

    $.each(roles, function (i, role) {
        let roleDiv = $('<div>').addClass('row pl-2 icheck-primary');

        let roleCheckbox = $('<input>').attr({
            'id': role.name,
            'type': 'checkbox',
            'onclick': 'EditModuleAndSubmodulesRoles(this)',
            'data-id': role.id
        });

        let roleLabel = $('<label>').text(role.name).attr({
            'for': role.name,
            'class': 'mt-3 ml-3'
        });

        roleDiv.append(roleCheckbox);
        roleDiv.append(roleLabel);
        rolesDiv.append(roleDiv);
    });
}

function EditModuleAndSubmodulesRoles(checkbox) {
    // Obtener IDs de los checkboxes marcados en #roles_access_e
    let role = $(checkbox).attr('id');

    // Recorrer #submodules_e
    $('.submodules_e').each(function () {
        let submoduleElement = $(this);
        let selectRole = submoduleElement.find('select.role_e');

        // Verificar si el checkbox está marcado
        if ($(checkbox).is(':checked')) {
            // Agregar el role al select
            selectRole.append($('<option>', {
                'value': role,
                'text': role
            }));
        } else {
            let optionToRemove = selectRole.find(`option[value="${role}"]`);

            // Verificar si la opción estaba seleccionada antes de la eliminación
            let isSelected = optionToRemove.is(':selected');

            if (isSelected) {
                let selectPermission = submoduleElement.find('select.permission_e');
                submoduleElement.find('input.url_e').val('')
                selectPermission.empty().append(
                    $('<option>', {
                        'value': '',
                        'text': 'Seleccione'
                    })
                );
            }
            // Quitar el role del select
            optionToRemove.remove();
        }
    });
}

function EditModuleAndSubmodulesQueryPermissions(selectRoles, selectPermissions) {
    $.ajax({
        url: `/Dashboard/ModulesAndSubmodules/Update/Query`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'role': $(selectRoles).val()
        },
        success: function(response) {
            if($(selectRoles).val() !== '') {
                EditModuleAndSubmodulesPermissions(selectPermissions, response.data.permissions);
            }
        },
        error: function(xhr, textStatus, errorThrown) {
            EditModuleAndSubmodulesAjaxError(xhr);
            $(`#${selectPermissions}`).empty().append(
                $('<option>', {
                    'value': '',
                    'text': 'Seleccione'
                })
            );
        }
    });
}

function EditModuleAndSubmodulesPermissions(selectPermissions, permissions) {
    let select = $(`#${selectPermissions}`).empty().append(
        $('<option>',{
            'value': '',
            'text': 'Seleccione'
        })
    );
    // Agregar opciones con los roles seleccionados
    $.each(permissions, function(index, permission) {
        select.append($('<option>',
            {
                'value': permission.id,
                'text': permission.name
            }
        ));
    });
}

function EditModuleAndSubmodulesAjaxError(xhr) {
    if(xhr.responseJSON.error){
        toastr.error(xhr.responseJSON.error.message);
        toastr.error(xhr.responseJSON.error.error);
    } else if(xhr.responseJSON.errors){
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassEditModuleAndSubmodules(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
    } else {
        toastr.error(xhr.responseJSON.message);
    }
}

function AddIsValidClassEditModuleAndSubmodules() {
    if (!$('#module_e').hasClass('is-invalid')) {
        $('#module_e').addClass('is-valid');
    }

    if (!$('#icon_e').hasClass('is-invalid')) {
        $('#icon_e').addClass('is-valid');
    }

    $('.submodules_e').find('div.submodule_e').each(function(index) {
        if (!$(this).find('select.role_e').hasClass('is-invalid')) {
            $(this).find('select.role_e').addClass('is-valid');
        }
        if (!$(this).find('select.permission_e').hasClass('is-invalid')) {
            $(this).find('select.permission_e').addClass('is-valid');
        }
        if (!$(this).find('input.subicon_e').hasClass('is-invalid')) {
            $(this).find('input.subicon_e').addClass('is-valid');
        }
        if (!$(this).find('input.name_e').hasClass('is-invalid')) {
            $(this).find('input.name_e').addClass('is-valid');
        }
        if (!$(this).find('input.url_e').hasClass('is-invalid')) {
            $(this).find('input.url_e').addClass('is-valid');
        }
    });
}

function RemoveIsValidClassEditModuleAndSubmodules() {
    $('#module_e').removeClass('is-valid');
    $('#icon_e').removeClass('is-valid');

    $('.submodules_e').find('div.submodule_e').each(function(index) {
        $(this).find('select.role_e').removeClass('is-valid');
        $(this).find('select.permission_e').removeClass('is-valid');
        $(this).find('input.subicon_e').removeClass('is-valid');
        $(this).find('input.name_e').removeClass('is-valid');
        $(this).find('input.url_e').removeClass('is-valid');
    });
}

function AddIsInvalidClassEditModuleAndSubmodules(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }

    $('.submodules_e').find('div.submodule_e').each(function(index) {
        // Agrega la clase 'is-invalid'
        if(input === `submodules.${index}.permission_id`) {
            if (!$(this).find('select.permission_e').hasClass('is-valid')) {
                $(this).find('select.role_e').addClass('is-invalid');
                $(this).find('select.permission_e').addClass('is-invalid');
            }
        }
        if(input === `submodules.${index}.icon`) {
            if (!$(this).find('input.subicon_e').hasClass('is-valid')) {
                $(this).find('input.subicon_e').addClass('is-invalid');
            }
        }
        if(input === `submodules.${index}.submodule`) {
            if (!$(this).find('input.name_e').hasClass('is-valid')) {
                $(this).find('input.name_e').addClass('is-invalid');
            }
        }
        if(input === `submodules.${index}.url`) {
            if (!$(this).find('input.url_e').hasClass('is-valid')) {
                $(this).find('input.url_e').addClass('is-invalid');
            }
        }
    });
}

function RemoveIsInvalidClassEditModuleAndSubmodules() {
    $('#module_e').removeClass('is-invalid');
    $('#icon_e').removeClass('is-invalid');

    $('.submodules_e').find('div.submodule_e').each(function(index) {
        $(this).find('select.role_e').removeClass('is-invalid');
        $(this).find('select.permission_e').removeClass('is-invalid');
        $(this).find('input.subicon_e').removeClass('is-invalid');
        $(this).find('input.name_e').removeClass('is-invalid');
        $(this).find('input.url_e').removeClass('is-invalid');
    });
}
