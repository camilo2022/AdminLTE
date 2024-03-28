function CreateClientBranchModal() {
    $.ajax({
        url: `/Dashboard/Clients/Branches/Create`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function (response) {
            CreateClientBranchModalCleaned();
            CreateClientBranchModalCountry(response.data);
            CreateClientBranchAjaxSuccess(response);
            $('#CreateClientBranchModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            CreateClientBranchAjaxError(xhr);
        }
    });
}

function CreateClientBranchModalCleaned() {
    CreateClientBranchModalResetSelect('country_id_cb_c');
    RemoveIsValidClassCreateClientBranch();
    RemoveIsInvalidClassCreateClientBranch();

    $('#name_cb_c').val('');
    $('#code_cb_c').val('');
    $('#address_cb_c').val('');
    $('#neighborhood_cb_c').val('');
    $('#description_cb_c').val('');
    $('#email_cb_c').val('');
    $('#telephone_number_first_cb_c').val('');
    $('#telephone_number_second_cb_c').val('');
}

function CreateClientBranchModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function CreateClientBranchModalCountry(countries) {
    countries.forEach(country => {
        $('#country_id_cb_c').append(new Option(country.name, country.id, false, false));
    });
}

function CreateClientBranchModalCountryGetDepartament(select) {
    if($(select).val() == '') {
        CreateClientBranchModalResetSelect('departament_id_cb_c');
    } else {
        $.ajax({
            url: `/Dashboard/Clients/Branches/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'country_id':  $(select).val()
            },
            success: function(response) {
                CreateClientBranchModalResetSelect('departament_id_cb_c');
                CreateClientBranchModalDepartament(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateClientAjaxError(xhr);
            }
        });
    }
};

function CreateClientBranchModalDepartament(departaments) {
    departaments.forEach(departament => {
        $('#departament_id_cb_c').append(new Option(departament.name, departament.id, false, false));
    });
}

function CreateClientBranchModalDepartamentGetCity(select) {
    if($(select).val() == '') {
        CreateClientBranchModalResetSelect('city_id_cb_c');
    } else {
        $.ajax({
            url: `/Dashboard/Clients/Branches/Create`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'departament_id':  $(select).val()
            },
            success: function(response) {
                CreateClientBranchModalResetSelect('city_id_cb_c');
                CreateClientBranchModalCity(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                CreateClientAjaxError(xhr);
            }
        });
    }
};

function CreateClientBranchModalCity(cities) {
    cities.forEach(city => {
        $('#city_id_cb_c').append(new Option(city.name, city.id, false, false));
    });
}

function CreateClientBranch() {
    Swal.fire({
        title: '¿Desea guardar la sucursal?',
        text: 'La sucursal será creado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, guardar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Clients/Branches/Store`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'client_id': $('#IndexClientBranchButton').attr('data-client_id'),
                    'name': $('#name_cb_c').val(),
                    'code': $('#code_cb_c').val(),
                    'country_id': $('#country_id_cb_c').val(),
                    'departament_id': $('#departament_id_cb_c').val(),
                    'city_id': $('#city_id_cb_c').val(),
                    'address': $('#address_cb_c').val(),
                    'neighborhood': $('#neighborhood_cb_c').val(),
                    'description': $('#description_cb_c').val(),
                    'email': $('#email_cb_c').val(),
                    'telephone_number_first': $('#telephone_number_first_cb_c').val(),
                    'telephone_number_second': $('#telephone_number_second_cb_c').val(),
                },
                success: function (response) {
                    tableClientBranches.ajax.reload();
                    CreateClientBranchAjaxSuccess(response);
                    RemoveIsValidClassCreateClientBranch();
                    RemoveIsInvalidClassCreateClientBranch();
                },
                error: function (xhr, textStatus, errorThrown) {
                    CreateClientBranchAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La sucursal no fue creado.')
        }
    });
}

function CreateClientBranchAjaxSuccess(response) {
    if (response.status === 204) {
        toastr.info(response.message);
        $('#CreateClientBranchModal').modal('hide');
    }

    if (response.status === 201) {
        toastr.success(response.message);
        $('#CreateClientBranchModal').modal('hide');
    }
}

function CreateClientBranchAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateClientBranchModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateClientBranchModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateClientBranchModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassCreateClientBranch();
        RemoveIsInvalidClassCreateClientBranch();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassCreateClientBranch(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassCreateClientBranch();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#CreateClientBranchModal').modal('hide');
    }
}

function AddIsValidClassCreateClientBranch() {
    if (!$('#name_cb_c').hasClass('is-invalid')) {
        $('#name_cb_c').addClass('is-valid');
    }
    if (!$('#code_cb_c').hasClass('is-invalid')) {
        $('#code_cb_c').addClass('is-valid');
    }
    if (!$('#address_cb_c').hasClass('is-invalid')) {
        $('#address_cb_c').addClass('is-valid');
    }
    if (!$('#neighborhood_cb_c').hasClass('is-invalid')) {
        $('#neighborhood_cb_c').addClass('is-valid');
    }
    if (!$('#description_cb_c').hasClass('is-invalid')) {
        $('#description_cb_c').addClass('is-valid');
    }
    if (!$('#email_cb_c').hasClass('is-invalid')) {
        $('#email_cb_c').addClass('is-valid');
    }
    if (!$('#telephone_number_first_cb_c').hasClass('is-invalid')) {
        $('#telephone_number_first_cb_c').addClass('is-valid');
    }
    if (!$('#telephone_number_second_cb_c').hasClass('is-invalid')) {
        $('#telephone_number_second_cb_c').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-country_id_cb_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-country_id_cb_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-departament_id_cb_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-departament_id_cb_c-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-city_id_cb_c-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-city_id_cb_c-container"]').addClass('is-valid');
    }
}

function RemoveIsValidClassCreateClientBranch() {
    $('#name_cb_c').removeClass('is-valid');
    $('#code_cb_c').removeClass('is-valid');
    $('#address_cb_c').removeClass('is-valid');
    $('#neighborhood_cb_c').removeClass('is-valid');
    $('#description_cb_c').removeClass('is-valid');
    $('#email_cb_c').removeClass('is-valid');
    $('#telephone_number_first_cb_c').removeClass('is-valid');
    $('#telephone_number_second_cb_c').removeClass('is-valid');
    $('span[aria-labelledby="select2-country_id_cb_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-departament_id_cb_c-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-city_id_cb_c-container"]').removeClass('is-valid');
}

function AddIsInvalidClassCreateClientBranch(input) {
    if (!$(`#${input}_cb_c`).hasClass('is-valid')) {
        $(`#${input}_cb_c`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_cb_c-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_cb_c-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassCreateClientBranch() {
    $('#name_cb_c').removeClass('is-invalid');
    $('#code_cb_c').removeClass('is-invalid');
    $('#address_cb_c').removeClass('is-invalid');
    $('#neighborhood_cb_c').removeClass('is-invalid');
    $('#description_cb_c').removeClass('is-invalid');
    $('#email_cb_c').removeClass('is-invalid');
    $('#telephone_number_first_cb_c').removeClass('is-invalid');
    $('#telephone_number_second_cb_c').removeClass('is-invalid');
    $('span[aria-labelledby="select2-country_id_cb_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-departament_id_cb_c-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-city_id_cb_c-container"]').removeClass('is-invalid');
}
