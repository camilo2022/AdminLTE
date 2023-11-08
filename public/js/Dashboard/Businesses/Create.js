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
    $('#code_c').val('');
}

function CreateBusinessModalResetSelect(id) {
    const select = document.getElementById(id);
    // Remove all options by setting the select's innerHTML to an empty string
    select.innerHTML = '';

    // Add a new option
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.text = 'Seleccione';
    select.appendChild(defaultOption);

    // Trigger the change event
    $(select).trigger('change');
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
                    'code': $('#code_c').val(),
                    'start_date': $('#start_date_c').val(),
                    'end_date': $('#end_date_c').val()
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
        toastr.error(xhr.responseJSON.error.message);
        $('#CreateBusinessModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateBusinessModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error.message);
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
        if(xhr.responseJSON.error) {
            toastr.error(xhr.responseJSON.error.message);
        }

        if(xhr.responseJSON.message) {
            toastr.error(xhr.responseJSON.message);
        }
        $('#CreateBusinessModal').modal('hide');
    }
}

function AddIsValidClassCreateBusiness() {
    if (!$('#name_c').hasClass('is-invalid')) {
      $('#name_c').addClass('is-valid');
    }
    if (!$('#code_c').hasClass('is-invalid')) {
      $('#code_c').addClass('is-valid');
    }
    $('span[aria-labelledby="select2-country_id_c-container"]').addClass('is-valid');
}

function RemoveIsValidClassCreateBusiness() {
    $('#name_c').removeClass('is-valid');
    $('#code_c').removeClass('is-valid');
    
    $('span[aria-labelledby="select2-country_id_c-container"]').addClass('is-valid');
}

function AddIsInvalidClassCreateBusiness(input) {
    if (!$(`#${input}_c`).hasClass('is-valid')) {
        $(`#${input}_c`).removeClass('is-valid');
    }
    $(`#${input}_c`).addClass('is-invalid');
    $('span[aria-labelledby="select2-country_id_c-container"]').addClass('is-valid');
}

function RemoveIsInvalidClassCreateBusiness() {
    $('#name_c').removeClass('is-invalid');
    $('#code_c').removeClass('is-invalid');
    $('span[aria-labelledby="select2-country_id_c-container"]').addClass('is-valid');
}
