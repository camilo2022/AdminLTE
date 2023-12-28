function CreatePersonModal(client_id) {
    $.ajax({
        url: `/Dashboard/Clients/People/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            tableClients.ajax.reload();
            CreatePersonModalCleaned();
            CreatePersonModalDocumentType(response.data.documentTypes);
            CreatePersonModalCountry(response.data.countries);
            CreatePersonAjaxSuccess(response);
            $('#CreatePersonButton').attr('data-client_id', client_id)
            $('#CreatePersonModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tableClients.ajax.reload();
            CreatePersonAjaxError(xhr);
        }
    });
}

function CreatePersonModalCleaned() {
    CreatePersonModalResetSelect('document_type_id_p_c');
    CreatePersonModalResetSelect('country_id_p_c');
    RemoveIsValidClassCreatePerson();
    RemoveIsInvalidClassCreatePerson();

    $('#name_p_c').val('');
    $('#last_name_p_c').val('');
    $('#document_number_p_c').val('');
    $('#address_p_c').val('');
    $('#neighborhood_p_c').val('');
    $('#email_p_c').val('');
    $('#telephone_number_first_p_c').val('');
    $('#telephone_number_second_p_c').val('');
}

function CreatePersonModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function CreatePersonModalDocumentType(documentTypes) {
    documentTypes.forEach(documentType => {
        $('#document_type_id_p_c').append(new Option(documentType.name, documentType.id, false, false));
    });
}

function CreatePersonModalCountry(countries) {
    countries.forEach(country => {
        $('#country_id_p_c').append(new Option(country.name, country.id, false, false));
    });
}

function CreatePersonModalCountryGetDepartament(select) {
    if($(select).val() == '') {
        CreatePersonModalResetSelect('departament_id_p_c');
    } else {
        $.ajax({
            url: `/Dashboard/Clients/People/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'country_id':  $(select).val()
            },
            success: function(response) {
                CreatePersonModalResetSelect('departament_id_p_c');
                CreatePersonModalDepartament(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreatePersonAjaxError(xhr);
            }
        });
    }
}

function CreatePersonModalDepartament(departaments) {
    departaments.forEach(departament => {
        $('#departament_id_p_c').append(new Option(departament.name, departament.id, false, false));
    });
}

function CreatePersonModalDepartamentGetCity(select) {
    if($(select).val() == '') {
        CreatePersonModalResetSelect('city_id_p_c');
    } else {
        $.ajax({
            url: `/Dashboard/Clients/People/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'departament_id':  $(select).val()
            },
            success: function(response) {
                CreatePersonModalResetSelect('city_id_p_c');
                CreatePersonModalCity(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreatePersonAjaxError(xhr);
            }
        });
    }
};

function CreatePersonModalCity(cities) {
    cities.forEach(city => {
        $('#city_id_p_c').append(new Option(city.name, city.id, false, false));
    });
}

function CreatePerson() {
    Swal.fire({
        title: '¿Desea guardar el representante legal?',
        text: 'El representante legal será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Clients/People/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'client_id': $('#CreatePersonButton').attr('data-client_id'),
                    'name': $('#name_p_c').val(),
                    'last_name': $('#last_name_p_c').val(),
                    'document_type_id': $('#document_type_id_p_c').val(),
                    'document_number': $('#document_number_p_c').val(),
                    'country_id': $('#country_id_p_c').val(),
                    'departament_id': $('#departament_id_p_c').val(),
                    'city_id': $('#city_id_p_c').val(),
                    'address': $('#address_p_c').val(),
                    'neighborhood': $('#neighborhood_p_c').val(),
                    'email': $('#email_p_c').val(),
                    'telephone_number_first': $('#telephone_number_first_p_c').val(),
                    'telephone_number_second': $('#telephone_number_second_p_c').val(),
                },
                success: function (response) {
                    tableClients.ajax.reload();
                    CreatePersonAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableClients.ajax.reload();
                    CreatePersonAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El representante legal no fue creado.')
        }
    });
}

function CreatePersonAjaxSuccess(response) {
    if (response.status === 200) {
        toastr.info(response.message);
        $('#CreatePersonModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreatePersonModal').modal('hide');
    }
}

function CreatePersonAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreatePersonModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreatePersonModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreatePersonModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreatePerson();
        RemoveIsInvalidClassCreatePerson();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreatePerson(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreatePerson();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreatePersonModal').modal('hide');
    }
}

function AddIsValidClassCreatePerson() {
    if (!$('#name_p_c').hasClass('is-invalid')) {
        $('#name_p_c').addClass('is-valid');
    }
    if (!$('#last_name_p_c').hasClass('is-invalid')) {
        $('#last_name_p_c').addClass('is-valid');
    }
    if (!$('#document_number_p_c').hasClass('is-invalid')) {
        $('#document_number_p_c').addClass('is-valid');
    }
    if (!$('#address_p_c').hasClass('is-invalid')) {
        $('#address_p_c').addClass('is-valid');
    }
    if (!$('#neighborhood_p_c').hasClass('is-invalid')) {
        $('#neighborhood_p_c').addClass('is-valid');
    }
    if (!$('#email_p_c').hasClass('is-invalid')) {
        $('#email_p_c').addClass('is-valid');
    }
    if (!$('#telephone_number_first_p_c').hasClass('is-invalid')) {
        $('#telephone_number_first_p_c').addClass('is-valid');
    }
    if (!$('#telephone_number_second_p_c').hasClass('is-invalid')) {
        $('#telephone_number_second_p_c').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-document_type_id_p_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-document_type_id_p_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-country_id_p_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-country_id_p_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-departament_id_p_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-departament_id_p_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-city_id_p_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-city_id_p_c-container"]').addClass('is-valid');
    }
}

function RemoveIsValidClassCreatePerson() {
    $('#name_p_c').removeClass('is-valid');
    $('#last_name_p_c').removeClass('is-valid');
    $('#document_number_p_c').removeClass('is-valid');
    $('#address_p_c').removeClass('is-valid');
    $('#neighborhood_p_c').removeClass('is-valid');
    $('#email_p_c').removeClass('is-valid');
    $('#telephone_number_first_p_c').removeClass('is-valid');
    $('#telephone_number_second_p_c').removeClass('is-valid');
    $('span[aria-labelledby="select2-document_type_id_p_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-country_id_p_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-departament_id_p_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-city_id_p_c-container"]').removeClass('is-valid');
}

function AddIsInvalidClassCreatePerson(input) {
    if (!$(`#${input}_p_c`).hasClass('is-valid')) {
        $(`#${input}_p_c`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_p_c-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_p_c-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreatePerson() {
    $('#name_p_c').removeClass('is-invalid');
    $('#last_name_p_c').removeClass('is-invalid');
    $('#document_number_p_c').removeClass('is-invalid');
    $('#address_p_c').removeClass('is-invalid');
    $('#neighborhood_p_c').removeClass('is-invalid');
    $('#email_p_c').removeClass('is-invalid');
    $('#telephone_number_first_p_c').removeClass('is-invalid');
    $('#telephone_number_second_p_c').removeClass('is-invalid');
    $('span[aria-labelledby="select2-document_type_id_p_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-country_id_p_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-departament_id_p_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-city_id_p_c-container"]').removeClass('is-invalid');
}
