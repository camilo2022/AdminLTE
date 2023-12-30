function EditPersonModal(id, client_id) {
    $.ajax({
        url: `/Dashboard/Clients/People/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            tableClients.ajax.reload();
            EditPersonModalCleaned(response.data.person);
            EditPersonModalDocumentType(response.data.documentTypes);
            EditPersonModalCountry(response.data.countries);
            EditPersonAjaxSuccess(response);
            $('#EditPersonButton').attr('data-client_id', client_id)
            $('#EditPersonModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            tableClients.ajax.reload();
            EditPersonAjaxError(xhr);
        }
    });
}

function EditPersonModalCleaned(person) {
    EditPersonModalResetSelect('document_type_id_p_e');
    EditPersonModalResetSelect('country_id_p_e');
    RemoveIsValidClassEditPerson();
    RemoveIsInvalidClassEditPerson();

    $('#EditPersonButton').attr('onclick', `EditPerson(${person.id})`);
    $('#EditPersonButton').attr('data-id', person.id);
    $('#EditPersonButton').attr('data-document_type_id', person.document_type_id);
    $('#EditPersonButton').attr('data-country_id', person.country_id);
    $('#EditPersonButton').attr('data-departament_id', person.departament_id);
    $('#EditPersonButton').attr('data-city_id', person.city_id);

    $('#name_p_e').val(person.name);
    $('#last_name_p_e').val(person.last_name);
    $('#document_number_p_e').val(person.document_number);
    $('#address_p_e').val(person.address);
    $('#neighborhood_p_e').val(person.neighborhood);
    $('#email_p_e').val(person.email);
    $('#telephone_number_first_p_e').val(person.telephone_number_first);
    $('#telephone_number_second_p_e').val(person.telephone_number_second);
}

function EditPersonModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function EditPersonModalDocumentType(documentTypes) {
    documentTypes.forEach(documentType => {
        $('#document_type_id_p_e').append(new Option(documentType.name, documentType.id, false, false));
    });

    let document_type_id = $('#EditPersonButton').attr('data-document_type_id');
    if(document_type_id != '') {
        $("#document_type_id_p_e").val(document_type_id).trigger('change');
        $('#EditPersonButton').attr('data-document_type_id', '');
    }
}

function EditPersonModalCountry(countries) {
    countries.forEach(country => {
        $('#country_id_p_e').append(new Option(country.name, country.id, false, false));
    });

    let country_id = $('#EditPersonButton').attr('data-country_id');
    if(country_id != '') {
        $("#country_id_p_e").val(country_id).trigger('change');
        $('#EditPersonButton').attr('data-country_id', '');
    }
}

function EditPersonModalCountryGetDepartament(select) {
    if($(select).val() == '') {
        EditPersonModalResetSelect('departament_id_p_e');
    } else {
        let id = $('#EditPersonButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Clients/People/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'country_id':  $(select).val()
            },
            success: function(response) {
                EditPersonModalResetSelect('departament_id_p_e');
                EditPersonModalDepartament(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditPersonAjaxError(xhr);
            }
        });
    }
}

function EditPersonModalDepartament(departaments) {
    departaments.forEach(departament => {
        $('#departament_id_p_e').append(new Option(departament.name, departament.id, false, false));
    });

    let departament_id = $('#EditPersonButton').attr('data-departament_id');
    if(departament_id != '') {
        $("#departament_id_p_e").val(departament_id).trigger('change');
        $('#EditPersonButton').attr('data-departament_id', '');
    }
}

function EditPersonModalDepartamentGetCity(select) {
    if($(select).val() == '') {
        EditPersonModalResetSelect('city_id_p_e');
    } else {
        let id = $('#EditPersonButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Clients/People/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'departament_id':  $(select).val()
            },
            success: function(response) {
                EditPersonModalResetSelect('city_id_p_e');
                EditPersonModalCity(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditPersonAjaxError(xhr);
            }
        });
    }
};

function EditPersonModalCity(cities) {
    cities.forEach(city => {
        $('#city_id_p_e').append(new Option(city.name, city.id, false, false));
    });

    let city_id = $('#EditPersonButton').attr('data-city_id');
    if(city_id != '') {
        $("#city_id_p_e").val(city_id).trigger('change');
        $('#EditPersonButton').attr('data-city_id', '');
    }
}

function EditPerson(id) {
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
                url: `/Dashboard/Clients/People/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'client_id': $('#EditPersonButton').attr('data-client_id'),
                    'name': $('#name_p_e').val(),
                    'last_name': $('#last_name_p_e').val(),
                    'document_type_id': $('#document_type_id_p_e').val(),
                    'document_number': $('#document_number_p_e').val(),
                    'country_id': $('#country_id_p_e').val(),
                    'departament_id': $('#departament_id_p_e').val(),
                    'city_id': $('#city_id_p_e').val(),
                    'address': $('#address_p_e').val(),
                    'neighborhood': $('#neighborhood_p_e').val(),
                    'email': $('#email_p_e').val(),
                    'telephone_number_first': $('#telephone_number_first_p_e').val(),
                    'telephone_number_second': $('#telephone_number_second_p_e').val(),
                },
                success: function (response) {
                    tableClients.ajax.reload();
                    EditPersonAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    tableClients.ajax.reload();
                    EditPersonAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El cliente no fue actualizada.')
        }
    });
}

function EditPersonAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#EditPersonModal').modal('hide');
    }

    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditPersonModal').modal('hide');
    }
}

function EditPersonAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditPersonModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditPersonModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditPersonModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditPerson();
        RemoveIsInvalidClassEditPerson();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditPerson(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditPerson();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditPersonModal').modal('hide');
    }
}

function AddIsValidClassEditPerson() {
    if (!$('#name_p_e').hasClass('is-invalid')) {
        $('#name_p_e').addClass('is-valid');
    }
    if (!$('#last_name_p_e').hasClass('is-invalid')) {
        $('#last_name_p_e').addClass('is-valid');
    }
    if (!$('#document_number_p_e').hasClass('is-invalid')) {
        $('#document_number_p_e').addClass('is-valid');
    }
    if (!$('#address_p_e').hasClass('is-invalid')) {
        $('#address_p_e').addClass('is-valid');
    }
    if (!$('#neighborhood_p_e').hasClass('is-invalid')) {
        $('#neighborhood_p_e').addClass('is-valid');
    }
    if (!$('#email_p_e').hasClass('is-invalid')) {
        $('#email_p_e').addClass('is-valid');
    }
    if (!$('#telephone_number_first_p_e').hasClass('is-invalid')) {
        $('#telephone_number_first_p_e').addClass('is-valid');
    }
    if (!$('#telephone_number_second_p_e').hasClass('is-invalid')) {
        $('#telephone_number_second_p_e').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-person_type_id_p_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-person_type_id_p_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-client_type_id_p_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-client_type_id_p_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-document_type_id_p_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-document_type_id_p_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-country_id_p_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-country_id_p_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-departament_id_p_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-departament_id_p_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-city_id_p_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-city_id_p_e-container"]').addClass('is-valid');
    }
}

function RemoveIsValidClassEditPerson() {
    $('#name_p_e').removeClass('is-valid');
    $('#last_name_p_e').removeClass('is-valid');
    $('#document_number_p_e').removeClass('is-valid');
    $('#address_p_e').removeClass('is-valid');
    $('#neighborhood_p_e').removeClass('is-valid');
    $('#email_p_e').removeClass('is-valid');
    $('#telephone_number_first_p_e').removeClass('is-valid');
    $('#telephone_number_second_p_e').removeClass('is-valid');
    $('span[aria-labelledby="select2-person_type_id_p_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-client_type_id_p_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-document_type_id_p_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-country_id_p_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-departament_id_p_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-city_id_p_e-container"]').removeClass('is-valid');
}

function AddIsInvalidClassEditPerson(input) {
    if (!$(`#${input}_p_e`).hasClass('is-valid')) {
        $(`#${input}_p_e`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_p_e-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_p_e-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditPerson() {
    $('#name_p_e').removeClass('is-invalid');
    $('#last_name_p_e').removeClass('is-invalid');
    $('#document_number_p_e').removeClass('is-invalid');
    $('#address_p_e').removeClass('is-invalid');
    $('#neighborhood_p_e').removeClass('is-invalid');
    $('#email_p_e').removeClass('is-invalid');
    $('#telephone_number_first_p_e').removeClass('is-invalid');
    $('#telephone_number_second_p_e').removeClass('is-invalid');
    $('span[aria-labelledby="select2-person_type_id_p_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-client_type_id_p_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-document_type_id_p_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-country_id_p_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-departament_id_p_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-city_id_p_e-container"]').removeClass('is-invalid');
}
