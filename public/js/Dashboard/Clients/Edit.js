function EditClientModal(id) {
    $.ajax({
        url: `/Dashboard/Clients/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            tableClients.ajax.reload();
            EditClientModalCleaned(response.data.client);
            EditClientModalClientType(response.data.clientTypes);
            EditClientModalPersonType(response.data.personTypes);
            EditClientModalCountry(response.data.countries);
            EditClientAjaxSuccess(response);
            $('#EditClientModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tableClients.ajax.reload();
            EditClientAjaxError(xhr);
        }
    });
}

function EditClientModalCleaned(client) {
    EditClientModalResetSelect('person_type_id_c_e');
    EditClientModalResetSelect('client_type_id_c_e');
    EditClientModalResetSelect('country_id_c_e');
    RemoveIsValidClassEditClient();
    RemoveIsInvalidClassEditClient();

    $('#EditClientButton').attr('onclick', `EditClient(${client.id})`);
    $('#EditClientButton').attr('data-id', client.id);
    $('#EditClientButton').attr('data-person_type_id', client.person_type_id);
    $('#EditClientButton').attr('data-client_type_id', client.client_type_id);
    $('#EditClientButton').attr('data-document_type_id', client.document_type_id);
    $('#EditClientButton').attr('data-country_id', client.country_id);
    $('#EditClientButton').attr('data-departament_id', client.departament_id);
    $('#EditClientButton').attr('data-city_id', client.city_id);

    $('#name_c_e').val(client.name);
    $('#document_number_c_e').val(client.document_number);
    $('#address_c_e').val(client.address);
    $('#neighborhood_c_e').val(client.neighborhood);
    $('#email_c_e').val(client.email);
    $('#telephone_number_first_c_e').val(client.telephone_number_first);
    $('#telephone_number_second_c_e').val(client.telephone_number_second);
}

function EditClientModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function EditClientModalClientType(clientTypes) {
    clientTypes.forEach(clientType => {
        $('#client_type_id_c_e').append(new Option(clientType.name, clientType.id, false, false));
    });

    let client_type_id = $('#EditClientButton').attr('data-client_type_id');
    if(client_type_id != '') {
        $("#client_type_id_c_e").val(client_type_id).trigger('change');
        $('#EditClientButton').attr('data-client_type_id', '');
    }
}

function EditClientModalPersonType(personTypes) {
    personTypes.forEach(personType => {
        $('#person_type_id_c_e').append(new Option(personType.name, personType.id, false, false));
    });

    let person_type_id = $('#EditClientButton').attr('data-person_type_id');
    if(person_type_id != '') {
        $("#person_type_id_c_e").val(person_type_id).trigger('change');
        $('#EditClientButton').attr('data-person_type_id', '');
    }
}

function EditClientModalPersonTypeGetDocumentType(select) {
    if($(select).val() == '') {
        EditClientModalResetSelect('document_type_id_c_e');
    } else {
        let id = $('#EditClientButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Clients/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'person_type_id':  $(select).val()
            },
            success: function(response) {
                EditClientModalResetSelect('document_type_id_c_e');
                EditClientModalDocumentType(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditClientAjaxError(xhr);
            }
        });
    }
};

function EditClientModalDocumentType(documentTypes) {
    documentTypes.forEach(documentType => {
        $('#document_type_id_c_e').append(new Option(documentType.name, documentType.id, false, false));
    });

    let document_type_id = $('#EditClientButton').attr('data-document_type_id');
    if(document_type_id != '') {
        $("#document_type_id_c_e").val(document_type_id).trigger('change');
        $('#EditClientButton').attr('data-document_type_id', '');
    }
}

function EditClientModalCountry(countries) {
    countries.forEach(country => {
        $('#country_id_c_e').append(new Option(country.name, country.id, false, false));
    });

    let country_id = $('#EditClientButton').attr('data-country_id');
    if(country_id != '') {
        $("#country_id_c_e").val(country_id).trigger('change');
        $('#EditClientButton').attr('data-country_id', '');
    }
}

function EditClientModalCountryGetDepartament(select) {
    if($(select).val() == '') {
        EditClientModalResetSelect('departament_id_c_e');
    } else {
        let id = $('#EditClientButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Clients/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'country_id':  $(select).val()
            },
            success: function(response) {
                EditClientModalResetSelect('departament_id_c_e');
                EditClientModalDepartament(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditClientAjaxError(xhr);
            }
        });
    }
};

function EditClientModalDepartament(departaments) {
    departaments.forEach(departament => {
        $('#departament_id_c_e').append(new Option(departament.name, departament.id, false, false));
    });

    let departament_id = $('#EditClientButton').attr('data-departament_id');
    if(departament_id != '') {
        $("#departament_id_c_e").val(departament_id).trigger('change');
        $('#EditClientButton').attr('data-departament_id', '');
    }
}

function EditClientModalDepartamentGetCity(select) {
    if($(select).val() == '') {
        EditClientModalResetSelect('city_id_c_e');
    } else {
        let id = $('#EditClientButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Clients/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'departament_id':  $(select).val()
            },
            success: function(response) {
                EditClientModalResetSelect('city_id_c_e');
                EditClientModalCity(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditClientAjaxError(xhr);
            }
        });
    }
};

function EditClientModalCity(cities) {
    cities.forEach(city => {
        $('#city_id_c_e').append(new Option(city.name, city.id, false, false));
    });

    let city_id = $('#EditClientButton').attr('data-city_id');
    if(city_id != '') {
        $("#city_id_c_e").val(city_id).trigger('change');
        $('#EditClientButton').attr('data-city_id', '');
    }
}

function EditClient(id) {
    Swal.fire({
        title: '¿Desea actualizar el cliente?',
        text: 'El cliente se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Clients/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c_e').val(),
                    'person_type_id': $('#person_type_id_c_e').val(),
                    'client_type_id': $('#client_type_id_c_e').val(),
                    'document_type_id': $('#document_type_id_c_e').val(),
                    'document_number': $('#document_number_c_e').val(),
                    'country_id': $('#country_id_c_e').val(),
                    'departament_id': $('#departament_id_c_e').val(),
                    'city_id': $('#city_id_c_e').val(),
                    'address': $('#address_c_e').val(),
                    'neighborhood': $('#neighborhood_c_e').val(),
                    'email': $('#email_c_e').val(),
                    'telephone_number_first': $('#telephone_number_first_c_e').val(),
                    'telephone_number_second': $('#telephone_number_second_c_e').val(),
                },
                success: function (response) {
                    tableClients.ajax.reload();
                    EditClientAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableClients.ajax.reload();
                    EditClientAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El cliente no fue actualizada.')
        }
    });
}

function EditClientAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#EditClientModal').modal('hide');
    }

    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditClientModal').modal('hide');
    }
}

function EditClientAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditClientModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditClientModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditClientModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditClient();
        RemoveIsInvalidClassEditClient();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditClient(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditClient();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditClientModal').modal('hide');
    }
}

function AddIsValidClassEditClient() {
    if (!$('#name_c_e').hasClass('is-invalid')) {
        $('#name_c_e').addClass('is-valid');
    }
    if (!$('#document_number_c_e').hasClass('is-invalid')) {
        $('#document_number_c_e').addClass('is-valid');
    }
    if (!$('#address_c_e').hasClass('is-invalid')) {
        $('#address_c_e').addClass('is-valid');
    }
    if (!$('#neighborhood_c_e').hasClass('is-invalid')) {
        $('#neighborhood_c_e').addClass('is-valid');
    }
    if (!$('#email_c_e').hasClass('is-invalid')) {
        $('#email_c_e').addClass('is-valid');
    }
    if (!$('#telephone_number_first_c_e').hasClass('is-invalid')) {
        $('#telephone_number_first_c_e').addClass('is-valid');
    }
    if (!$('#telephone_number_second_c_e').hasClass('is-invalid')) {
        $('#telephone_number_second_c_e').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-person_type_id_c_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-person_type_id_c_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-client_type_id_c_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-client_type_id_c_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-document_type_id_c_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-document_type_id_c_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-country_id_c_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-country_id_c_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-departament_id_c_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-departament_id_c_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-city_id_c_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-city_id_c_e-container"]').addClass('is-valid');
    }
}

function RemoveIsValidClassEditClient() {
    $('#name_c_e').removeClass('is-valid');
    $('#document_number_c_e').removeClass('is-valid');
    $('#address_c_e').removeClass('is-valid');
    $('#neighborhood_c_e').removeClass('is-valid');
    $('#email_c_e').removeClass('is-valid');
    $('#telephone_number_first_c_e').removeClass('is-valid');
    $('#telephone_number_second_c_e').removeClass('is-valid');
    $('span[aria-labelledby="select2-person_type_id_c_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-client_type_id_c_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-document_type_id_c_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-country_id_c_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-departament_id_c_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-city_id_c_e-container"]').removeClass('is-valid');
}

function AddIsInvalidClassEditClient(input) {
    if (!$(`#${input}_c_e`).hasClass('is-valid')) {
        $(`#${input}_c_e`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_c_e-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_c_e-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditClient() {
    $('#name_c_e').removeClass('is-invalid');
    $('#document_number_c_e').removeClass('is-invalid');
    $('#address_c_e').removeClass('is-invalid');
    $('#neighborhood_c_e').removeClass('is-invalid');
    $('#email_c_e').removeClass('is-invalid');
    $('#telephone_number_first_c_e').removeClass('is-invalid');
    $('#telephone_number_second_c_e').removeClass('is-invalid');
    $('span[aria-labelledby="select2-person_type_id_c_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-client_type_id_c_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-document_type_id_c_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-country_id_c_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-departament_id_c_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-city_id_c_e-container"]').removeClass('is-invalid');
}
