function CreateCategoryAndSubcategoriesModal() {
    $.ajax({
        url: `/Dashboard/CategoriesAndSubcategories/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function(response) {
            CreateCategoryAndSubcategoriesModalCleaned();
            CreateCategoryAndSubcategoriesQueryRoles(response.data);
            CreateCategoryAndSubcategoriesAjaxSuccess(response);
            $('#CreateCategoryAndSubcategoriesModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            CreateCategoryAndSubcategoriesAjaxError(xhr);
        }
    });
}

function CreateCategoryAndSubcategoriesModalCleaned() {
    RemoveIsInvalidClassCreateCategoryAndSubcategories();
    RemoveIsValidClassCreateCategoryAndSubcategories();
    $('.subcategories_c').empty();
    $('#name_c').val('');
    $('#code_c').val('');
    $('#description_c').val('');
    $('#CreateCategoryAndSubcategoriesAddSubcategoryButton').attr('data-count', 0);
    CreateCategoryAndSubcategoriesAddSubcategory();
}

function CreateCategoryAndSubcategories() {
    Swal.fire({
        title: '¿Desea guardar la categoria y subcategorias?',
        text: 'La categoria y subcategorias serán creados.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/CategoriesAndSubcategories/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'category': $('#name_c').val(),
                    'code': $('#code_c').val(),
                    'description': $('#description_c').val(),
                    'subcategories': $('.subcategories_c').find('div.subcategories_c').map(function(index) {
                        return {
                            'subcategory': $(this).find('input.name_c').val(),
                            'code': $(this).find('input.code_c').val(),
                            'description': $(this).find('input.description_c').val()
                        };
                    }).get()
                },
                success: function(response) {
                    tableCategoriesAndSubcategories.ajax.reload();
                    CreateCategoryAndSubcategoriesAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableCategoriesAndSubcategories.ajax.reload();
                    CreateCategoryAndSubcategoriesAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La categoria y subcategorias no fueron creados.')
        }
    });
}

function CreateCategoryAndSubcategoriesAddSubcategory() {
    // Crear el nuevo elemento HTML con jQuery
    let id = $('#CreateCategoryAndSubcategoriesAddSubcategoryButton').attr('data-count');
    let newSubcategory = $('<div>').attr({
        'id': `group-subcategory${id}`,
        'class': 'form-group subcategory_c'
    });
    let card = $('<div>').addClass('card collapsed-card');
    let cardHeader = $('<div>').addClass('card-header border-0 ui-sortable-handle');
    let cardTitle = $('<h3>').addClass('card-title mt-1');
    let inputGroup = $('<div>').addClass('input-group');
    let input = $('<input>').attr({
        'type': 'text',
        'class': 'form-control name_c',
        'id': `name${id}_c`,
        'name': ''
    });
    let inputGroupAppend = $('<div>').addClass('input-group-append');
    let inputGroupText = $('<span>').addClass('input-group-text');
    let signatureIcon = $('<i>').addClass('fas fa-signature');
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
        'onclick': `CreateCategoryAndSubcategoriesRemoveSubcategory(${id})`
    });
    let timesIcon = $('<i>').addClass('fas fa-times');

    // Anidar elementos
    inputGroupText.append(signatureIcon);
    inputGroupAppend.append(inputGroupText);
    inputGroup.append(input, inputGroupAppend);
    cardTitle.append(inputGroup);
    collapseButton.append(plusIcon);
    removeButton.append(timesIcon);
    cardTools.append(collapseButton, removeButton);
    cardHeader.append(cardTitle, cardTools);
    card.append(cardHeader);

    let cardBody = $('<div>').addClass('card-body').css('display', 'none');
    
    
    let codeForm = $('<div>').addClass('form-group');
    let codeLabel = $('<label>').attr('for', '').text('Codigo');
    let codeInputGroup = $('<div>').addClass('input-group');
    let codeInput = $('<input>').attr({
        'type': 'text',
        'id': `code${id}_c`,
        'class': 'form-control code_c',
    });
    let codeInputAppend = $('<div>').addClass('input-group-append');
    let codeIcon = $('<span>').addClass('input-group-text').append($('<i>').addClass('fas fa-code'));
    codeInputAppend.append(codeIcon);
    codeInputGroup.append(codeInput, codeInputAppend);
    codeForm.append(codeLabel, codeInputGroup);

    let descriptionForm = $('<div>').addClass('form-group');
    let descriptionLabel = $('<label>').attr('for', '').text('Descripcion');
    let descriptionInputGroup = $('<div>').addClass('input-group');
    let descriptionInput = $('<textarea>').attr({
        'type': 'text',
        'id': `description${id}_c`,
        'class': 'form-control description_c',
        'cols': '30',
        'rows': '3'
    });
    let descriptionInputAppend = $('<div>').addClass('input-group-append');
    let descriptionIcon = $('<span>').addClass('input-group-text').append($('<i>').addClass('fas fa-text-size'));
    descriptionInputAppend.append(descriptionIcon);
    descriptionInputGroup.append(descriptionInput, descriptionInputAppend);
    descriptionForm.append(descriptionLabel, descriptionInputGroup);


    cardBody.append(codeForm, descriptionForm);
    card.append(cardBody);

    newSubcategory.append(card);

    // Agregar el nuevo elemento al elemento con clase "subcategorys_c"
    $('.subcategories_c').append(newSubcategory);
    id++;
    $('#CreateCategoryAndSubcategoriesAddSubcategoryButton').attr('data-count', id)
}

function CreateCategoryAndSubcategoriesRemoveSubcategory(index) {
    $(`#group-subcategory${index}`).remove();
}

function CreateCategoryAndSubcategoriesChangeClassIcon(input, icon) {
    $(`#${icon}`).attr('class', input.value);
}

function CreateCategoryAndSubcategoriesWriteUrl(selectPermission, inputUrl) {
    if($(selectPermission).val() === '') {
        $(`#${inputUrl}`).val('');
    } else {
        $(`#${inputUrl}`).val(`/${$(selectPermission).find('option:selected').text().replace(/\./g, '/')}`);
    }
}

function CreateCategoryAndSubcategoriesQueryRoles(roles) {
    let rolesDiv = $('#roles_access_c');

    $.each(roles, function (i, role) {
        let roleDiv = $('<div>').addClass('row pl-2 icheck-primary');

        let roleCheckbox = $('<input>').attr({
            'id': role.name,
            'type': 'checkbox',
            'onclick': 'CreateCategoryAndSubcategoriesRoles(this)',
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

function CreateCategoryAndSubcategoriesRoles(checkbox) {
    // Obtener IDs de los checkboxes marcados en #roles_access_c
    let role = $(checkbox).attr('id');

    // Recorrer #subcategorys_c
    $('.subcategorys_c').each(function () {
        let subcategoryElement = $(this);
        let selectRole = subcategoryElement.find('select.role_c');

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
                let selectPermission = subcategoryElement.find('select.permission_c');
                subcategoryElement.find('input.url_c').val('')
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

function CreateCategoryAndSubcategoriesQueryPermissions(selectRoles, selectPermissions) {
    $.ajax({
        url: `/Dashboard/CategoriesAndSubcategories/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'role': $(selectRoles).val()
        },
        success: function(response) {
            if($(selectRoles).val() !== '') {
                CreateCategoryAndSubcategoriesPermissions(selectPermissions, response.data.permissions);
            }
        },
        error: function(xhr, textStatus, errorThrown) {
            CreateCategoryAndSubcategoriesAjaxError(xhr);
            $(`#${selectPermissions}`).empty().append(
                $('<option>', {
                    'value': '',
                    'text': 'Seleccione'
                })
            );
        }
    });
}

function CreateCategoryAndSubcategoriesPermissions(selectPermissions, permissions) {
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

function CreateCategoryAndSubcategoriesAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.info(response.message);
        $('#CreateCategoryAndSubcategoriesModal').modal('hide');
    }

    if(response.status === 201) {
        toastr.success(response.message);
        $('#CreateCategoryAndSubcategoriesModal').modal('hide');
    }
}

function CreateCategoryAndSubcategoriesAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error.message);
        $('#CreateCategoryAndSubcategoriesModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateCategoryAndSubcategoriesModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error.message);
        $('#CreateCategoryAndSubcategoriesModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassCreateCategoryAndSubcategories();
        RemoveIsInvalidClassCreateCategoryAndSubcategories();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassCreateCategoryAndSubcategories(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateCategoryAndSubcategories();
    }

    if(xhr.status === 500){
        if(xhr.responseJSON.error) {
            toastr.error(xhr.responseJSON.error.message);
        }

        if(xhr.responseJSON.message) {
            toastr.error(xhr.responseJSON.message);
        }
        $('#CreateCategoryAndSubcategoriesModal').modal('hide');
    }
}

function AddIsValidClassCreateCategoryAndSubcategories() {
    if (!$('#module_c').hasClass('is-invalid')) {
        $('#module_c').addClass('is-valid');
    }

    if (!$('#icon_c').hasClass('is-invalid')) {
        $('#icon_c').addClass('is-valid');
    }

    $('.subcategorys_c').find('div.subcategory_c').each(function(index) {
        if (!$(this).find('select.role_c').hasClass('is-invalid')) {
            $(this).find('select.role_c').addClass('is-valid');
        }
        if (!$(this).find('select.permission_c').hasClass('is-invalid')) {
            $(this).find('select.permission_c').addClass('is-valid');
        }
        if (!$(this).find('input.subicon_c').hasClass('is-invalid')) {
            $(this).find('input.subicon_c').addClass('is-valid');
        }
        if (!$(this).find('input.name_c').hasClass('is-invalid')) {
            $(this).find('input.name_c').addClass('is-valid');
        }
        if (!$(this).find('input.url_c').hasClass('is-invalid')) {
            $(this).find('input.url_c').addClass('is-valid');
        }
    });
}

function RemoveIsValidClassCreateCategoryAndSubcategories() {
    $('#module_c').removeClass('is-valid');
    $('#icon_c').removeClass('is-valid');

    $('.subcategorys_c').find('div.subcategory_c').each(function(index) {
        $(this).find('select.role_c').removeClass('is-valid');
        $(this).find('select.permission_c').removeClass('is-valid');
        $(this).find('input.subicon_c').removeClass('is-valid');
        $(this).find('input.name_c').removeClass('is-valid');
        $(this).find('input.url_c').removeClass('is-valid');
    });
}

function AddIsInvalidClassCreateCategoryAndSubcategories(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }

    $('.subcategorys_c').find('div.subcategory_c').each(function(index) {
        // Agrega la clase 'is-invalid'
        if(input === `subcategorys.${index}.permission_id`) {
            if (!$(this).find('select.permission_c').hasClass('is-valid')) {
                $(this).find('select.role_c').addClass('is-invalid');
                $(this).find('select.permission_c').addClass('is-invalid');
            }
        }
        if(input === `subcategorys.${index}.icon`) {
            if (!$(this).find('input.subicon_c').hasClass('is-valid')) {
                $(this).find('input.subicon_c').addClass('is-invalid');
            }
        }
        if(input === `subcategorys.${index}.subcategory`) {
            if (!$(this).find('input.name_c').hasClass('is-valid')) {
                $(this).find('input.name_c').addClass('is-invalid');
            }
        }
        if(input === `subcategorys.${index}.url`) {
            if (!$(this).find('input.url_c').hasClass('is-valid')) {
                $(this).find('input.url_c').addClass('is-invalid');
            }
        }
    });
}

function RemoveIsInvalidClassCreateCategoryAndSubcategories() {
    $('#module_c').removeClass('is-invalid');
    $('#icon_c').removeClass('is-invalid');

    $('.subcategorys_c').find('div.subcategory_c').each(function(index) {
        $(this).find('select.role_c').removeClass('is-invalid');
        $(this).find('select.permission_c').removeClass('is-invalid');
        $(this).find('input.subicon_c').removeClass('is-invalid');
        $(this).find('input.name_c').removeClass('is-invalid');
        $(this).find('input.url_c').removeClass('is-invalid');
    });
}
