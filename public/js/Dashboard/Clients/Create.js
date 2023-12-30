function CreateClientModal() {
    $.ajax({
        url: `/Dashboard/Clients/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            tableClients.ajax.reload();
            CreateClientModalCleaned();
            CreateClientModalClientType(response.data.clientTypes);
            CreateClientModalPersonType(response.data.personTypes);
            CreateClientModalCountry(response.data.countries);
            CreateClientAjaxSuccess(response);
            $('#CreateClientModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tableClients.ajax.reload();
            CreateClientAjaxError(xhr);
        }
    });
}

function CreateClientModalCleaned() {
    CreateClientModalResetSelect('person_type_id_c_c');
    CreateClientModalResetSelect('client_type_id_c_c');
    CreateClientModalResetSelect('country_id_c_c');
    RemoveIsValidClassCreateClient();
    RemoveIsInvalidClassCreateClient();

    $('#name_c_c').val('');
    $('#document_number_c_c').val('');
    $('#address_c_c').val('');
    $('#neighborhood_c_c').val('');
    $('#email_c_c').val('');
    $('#telephone_number_first_c_c').val('');
    $('#telephone_number_second_c_c').val('');
}

function CreateClientModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function CreateClientModalClientType(clientTypes) {
    clientTypes.forEach(clientType => {
        $('#client_type_id_c_c').append(new Option(clientType.name, clientType.id, false, false));
    });
}

function CreateClientModalPersonType(personTypes) {
    personTypes.forEach(personType => {
        $('#person_type_id_c_c').append(new Option(personType.name, personType.id, false, false));
    });
}

function CreateClientModalPersonTypeGetDocumentType(select) {
    if($(select).val() == '') {
        CreateClientModalResetSelect('document_type_id_c_c');
    } else {
        $.ajax({
            url: `/Dashboard/Clients/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'person_type_id':  $(select).val()
            },
            success: function(response) {
                CreateClientModalResetSelect('document_type_id_c_c');
                CreateClientModalDocumentType(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateClientAjaxError(xhr);
            }
        });
    }
};

function CreateClientModalDocumentType(documentTypes) {
    documentTypes.forEach(documentType => {
        $('#document_type_id_c_c').append(new Option(documentType.name, documentType.id, false, false));
    });
}

function CreateClientModalCountry(countries) {
    countries.forEach(country => {
        $('#country_id_c_c').append(new Option(country.name, country.id, false, false));
    });
}

function CreateClientModalCountryGetDepartament(select) {
    if($(select).val() == '') {
        CreateClientModalResetSelect('departament_id_c_c');
    } else {
        $.ajax({
            url: `/Dashboard/Clients/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'country_id':  $(select).val()
            },
            success: function(response) {
                CreateClientModalResetSelect('departament_id_c_c');
                CreateClientModalDepartament(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateClientAjaxError(xhr);
            }
        });
    }
};

function CreateClientModalDepartament(departaments) {
    departaments.forEach(departament => {
        $('#departament_id_c_c').append(new Option(departament.name, departament.id, false, false));
    });
}

function CreateClientModalDepartamentGetCity(select) {
    if($(select).val() == '') {
        CreateClientModalResetSelect('city_id_c_c');
    } else {
        $.ajax({
            url: `/Dashboard/Clients/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'departament_id':  $(select).val()
            },
            success: function(response) {
                CreateClientModalResetSelect('city_id_c_c');
                CreateClientModalCity(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateClientAjaxError(xhr);
            }
        });
    }
};

function CreateClientModalCity(cities) {
    cities.forEach(city => {
        $('#city_id_c_c').append(new Option(city.name, city.id, false, false));
    });
}

