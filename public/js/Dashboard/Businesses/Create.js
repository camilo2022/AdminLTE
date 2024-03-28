function CreateBusinessModal() {
    $.ajax({
        url: `/Dashboard/Businesses/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            CreateBusinessModalCleaned();
            CreateBusinessModalCountry(response.data.countries);
            CreateBusinessModalPersonType(response.data.personTypes);
            CreateBusinessAjaxSuccess(response);
            $('#CreateBusinessModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            CreateBusinessAjaxError(xhr);
        }
    });
}

function CreateBusinessModalCleaned() {
    CreateBusinessModalResetSelect('country_id_c');
    CreateBusinessModalResetSelect('person_type_id_c');
    RemoveIsValidClassCreateBusiness();
    RemoveIsInvalidClassCreateBusiness();

    $('#name_c').val('');
    $('#document_number_c').val('');
    $('#telephone_number_c').val('');
    $('#email_c').val('');
    $('#description_c').val('');
    $('#address_c').val('');
    $('#neighborhood_c').val('');
}

function CreateBusinessModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function CreateBusinessModalPersonType(personTypes) {
    personTypes.forEach(personType => {
        $('#person_type_id_c').append(new Option(personType.name, personType.id, false, false));
    });
}

function CreateBusinessModalPersonTypeGetDocumentType(select) {
    if($(select).val() == '') {
        CreateBusinessModalResetSelect('document_type_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Businesses/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'person_type_id':  $(select).val()
            },
            success: function(response) {
                CreateBusinessModalResetSelect('document_type_id_c');
                CreateBusinessModalDocumentType(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateBusinessAjaxError(xhr);
            }
        });
    }
}

function CreateBusinessModalDocumentType(documentTypes) {
    documentTypes.forEach(documentType => {
        $('#document_type_id_c').append(new Option(documentType.name, documentType.id, false, false));
    });
}

function CreateBusinessModalCountry(countries) {
    countries.forEach(country => {
        $('#country_id_c').append(new Option(country.name, country.id, false, false));
    });
}

function CreateBusinessModalCountryGetDepartament(select) {
    if($(select).val() == '') {
        CreateBusinessModalResetSelect('departament_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Businesses/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'country_id':  $(select).val()
            },
            success: function(response) {
                CreateBusinessModalResetSelect('departament_id_c');
                CreateBusinessModalDepartament(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateBusinessAjaxError(xhr);
            }
        });
    }
};

function CreateBusinessModalDepartament(departaments) {
    departaments.forEach(departament => {
        $('#departament_id_c').append(new Option(departament.name, departament.id, false, false));
    });
}

function CreateBusinessModalDepartamentGetCity(select) {
    if($(select).val() == '') {
        CreateBusinessModalResetSelect('city_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Businesses/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'departament_id':  $(select).val()
            },
            success: function(response) {
                CreateBusinessModalResetSelect('city_id_c');
                CreateBusinessModalCity(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateBusinessAjaxError(xhr);
            }
        });
    }
};

function CreateBusinessModalCity(cities) {
    cities.forEach(city => {
        $('#city_id_c').append(new Option(city.name, city.id, false, false));
    });
}

function CreateBusiness() {
    Swal.fire({
        title: '¿Desea guardar la empresa?',
        text: 'La empresa será creada.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Businesses/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#name_c').val(),
                    'person_type_id': $('#person_type_id_c').val(),
                    'document_type_id': $('#document_type_id_c').val(),
                    'document_number': $('#document_number_c').val(),
                    'telephone_number': $('#telephone_number_c').val(),
                    'email': $('#email_c').val(),
                    'description': $('#description_c').val(),
                    'address': $('#address_c').val(),
                    'neighborhood': $('#neighborhood_c').val(),
                    'country_id': $('#country_id_c').val(),
                    'departament_id': $('#departament_id_c').val(),
                    'city_id': $('#city_id_c').val()
                },
                success: function(response) {
                    tableBusinesses.ajax.reload();
                    CreateBusinessAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    CreateBusinessAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La empresa no fue creada.')
        }
    });
}

function CreateBusinessAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.info(response.message);
        $('#CreateBusinessModal').modal('hide');
    }

    if(response.status === 201) {
        toastr.success(response.message);
        $('#CreateBusinessModal').modal('hide');
    }
}

function CreateBusinessAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateBusinessModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateBusinessModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateBusinessModal').modal('hide');
    }

    if(xhr.status === 422){
        RemoveIsValidClassCreateBusiness();
        RemoveIsInvalidClassCreateBusiness();
        $.each(xhr.responseJSON.errors, function(field, messages) {
            AddIsInvalidClassCreateBusiness(field);
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateBusiness();
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateBusinessModal').modal('hide');
    }
}

function AddIsValidClassCreateBusiness() {
    if (!$('#name_c').hasClass('is-invalid')) {
      $('#name_c').addClass('is-valid');
    }
    if (!$('#document_number_c').hasClass('is-invalid')) {
      $('#document_number_c').addClass('is-valid');
    }
    if (!$('#telephone_number_c').hasClass('is-invalid')) {
        $('#telephone_number_c').addClass('is-valid');
    }
    if (!$('#email_c').hasClass('is-invalid')) {
        $('#email_c').addClass('is-valid');
    }
    if (!$('#address_c').hasClass('is-invalid')) {
        $('#address_c').addClass('is-valid');
    }
    if (!$('#neighborhood_c').hasClass('is-invalid')) {
        $('#neighborhood_c').addClass('is-valid');
    }
    if (!$('#description_c').hasClass('is-invalid')) {
        $('#description_c').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-document_type_id_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-document_type_id_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-person_type_id_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-person_type_id_c-container"]').addClass('is-valid');
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

function RemoveIsValidClassCreateBusiness() {
    $('#name_c').removeClass('is-valid');
    $('#document_number_c').removeClass('is-valid');
    $('#telephone_number_c').removeClass('is-valid');
    $('#email_c').removeClass('is-valid');
    $('#address_c').removeClass('is-valid');
    $('#neighborhood_c').removeClass('is-valid');
    $('#description_c').removeClass('is-valid');
    $('span[aria-labelledby="select2-document_type_id_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-person_type_id_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-country_id_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-departament_id_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-city_id_c-container"]').removeClass('is-valid');
}

function AddIsInvalidClassCreateBusiness(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).addClass('is-invalid');
    }
    if (!$(`#span[aria-labelledby="select2-${input}_c-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_c-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateBusiness() {
    $('#name_c').removeClass('is-invalid');
    $('#document_number_c').removeClass('is-invalid');
    $('#telephone_number_c').removeClass('is-invalid');
    $('#email_c').removeClass('is-invalid');
    $('#address_c').removeClass('is-invalid');
    $('#neighborhood_c').removeClass('is-invalid');
    $('#description_c').removeClass('is-invalid');
    $('span[aria-labelledby="select2-document_type_id_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-person_type_id_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-country_id_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-departament_id_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-city_id_c-container"]').removeClass('is-invalid');
}
