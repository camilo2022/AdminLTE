function ShowProductModal(id) {
    $.ajax({
        url: `/Dashboard/Products/Show/${id}`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            tableProducts.ajax.reload();
            ShowProductModalCleaned(response.data);
            $('#ShowProductModal').modal('show');
        },
        error: function(xhr, textStatus, errorThrown) {
            tableProducts.ajax.reload();
            ShowProductAjaxError(xhr);
        }
    });
}

function ShowProductModalCleaned(product) {
    $.each(product.photos, function(index, photo) {
        $('#photos_indicators').append(`<li data-target="#carouselExampleIndicators" data-slide-to="${index}" ${index == 0 ? 'class="active"' : ''}></li>`);
        $('#photos_carousel').append(`<div class="carousel-item ${index == 0 ? 'active' : ''}">
                <img class="d-block w-100" src="${photo.path}" alt="${photo.name}">
            </div>`);
    });
}

function ShowProductAjaxSuccess(response) {
    if(response.status === 200) {
        toastr.success(response.message);
        $('#ShowProductModal').modal('hide');
    }
}

function ShowProductAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowProductModal').modal('hide');
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowProductModal').modal('hide');
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowProductModal').modal('hide');
    }

    if(xhr.status === 422){
        $.each(xhr.responseJSON.errors, function(field, messages) {
            $.each(messages, function(index, message) {
                toastr.error(message);
            });
        });
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
        $('#ShowProductModal').modal('hide');
    }
}
