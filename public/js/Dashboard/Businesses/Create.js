function CreateBusinessModal() {
    $.ajax({
        url: `/Dashboard/Businesses/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            CreateBusinessModalCleaned();
            CreateBusinessModalCountry(response.data);
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
    RemoveIsValidClassCreateBusiness();
    RemoveIsInvalidClassCreateBusiness();

    $('#name_c').val('');
    $('#document_number_c').val('');
    $('#telephone_number_c').val('');
    $('#email_c').val('');
    $('#description_c').val('');
    $('#address_c').val('');
    $('#neighbourhood_c').val('');
}

function CreateBusinessModalResetSelect(id) {
    const select = $(`#${id}`);
    select.html('');
    const defaultOption = $('<option>', {
        value: '',
        text: 'Seleccione'
    });
    select.append(defaultOption);
    select.trigger('change');
}

function CreateBusinessModalCountry(countries) {
    countries.forEach(country => {
        let newOption = new Option(country.name, country.id, false, false);
        $('#country_id_c').append(newOption);
    });
}

$('#country_id_c').on('change', function() {
    if($(this).val() == '') {
        CreateBusinessModalResetSelect('departament_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Businesses/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'country_id':  $(this).val()
            },
            success: function(response) {
                CreateBusinessModalDepartament(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateBusinessAjaxError(xhr);
            }
        });
    }
});

function CreateBusinessModalDepartament(departaments) {
    departaments.forEach(departament => {
        let newOption = new Option(departament.name, departament.id, false, false);
        $('#departament_id_c').append(newOption);
    });
}

$('#departament_id_c').on('change', function() {
    if($(this).val() == '') {
        CreateBusinessModalResetSelect('city_id_c');
    } else {
        $.ajax({
            url: `/Dashboard/Businesses/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'departament_id':  $(this).val()
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
});

function CreateBusinessModalCity(cities) {
    cities.forEach(city => {
        let newOption = new Option(city.name, city.id, false, false);
        $('#city_id_c').append(newOption);
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
                    'document_number': $('#document_number_c').val(),
                    'telephone_number': $('#telephone_number_c').val(),
                    'email': $('#email_c').val(),
                    'description': $('#description_c').val(),
                    'address': $('#address_c').val(),
                    'neighbourhood': $('#neighbourhood_c').val(),
                    'country_id': $('#country_id_c').val(),
                    'departament_id': $('#departament_id_c').val(),
                    'city_id': $('#city_id_c').val()
                },
                success: function(response) {
                    tableBusinesses.ajax.reload();
                    CreateBusinessAjaxSuccess(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    tableBusinesses.ajax.reload();
                    CreateBusinessAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La empresa no fue creada.')
        }
    });
}

function CreateBusinessAjaxSuccess(response) {
    if(response.status === 200) {
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
    if (!$('#neighbourhood_c').hasClass('is-invalid')) {
        $('#neighbourhood_c').addClass('is-valid');
    }
    if (!$('#description_c').hasClass('is-invalid')) {
        $('#description_c').addClass('is-valid');
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
    $('#neighbourhood_c').removeClass('is-valid');
    $('#description_c').removeClass('is-valid');
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
    $('#neighbourhood_c').removeClass('is-invalid');
    $('#description_c').removeClass('is-invalid');
    $('span[aria-labelledby="select2-country_id_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-departament_id_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-city_id_c-container"]').removeClass('is-invalid');
}