function CreateClient() {
    Swal.fire({
        title: '¿Desea guardar el cliente?',
        text: 'El cliente será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Clients/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c_c').val(),
                    'person_type_id': $('#person_type_id_c_c').val(),
                    'client_type_id': $('#client_type_id_c_c').val(),
                    'document_type_id': $('#document_type_id_c_c').val(),
                    'document_number': $('#document_number_c_c').val(),
                    'country_id': $('#country_id_c_c').val(),
                    'departament_id': $('#departament_id_c_c').val(),
                    'city_id': $('#city_id_c_c').val(),
                    'address': $('#address_c_c').val(),
                    'neighborhood': $('#neighborhood_c_c').val(),
                    'email': $('#email_c_c').val(),
                    'telephone_number_first': $('#telephone_number_first_c_c').val(),
                    'telephone_number_second': $('#telephone_number_second_c_c').val(),
                },
                success: function (response) {
                    tableClients.ajax.reload();
                    CreateClientAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableClients.ajax.reload();
                    CreateClientAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El cliente no fue creado.')
        }
    });
}

function CreateClientAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#CreateClientModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateClientModal').modal('hide');
    }
}

function CreateClientAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateClientModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateClientModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateClientModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateClient();
        RemoveIsInvalidClassCreateClient();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateClient(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateClient();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateClientModal').modal('hide');
    }
}

function AddIsValidClassCreateClient() {
    if (!$('#name_c_c').hasClass('is-invalid')) {
        $('#name_c_c').addClass('is-valid');
    }
    if (!$('#document_number_c_c').hasClass('is-invalid')) {
        $('#document_number_c_c').addClass('is-valid');
    }
    if (!$('#address_c_c').hasClass('is-invalid')) {
        $('#address_c_c').addClass('is-valid');
    }
    if (!$('#neighborhood_c_c').hasClass('is-invalid')) {
        $('#neighborhood_c_c').addClass('is-valid');
    }
    if (!$('#email_c_c').hasClass('is-invalid')) {
        $('#email_c_c').addClass('is-valid');
    }
    if (!$('#telephone_number_first_c_c').hasClass('is-invalid')) {
        $('#telephone_number_first_c_c').addClass('is-valid');
    }
    if (!$('#telephone_number_second_c_c').hasClass('is-invalid')) {
        $('#telephone_number_second_c_c').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-person_type_id_c_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-person_type_id_c_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-client_type_id_c_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-client_type_id_c_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-document_type_id_c_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-document_type_id_c_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-country_id_c_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-country_id_c_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-departament_id_c_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-departament_id_c_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-city_id_c_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-city_id_c_c-container"]').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateClient() {
    $('#name_c_c').removeClass('is-valid');
    $('#document_number_c_c').removeClass('is-valid');
    $('#address_c_c').removeClass('is-valid');
    $('#neighborhood_c_c').removeClass('is-valid');
    $('#email_c_c').removeClass('is-valid');
    $('#telephone_number_first_c_c').removeClass('is-valid');
    $('#telephone_number_second_c_c').removeClass('is-valid');
    $('span[aria-labelledby="select2-person_type_id_c_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-client_type_id_c_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-document_type_id_c_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-country_id_c_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-departament_id_c_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-city_id_c_c-container"]').removeClass('is-valid');
}

function AddIsInvalidClassCreateClient(input) {
    if (!$(`#${input}_c_c`).hasClass('is-valid')) {
        $(`#${input}_c_c`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_c_c-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_c_c-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateClient() {
    $('#name_c_c').removeClass('is-invalid');
    $('#document_number_c_c').removeClass('is-invalid');
    $('#address_c_c').removeClass('is-invalid');
    $('#neighborhood_c_c').removeClass('is-invalid');
    $('#email_c_c').removeClass('is-invalid');
    $('#telephone_number_first_c_c').removeClass('is-invalid');
    $('#telephone_number_second_c_c').removeClass('is-invalid');
    $('span[aria-labelledby="select2-person_type_id_c_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-client_type_id_c_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-document_type_id_c_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-country_id_c_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-departament_id_c_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-city_id_c_c-container"]').removeClass('is-invalid');
}
