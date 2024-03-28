function EditClientBranchModal(id) {
    $.ajax({
        url: `/Dashboard/Clients/Branches/Edit/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            EditClientBranchModalCleaned(response.data.clientBranch);
            EditClientBranchModalCountry(response.data.countries);
            EditClientBranchAjaxSuccess(response);
            $('#EditClientBranchModal').modal('show');
        },
        error: function (xhr, textStatus, errorThrown) {
            EditClientBranchAjaxError(xhr);
        }
    });
}

function EditClientBranchModalCleaned(clientBranch) {
    EditClientBranchModalResetSelect('country_id_cb_e');
    RemoveIsValidClassEditClientBranch();
    RemoveIsInvalidClassEditClientBranch();

    $('#EditClientBranchButton').attr('onclick', `EditClientBranch(${clientBranch.id})`);
    $('#EditClientBranchButton').attr('data-id', clientBranch.id);
    $('#EditClientBranchButton').attr('data-country_id', clientBranch.country_id);
    $('#EditClientBranchButton').attr('data-departament_id', clientBranch.departament_id);
    $('#EditClientBranchButton').attr('data-city_id', clientBranch.city_id);

    $('#name_cb_e').val(clientBranch.name);
    $('#code_cb_e').val(clientBranch.code);
    $('#address_cb_e').val(clientBranch.address);
    $('#neighborhood_cb_e').val(clientBranch.neighborhood);
    $('#description_cb_e').val(clientBranch.description);
    $('#email_cb_e').val(clientBranch.email);
    $('#telephone_number_first_cb_e').val(clientBranch.telephone_number_first);
    $('#telephone_number_second_cb_e').val(clientBranch.telephone_number_second);
}

function EditClientBranchModalResetSelect(id) {
    $(`#${id}`).html('')
    $(`#${id}`).append(new Option('Seleccione', '', false, false));
    $(`#${id}`).trigger('change');
}

function EditClientBranchModalCountry(countries) {
    countries.forEach(country => {
        $('#country_id_cb_e').append(new Option(country.name, country.id, false, false));
    });

    let country_id = $('#EditClientBranchButton').attr('data-country_id');
    if(country_id != '') {
        $("#country_id_cb_e").val(country_id).trigger('change');
        $('#EditClientBranchButton').attr('data-country_id', '');
    }
}

function EditClientBranchModalCountryGetDepartament(select) {
    if($(select).val() == '') {
        EditClientBranchModalResetSelect('departament_id_cb_e');
    } else {
        let id = $('#EditClientBranchButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Clients/Branches/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'country_id':  $(select).val()
            },
            success: function(response) {
                EditClientBranchModalResetSelect('departament_id_cb_e');
                EditClientBranchModalDepartament(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditClientAjaxError(xhr);
            }
        });
    }
};

function EditClientBranchModalDepartament(departaments) {
    departaments.forEach(departament => {
        $('#departament_id_cb_e').append(new Option(departament.name, departament.id, false, false));
    });

    let departament_id = $('#EditClientBranchButton').attr('data-departament_id');
    if(departament_id != '') {
        $("#departament_id_cb_e").val(departament_id).trigger('change');
        $('#EditClientBranchButton').attr('data-departament_id', '');
    }
}

function EditClientBranchModalDepartamentGetCity(select) {
    if($(select).val() == '') {
        EditClientBranchModalResetSelect('city_id_cb_e');
    } else {
        let id = $('#EditClientBranchButton').attr('data-id');
        $.ajax({
            url: `/Dashboard/Clients/Branches/Edit/${id}`,
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'departament_id':  $(select).val()
            },
            success: function(response) {
                EditClientBranchModalResetSelect('city_id_cb_e');
                EditClientBranchModalCity(response.data);
            },
            error: function(xhr, textStatus, errorThrown) {
                EditClientAjaxError(xhr);
            }
        });
    }
};

function EditClientBranchModalCity(cities) {
    cities.forEach(city => {
        $('#city_id_cb_e').append(new Option(city.name, city.id, false, false));
    });

    let city_id = $('#EditClientBranchButton').attr('data-city_id');
    if(city_id != '') {
        $("#city_id_cb_e").val(city_id).trigger('change');
        $('#EditClientBranchButton').attr('data-city_id', '');
    }
}

function EditClientBranch(id) {
    Swal.fire({
        title: '¿Desea actualizar la sucursal?',
        text: 'La sucursal será actualizado.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, actualizar!',
        cancelButtonText: 'No, cancelar!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `/Dashboard/Clients/Branches/Update/${id}`,
                type: 'PUT',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'client_id': $('#IndexClientBranchButton').attr('data-client_id'),
                    'name': $('#name_cb_e').val(),
                    'code': $('#code_cb_e').val(),
                    'country_id': $('#country_id_cb_e').val(),
                    'departament_id': $('#departament_id_cb_e').val(),
                    'city_id': $('#city_id_cb_e').val(),
                    'address': $('#address_cb_e').val(),
                    'neighborhood': $('#neighborhood_cb_e').val(),
                    'description': $('#description_cb_e').val(),
                    'email': $('#email_cb_e').val(),
                    'telephone_number_first': $('#telephone_number_first_cb_e').val(),
                    'telephone_number_second': $('#telephone_number_second_cb_e').val(),
                },
                success: function (response) {
                    tableClientBranches.ajax.reload();
                    EditClientBranchAjaxSuccess(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                    EditClientBranchAjaxError(xhr);
                }
            });
        } else {
            toastr.info('La sucursal no fue actualizado.')
        }
    });
}

