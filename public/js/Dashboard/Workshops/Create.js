function CreateWorkshopModal() {
    $.ajax({
        url: `/Dashboard/Workshops/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            CreateWorkshopModalCleaned();
            CreateWorkshopModalPersonType(response.data.personTypes);
            CreateWorkshopModalCountry(response.data.countries);
            CreateWorkshopAjaxSuccess(response);
            $('#CreateWorkshopModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            CreateWorkshopAjaxError(xhr);
        }
    });
}

function CreateWorkshopModalCleaned() {
    CreateWorkshopModalResetSelect('person_type_id_c');
    CreateWorkshopModalResetSelect('client_type_id_c');
    CreateWorkshopModalResetSelect('country_id_c');
    RemoveIsValidClassCreateWorkshop();
    RemoveIsInvalidClassCreateWorkshop();

    $('#name_c').val('');
    $('#document_number_c').val('');
    $('#address_c').val('');
    $('#neighborhood_c').val('');
    $('#email_c').val('');
    $('#telephone_number_first_c').val('');
    $('#telephone_number_second_c').val('');
}

function CreateWorkshopModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function CreateWorkshopModalPersonType(personTypes) {
    personTypes.forEach(personType => {
        $('#person_type_id_c').append(new Option(personType.name, personType.id, false, false));
    });
}

function CreateWorkshopModalPersonTypeGetDocumentType(select) {
    if($(select).val() == '') {
        CreateWorkshopModalResetSelect('document_type_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Workshops/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'person_type_id':  $(select).val()
            },
            success: function(response) {
                CreateWorkshopModalResetSelect('document_type_id_c');
                CreateWorkshopModalDocumentType(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateWorkshopAjaxError(xhr);
            }
        });
    }
};

function CreateWorkshopModalDocumentType(documentTypes) {
    documentTypes.forEach(documentType => {
        $('#document_type_id_c').append(new Option(documentType.name, documentType.id, false, false));
    });
}

function CreateWorkshopModalCountry(countries) {
    countries.forEach(country => {
        $('#country_id_c').append(new Option(country.name, country.id, false, false));
    });
}

function CreateWorkshopModalCountryGetDepartament(select) {
    if($(select).val() == '') {
        CreateWorkshopModalResetSelect('departament_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Workshops/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'country_id':  $(select).val()
            },
            success: function(response) {
                CreateWorkshopModalResetSelect('departament_id_c');
                CreateWorkshopModalDepartament(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateWorkshopAjaxError(xhr);
            }
        });
    }
};

function CreateWorkshopModalDepartament(departaments) {
    departaments.forEach(departament => {
        $('#departament_id_c').append(new Option(departament.name, departament.id, false, false));
    });
}

function CreateWorkshopModalDepartamentGetCity(select) {
    if($(select).val() == '') {
        CreateWorkshopModalResetSelect('city_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Workshops/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'departament_id':  $(select).val()
            },
            success: function(response) {
                CreateWorkshopModalResetSelect('city_id_c');
                CreateWorkshopModalCity(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateWorkshopAjaxError(xhr);
            }
        });
    }
};

function CreateWorkshopModalCity(cities) {
    cities.forEach(city => {
        $('#city_id_c').append(new Option(city.name, city.id, false, false));
    });
}

function CreateWorkshop() {
    Swal.fire({
        title: '¿Desea guardar el taller?',
        text: 'El taller será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Workshops/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                    'person_type_id': $('#person_type_id_c').val(),
                    'document_type_id': $('#document_type_id_c').val(),
                    'document_number': $('#document_number_c').val(),
                    'country_id': $('#country_id_c').val(),
                    'departament_id': $('#departament_id_c').val(),
                    'city_id': $('#city_id_c').val(),
                    'address': $('#address_c').val(),
                    'neighborhood': $('#neighborhood_c').val(),
                    'email': $('#email_c').val(),
                    'telephone_number_first': $('#telephone_number_first_c').val(),
                    'telephone_number_second': $('#telephone_number_second_c').val(),
                },
                success: function (response) {
                    tableWorkshops.ajax.reload();
                    CreateWorkshopAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    CreateWorkshopAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El taller no fue creado.')
        }
    });
}

function CreateWorkshopAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#CreateWorkshopModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateWorkshopModal').modal('hide');
    }
}

function CreateWorkshopAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateWorkshopModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateWorkshopModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateWorkshopModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateWorkshop();
        RemoveIsInvalidClassCreateWorkshop();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateWorkshop(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateWorkshop();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateWorkshopModal').modal('hide');
    }
}

function AddIsValidClassCreateWorkshop() {
    if (!$('#name_c').hasClass('is-invalid')) {
        $('#name_c').addClass('is-valid');
    }
    if (!$('#document_number_c').hasClass('is-invalid')) {
        $('#document_number_c').addClass('is-valid');
    }
    if (!$('#address_c').hasClass('is-invalid')) {
        $('#address_c').addClass('is-valid');
    }
    if (!$('#neighborhood_c').hasClass('is-invalid')) {
        $('#neighborhood_c').addClass('is-valid');
    }
    if (!$('#email_c').hasClass('is-invalid')) {
        $('#email_c').addClass('is-valid');
    }
    if (!$('#telephone_number_first_c').hasClass('is-invalid')) {
        $('#telephone_number_first_c').addClass('is-valid');
    }
    if (!$('#telephone_number_second_c').hasClass('is-invalid')) {
        $('#telephone_number_second_c').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-person_type_id_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-person_type_id_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-document_type_id_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-document_type_id_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-country_id_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-country_id_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-departament_id_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-departament_id_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-city_id_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-city_id_c-container"]').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateWorkshop() {
    $('#name_c').removeClass('is-valid');
    $('#document_number_c').removeClass('is-valid');
    $('#address_c').removeClass('is-valid');
    $('#neighborhood_c').removeClass('is-valid');
    $('#email_c').removeClass('is-valid');
    $('#telephone_number_first_c').removeClass('is-valid');
    $('#telephone_number_second_c').removeClass('is-valid');
    $('span[aria-labelledby="select2-person_type_id_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-document_type_id_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-country_id_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-departament_id_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-city_id_c-container"]').removeClass('is-valid');
}

function AddIsInvalidClassCreateWorkshop(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_c-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_c-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateWorkshop() {
    $('#name_c').removeClass('is-invalid');
    $('#document_number_c').removeClass('is-invalid');
    $('#address_c').removeClass('is-invalid');
    $('#neighborhood_c').removeClass('is-invalid');
    $('#email_c').removeClass('is-invalid');
    $('#telephone_number_first_c').removeClass('is-invalid');
    $('#telephone_number_second_c').removeClass('is-invalid');
    $('span[aria-labelledby="select2-person_type_id_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-document_type_id_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-country_id_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-departament_id_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-city_id_c-container"]').removeClass('is-invalid');
}
