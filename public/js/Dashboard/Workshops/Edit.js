function EditWorkshopModal(id) {
    $.ajax({
        url: `/Dashboard/Workshops/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            EditWorkshopModalCleaned(response.data.workshop);
            EditWorkshopModalPersonType(response.data.personTypes);
            EditWorkshopModalCountry(response.data.countries);
            EditWorkshopAjaxSuccess(response);
            $('#EditWorkshopModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            EditWorkshopAjaxError(xhr);
        }
    });
}

function EditWorkshopModalCleaned(workshop) {
    EditWorkshopModalResetSelect('person_type_id_e');
    EditWorkshopModalResetSelect('country_id_e');
    RemoveIsValidClassEditWorkshop();
    RemoveIsInvalidClassEditWorkshop();

    $('#EditWorkshopButton').attr('onclick', `EditWorkshop(${workshop.id})`);
    $('#EditWorkshopButton').attr('data-id', workshop.id);
    $('#EditWorkshopButton').attr('data-person_type_id', workshop.person_type_id);
    $('#EditWorkshopButton').attr('data-document_type_id', workshop.document_type_id);
    $('#EditWorkshopButton').attr('data-country_id', workshop.country_id);
    $('#EditWorkshopButton').attr('data-departament_id', workshop.departament_id);
    $('#EditWorkshopButton').attr('data-city_id', workshop.city_id);

    $('#name_e').val(workshop.name);
    $('#document_number_e').val(workshop.document_number);
    $('#address_e').val(workshop.address);
    $('#neighborhood_e').val(workshop.neighborhood);
    $('#email_e').val(workshop.email);
    $('#telephone_number_first_e').val(workshop.telephone_number_first);
    $('#telephone_number_second_e').val(workshop.telephone_number_second);
}

function EditWorkshopModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function EditWorkshopModalPersonType(personTypes) {
    personTypes.forEach(personType => {
        $('#person_type_id_e').append(new Option(personType.name, personType.id, false, false));
    });

    let person_type_id = $('#EditWorkshopButton').attr('data-person_type_id');
    if(person_type_id != '') {
        $("#person_type_id_e").val(person_type_id).trigger('change');
        $('#EditWorkshopButton').attr('data-person_type_id', '');
    }
}

function EditWorkshopModalPersonTypeGetDocumentType(select) {
    if($(select).val() == '') {
        EditWorkshopModalResetSelect('document_type_id_e');
    } else {
        let id = $('#EditWorkshopButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Workshops/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'person_type_id':  $(select).val()
            },
            success: function(response) {
                EditWorkshopModalResetSelect('document_type_id_e');
                EditWorkshopModalDocumentType(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditWorkshopAjaxError(xhr);
            }
        });
    }
};

function EditWorkshopModalDocumentType(documentTypes) {
    documentTypes.forEach(documentType => {
        $('#document_type_id_e').append(new Option(documentType.name, documentType.id, false, false));
    });

    let document_type_id = $('#EditWorkshopButton').attr('data-document_type_id');
    if(document_type_id != '') {
        $("#document_type_id_e").val(document_type_id).trigger('change');
        $('#EditWorkshopButton').attr('data-document_type_id', '');
    }
}

function EditWorkshopModalCountry(countries) {
    countries.forEach(country => {
        $('#country_id_e').append(new Option(country.name, country.id, false, false));
    });

    let country_id = $('#EditWorkshopButton').attr('data-country_id');
    if(country_id != '') {
        $("#country_id_e").val(country_id).trigger('change');
        $('#EditWorkshopButton').attr('data-country_id', '');
    }
}

function EditWorkshopModalCountryGetDepartament(select) {
    if($(select).val() == '') {
        EditWorkshopModalResetSelect('departament_id_e');
    } else {
        let id = $('#EditWorkshopButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Workshops/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'country_id':  $(select).val()
            },
            success: function(response) {
                EditWorkshopModalResetSelect('departament_id_e');
                EditWorkshopModalDepartament(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditWorkshopAjaxError(xhr);
            }
        });
    }
};

function EditWorkshopModalDepartament(departaments) {
    departaments.forEach(departament => {
        $('#departament_id_e').append(new Option(departament.name, departament.id, false, false));
    });

    let departament_id = $('#EditWorkshopButton').attr('data-departament_id');
    if(departament_id != '') {
        $("#departament_id_e").val(departament_id).trigger('change');
        $('#EditWorkshopButton').attr('data-departament_id', '');
    }
}

function EditWorkshopModalDepartamentGetCity(select) {
    if($(select).val() == '') {
        EditWorkshopModalResetSelect('city_id_e');
    } else {
        let id = $('#EditWorkshopButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Workshops/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'departament_id':  $(select).val()
            },
            success: function(response) {
                EditWorkshopModalResetSelect('city_id_e');
                EditWorkshopModalCity(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditWorkshopAjaxError(xhr);
            }
        });
    }
};

function EditWorkshopModalCity(cities) {
    cities.forEach(city => {
        $('#city_id_e').append(new Option(city.name, city.id, false, false));
    });

    let city_id = $('#EditWorkshopButton').attr('data-city_id');
    if(city_id != '') {
        $("#city_id_e").val(city_id).trigger('change');
        $('#EditWorkshopButton').attr('data-city_id', '');
    }
}

function EditWorkshop(id) {
    Swal.fire({
        title: '¿Desea actualizar el taller?',
        text: 'El taller se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Workshops/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_e').val(),
                    'person_type_id': $('#person_type_id_e').val(),
                    'document_type_id': $('#document_type_id_e').val(),
                    'document_number': $('#document_number_e').val(),
                    'country_id': $('#country_id_e').val(),
                    'departament_id': $('#departament_id_e').val(),
                    'city_id': $('#city_id_e').val(),
                    'address': $('#address_e').val(),
                    'neighborhood': $('#neighborhood_e').val(),
                    'email': $('#email_e').val(),
                    'telephone_number_first': $('#telephone_number_first_e').val(),
                    'telephone_number_second': $('#telephone_number_second_e').val(),
                },
                success: function (response) {
                    tableWorkshops.ajax.reload();
                    EditWorkshopAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    EditWorkshopAjaxError(xhr);
                }
            });
        } else {
            toastr.info('El taller no fue actualizada.')
        }
    });
}

function EditWorkshopAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#EditWorkshopModal').modal('hide');
    }

    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditWorkshopModal').modal('hide');
    }
}

function EditWorkshopAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditWorkshopModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditWorkshopModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditWorkshopModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditWorkshop();
        RemoveIsInvalidClassEditWorkshop();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditWorkshop(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditWorkshop();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditWorkshopModal').modal('hide');
    }
}

function AddIsValidClassEditWorkshop() {
    if (!$('#name_e').hasClass('is-invalid')) {
        $('#name_e').addClass('is-valid');
    }
    if (!$('#document_number_e').hasClass('is-invalid')) {
        $('#document_number_e').addClass('is-valid');
    }
    if (!$('#address_e').hasClass('is-invalid')) {
        $('#address_e').addClass('is-valid');
    }
    if (!$('#neighborhood_e').hasClass('is-invalid')) {
        $('#neighborhood_e').addClass('is-valid');
    }
    if (!$('#email_e').hasClass('is-invalid')) {
        $('#email_e').addClass('is-valid');
    }
    if (!$('#telephone_number_first_e').hasClass('is-invalid')) {
        $('#telephone_number_first_e').addClass('is-valid');
    }
    if (!$('#telephone_number_second_e').hasClass('is-invalid')) {
        $('#telephone_number_second_e').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-person_type_id_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-person_type_id_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-document_type_id_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-document_type_id_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-country_id_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-country_id_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-departament_id_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-departament_id_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-city_id_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-city_id_e-container"]').addClass('is-valid');
    }
}

function RemoveIsValidClassEditWorkshop() {
    $('#name_e').removeClass('is-valid');
    $('#document_number_e').removeClass('is-valid');
    $('#address_e').removeClass('is-valid');
    $('#neighborhood_e').removeClass('is-valid');
    $('#email_e').removeClass('is-valid');
    $('#telephone_number_first_e').removeClass('is-valid');
    $('#telephone_number_second_e').removeClass('is-valid');
    $('span[aria-labelledby="select2-person_type_id_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-document_type_id_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-country_id_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-departament_id_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-city_id_e-container"]').removeClass('is-valid');
}

function AddIsInvalidClassEditWorkshop(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_e-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_e-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditWorkshop() {
    $('#name_e').removeClass('is-invalid');
    $('#document_number_e').removeClass('is-invalid');
    $('#address_e').removeClass('is-invalid');
    $('#neighborhood_e').removeClass('is-invalid');
    $('#email_e').removeClass('is-invalid');
    $('#telephone_number_first_e').removeClass('is-invalid');
    $('#telephone_number_second_e').removeClass('is-invalid');
    $('span[aria-labelledby="select2-person_type_id_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-document_type_id_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-country_id_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-departament_id_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-city_id_e-container"]').removeClass('is-invalid');
}