function EditClientBranchAjaxSuccess(response) {
    if (response.status === 200) {
        toastr.success(response.message);
        $('#EditClientBranchModal').modal('hide');
    }

    if (response.status === 204) {
        toastr.info(response.message);
        $('#EditClientBranchModal').modal('hide');
    }
}

function EditClientBranchAjaxError(xhr) {
    if (xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditClientBranchModal').modal('hide');
    }

    if (xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditClientBranchModal').modal('hide');
    }

    if (xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditClientBranchModal').modal('hide');
    }

    if (xhr.status === 422) {
        RemoveIsValidClassEditClientBranch();
        RemoveIsInvalidClassEditClientBranch();
        $.each(xhr.responseJSON.errors, function (field, messages) {
            AddIsInvalidClassEditClientBranch(field);
            $.each(messages, function (index, message) {
                toastr.error(message);
            });
        });
        AddIsValidClassEditClientBranch();
    }

    if (xhr.status === 500) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#EditClientBranchModal').modal('hide');
    }
}

function AddIsValidClassEditClientBranch() {
    if (!$('#code_cb_e').hasClass('is-invalid')) {
        $('#code_cb_e').addClass('is-valid');
    }
    if (!$('#address_cb_e').hasClass('is-invalid')) {
        $('#address_cb_e').addClass('is-valid');
    }
    if (!$('#neighborhood_cb_e').hasClass('is-invalid')) {
        $('#neighborhood_cb_e').addClass('is-valid');
    }
    if (!$('#description_cb_e').hasClass('is-invalid')) {
        $('#description_cb_e').addClass('is-valid');
    }
    if (!$('#email_cb_e').hasClass('is-invalid')) {
        $('#email_cb_e').addClass('is-valid');
    }
    if (!$('#telephone_number_first_cb_e').hasClass('is-invalid')) {
        $('#telephone_number_first_cb_e').addClass('is-valid');
    }
    if (!$('#telephone_number_second_cb_e').hasClass('is-invalid')) {
        $('#telephone_number_second_cb_e').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-country_id_cb_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-country_id_cb_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-departament_id_cb_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-departament_id_cb_e-container"]').addClass('is-valid');
    }
    if (!$('span[aria-labelledby="select2-city_id_cb_e-container"]').hasClass('is-invalid')) {
        $('span[aria-labelledby="select2-city_id_cb_e-container"]').addClass('is-valid');
    }
}

function RemoveIsValidClassEditClientBranch() {
    $('#code_cb_e').removeClass('is-valid');
    $('#address_cb_e').removeClass('is-valid');
    $('#neighborhood_cb_e').removeClass('is-valid');
    $('#description_cb_e').removeClass('is-valid');
    $('#email_cb_e').removeClass('is-valid');
    $('#telephone_number_first_cb_e').removeClass('is-valid');
    $('#telephone_number_second_cb_e').removeClass('is-valid');
    $('span[aria-labelledby="select2-country_id_cb_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-departament_id_cb_e-container"]').removeClass('is-valid');
    $('span[aria-labelledby="select2-city_id_cb_e-container"]').removeClass('is-valid');
}

function AddIsInvalidClassEditClientBranch(input) {
    if (!$(`#${input}_cb_e`).hasClass('is-valid')) {
        $(`#${input}_cb_e`).addClass('is-invalid');
    }
    if (!$(`span[aria-labelledby="select2-${input}_cb_e-container`).hasClass('is-valid')) {
        $(`span[aria-labelledby="select2-${input}_cb_e-container"]`).addClass('is-invalid');
    }
}

function RemoveIsInvalidClassEditClientBranch() {
    $('#code_cb_e').removeClass('is-invalid');
    $('#address_cb_e').removeClass('is-invalid');
    $('#neighborhood_cb_e').removeClass('is-invalid');
    $('#description_cb_e').removeClass('is-invalid');
    $('#email_cb_e').removeClass('is-invalid');
    $('#telephone_number_first_cb_e').removeClass('is-invalid');
    $('#telephone_number_second_cb_e').removeClass('is-invalid');
    $('span[aria-labelledby="select2-country_id_cb_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-departament_id_cb_e-container"]').removeClass('is-invalid');
    $('span[aria-labelledby="select2-city_id_cb_e-container"]').removeClass('is-invalid');
}
