function EditBusinessModal(id) {
    $.ajax({
        url: `/Dashboard/Businesses/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            EditBusinessModalCleaned(response.data.business);
            EditBusinessModalCountry(response.data.countries);
            EditBusinessModalPersonType(response.data.personTypes);
            EditBusinessAjaxSuccess(response);
            $('#EditBusinessModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            EditBusinessAjaxError(xhr);
        }
    });
}

function EditBusinessModalCleaned(business) {
    EditBusinessModalResetSelect('country_id_e');
    EditBusinessModalResetSelect('person_type_id_e');
    RemoveIsValidClassEditBusiness();
    RemoveIsInvalidClassEditBusiness();

    $('#EditBusinessButton').attr('onclick', `EditBusiness(${business.id})`);
    $('#EditBusinessButton').attr('data-id', business.id);
    $('#EditBusinessButton').attr('data-person_type_id', business.person_type_id);
    $('#EditBusinessButton').attr('data-document_type_id', business.document_type_id);
    $('#EditBusinessButton').attr('data-country_id', business.country_id);
    $('#EditBusinessButton').attr('data-departament_id', business.departament_id);
    $('#EditBusinessButton').attr('data-city_id', business.city_id);

    $('#name_e').val(business.name);
    $('#document_number_e').val(business.document_number);
    $('#telephone_number_e').val(business.telephone_number);
    $('#email_e').val(business.email);
    $('#description_e').val(business.description);
    $('#address_e').val(business.address);
    $('#neighborhood_e').val(business.neighborhood);
}

function EditBusinessModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function EditBusinessModalPersonType(personTypes) {
    personTypes.forEach(personType => {
        $('#person_type_id_e').append(new Option(personType.name, personType.id, false, false));
    });
    let person_type_id = $('#EditBusinessButton').attr('data-person_type_id');
    if(person_type_id != '') {
        $("#person_type_id_e").val(person_type_id).trigger('change');
        $('#EditBusinessButton').attr('data-person_type_id', '');
    }
}

function EditBusinessModalPersonTypeGetDocumentType(select) {
    if($(select).val() == '') {
        EditBusinessModalResetSelect('document_type_id_e');
    } else {
        let id = $('#EditBusinessButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Businesses/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'person_type_id':  $(select).val()
            },
            success: function(response) {
                EditBusinessModalResetSelect('document_type_id_e');
                EditBusinessModalDocumentType(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditBusinessAjaxError(xhr);
            }
        });
    }
}

function EditBusinessModalDocumentType(documentTypes) {
    documentTypes.forEach(documentType => {
        $('#document_type_id_e').append(new Option(documentType.name, documentType.id, false, false));
    });
    let document_type_id = $('#EditBusinessButton').attr('data-document_type_id');
    if(document_type_id != '') {
        $("#document_type_id_e").val(document_type_id).trigger('change');
        $('#EditBusinessButton').attr('data-document_type_id', '');
    }
}

function EditBusinessModalCountry(countries) {
    countries.forEach(country => {
        $('#country_id_e').append(new Option(country.name, country.id, false, false));
    });
    let country_id = $('#EditBusinessButton').attr('data-country_id');
    if(country_id != '') {
        $("#country_id_e").val(country_id).trigger('change');
        $('#EditBusinessButton').attr('data-country_id', '');
    }
}

function EditBusinessModalCountryGetDepartament(select) {
    if($(select).val() == '') {
        EditBusinessModalResetSelect('departament_id_e');
    } else {
        let id = $('#EditBusinessButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Businesses/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'country_id':  $(select).val()
            },
            success: function(response) {
                EditBusinessModalDepartament(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditBusinessAjaxError(xhr);
            }
        });
    }
};

function EditBusinessModalDepartament(departaments) {
    departaments.forEach(departament => {
        $('#departament_id_e').append(new Option(departament.name, departament.id, false, false));
    });
    let departament_id = $('#EditBusinessButton').attr('data-departament_id');
    if(departament_id != '') {
        $("#departament_id_e").val(departament_id).trigger('change');
        $('#EditBusinessButton').attr('data-departament_id', '');
    }
}

function EditBusinessModalDepartamentGetCity(select) {
    if($(select).val() == '') {
        EditBusinessModalResetSelect('city_id_e');
    } else {
        let id = $('#EditBusinessButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Businesses/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'departament_id':  $(select).val()
            },
            success: function(response) {
                EditBusinessModalResetSelect('city_id_e');
                EditBusinessModalCity(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditBusinessAjaxError(xhr);
            }
        });
    }
};

function EditBusinessModalCity(cities) {
    cities.forEach(city => {
        $('#city_id_e').append(new Option(city.name, city.id, false, false));
    });
    let city_id = $('#EditBusinessButton').attr('data-city_id');
    if(city_id != '') {
        $("#city_id_e").val(city_id).trigger('change');
        $('#EditBusinessButton').attr('data-city_id', '');
    }
}

function EditBusiness(id) {
    Swal.fire({
        title: '¿Desea actualizar la empresa?',
        text: 'La empresa se actualizara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Businesses/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_e').val(),
                    'person_type_id': $('#person_type_id_e').val(),
                    'document_type_id': $('#document_type_id_e').val(),
                    'document_number': $('#document_number_e').val(),
                    'telephone_number': $('#telephone_number_e').val(),
                    'email': $('#email_e').val(),
                    'description': $('#description_e').val(),
                    'address': $('#address_e').val(),
                    'neighborhood': $('#neighborhood_e').val(),
                    'country_id': $('#country_id_e').val(),
                    'departament_id': $('#departament_id_e').val(),
                    'city_id': $('#city_id_e').val()
                },
                success: function(response) {
                    tableBusinesses.ajax.reload();
                    EditBusinessAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    EditBusinessAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La empresa no fue actualizado.')
        }
    });
}

function EditBusinessAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.info(response.message);
        $('#EditBusinessModal').modal('hide');
    }

    if(response.status === 200) {
        toastr.success(response.message);
        $('#EditBusinessModal').modal('hide');
    }
}

function EditBusinessAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditBusinessModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditBusinessModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditBusinessModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassEditBusiness();
        RemoveIsInvalidClassEditBusiness();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassEditBusiness(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditBusiness();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditBusinessModal').modal('hide');
    }
}

function AddIsValidClassEditBusiness() {
    if (!$('#name_e').hasClass('is-invalid')) {
        $('#name_e').addClass('is-valid');
    }
    if (!$('#document_number_e').hasClass('is-invalid')) {
        $('#document_number_e').addClass('is-valid');
    }
    if (!$('#telephone_number_e').hasClass('is-invalid')) {
        $('#telephone_number_e').addClass('is-valid');
    }
    if (!$('#email_e').hasClass('is-invalid')) {
        $('#email_e').addClass('is-valid');
    }
    if (!$('#address_e').hasClass('is-invalid')) {
        $('#address_e').addClass('is-valid');
    }
    if (!$('#neighborhood_e').hasClass('is-invalid')) {
        $('#neighborhood_e').addClass('is-valid');
    }
    if (!$('#description_e').hasClass('is-invalid')) {
        $('#description_e').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-document_type_id_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-document_type_id_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-person_type_id_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-person_type_id_e-container"]').addClass('is-valid');
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

function RemoveIsValidClassEditBusiness() {
    $('#name_e').removeClass('is-valid');
    $('#document_number_e').removeClass('is-valid');
    $('#telephone_number_e').removeClass('is-valid');
    $('#email_e').removeClass('is-valid');
    $('#address_e').removeClass('is-valid');
    $('#neighborhood_e').removeClass('is-valid');
    $('#description_e').removeClass('is-valid');
    $('span[aria-labelledby="select2-document_type_id_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-person_type_id_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-country_id_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-departament_id_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-city_id_e-container"]').removeClass('is-valid');
}

function AddIsInvalidClassEditBusiness(input) {
    if (!$(`#${input}_e`).hasClass('is-valid')) {
        $(`#${input}_e`).addClass('is-invalid');
    }
    if (!$(`#span[aria-labelledby="select2-${input}_e-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_e-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditBusiness() {
    $('#name_e').removeClass('is-invalid');
    $('#document_number_e').removeClass('is-invalid');
    $('#telephone_number_e').removeClass('is-invalid');
    $('#email_e').removeClass('is-invalid');
    $('#address_e').removeClass('is-invalid');
    $('#neighborhood_e').removeClass('is-invalid');
    $('#description_e').removeClass('is-invalid');
    $('span[aria-labelledby="select2-document_type_id_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-person_type_id_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-country_id_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-departament_id_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-city_id_e-container"]').removeClass('is-invalid');
}
