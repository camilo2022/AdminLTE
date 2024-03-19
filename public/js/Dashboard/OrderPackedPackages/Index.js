$('#IndexOrderPackedDetail').trigger('click');

function IndexOrderPackedDetail(order_packing_id) {
    $.ajax({
        url: `/Dashboard/Orders/Packed/Packages/Index/Query`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'order_packing_id': order_packing_id
        },
        success: function(response) {
            IndexOrderPackedDetailCleaned(response.data);
            IndexOrderPackedDetailAjaxSuccess(response);
        },
        error: function(xhr, textStatus, errorThrown) {
            IndexOrderPackedDetailAjaxError(xhr);
        }
    });
}

function IndexOrderPackedDetailCleaned(packages) {
    $('#orderPackages').html('');

    let orderPackages = '';
    
    $.each(packages, function(i, package) {
        if (i % 2 === 0) {
            orderPackages += '<div class="row">';
        }

        orderPackages += `<div class="col-lg-6">
            <button type="button" class="mb-2 btn w-100 collapsed btn-dark" data-toggle="collapse" data-target="#collapsePackage${i}" aria-expanded="false" aria-controls="#collapsePackage${i}">
                <b>
                    <div class="table-responsive">
                        <i class="fa fa-box"></i>
                        <span>${package.package_type.name} #</span> <span class="badge badge-light">${package.package_type.id}</span> | <span> PESO: </span> <span class="badge badge-light">${package.weight}</span>
                    </div>
                </b>
            </button>
            <div class="table-responsive collapse" id="collapsePackage${i}">
                <div class="col-12">
                    <div class="row mb-2 text-center">
                        <div class="col-lg-4">
                            <a class="btn btn-primary" type="button" onclick="OpenOrderPackedPackage(${package.id})" title="Abrir empaque.">
                                <i class="fas fa-box-open-full text-white"></i>
                            </a>
                        </div>
                        <div class="col-lg-4">
                            <a class="btn btn-warning" type="button" onclick="CloseOrderPackedPackage(${package.id}, false)" title="Cerrar empaque.">
                                <i class="fas fa-box-taped text-white"></i>
                            </a>
                        </div>
                        <div class="col-lg-4">
                            <a class="btn btn-danger" type="button" onclick="DeleteOrderPackedPackage(${package.id}, false)" title="Eliminar empaque.">
                                <i class="fas fa-trash text-white"></i>
                            </a>
                        </div>
                    </div>`;

        $.each(package.order_package_details, function(j, package_detail) {
            orderPackages += `<div class="col-lg-12">
                <button type="button" class="mb-2 btn btn-info w-100 collapsed" data-toggle="collapse" data-target="#collapsePackageDetail${j}" aria-expanded="false" aria-controls="#collapsePackageDetail${j}">
                    <b>
                        <div class="table-responsive">
                            <i class="fa fa-solid fa-paperclip"></i>
                            <span>${package_detail.order_dispatch_detail.order_detail.product.code}-${package_detail.order_dispatch_detail.order_detail.color.code}-${package_detail.order_dispatch_detail.order_detail.tone.code}</span> | <span class="badge badge-light">${package_detail.order_package_detail_quantities.reduce((total, objeto) => total + objeto.quantity, 0)} UND</span>
                        </div>
                    </b>
                </button>
                <div class="table-responsive collapse" id="collapsePackageDetail${j}">
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th scope="col">TALLA</th>
                                <th scope="col">CE</th>
                            </tr>
                        </thead>
                        <tbody>`;

            $.each(package_detail.order_package_detail_quantities, function(j, package_detail_quantity) {
                orderPackages += `<tr>
                    <th scope="col">${package_detail_quantity.order_dispatch_detail_quantity.order_detail_quantity.size.code}</th>
                    <td>${package_detail_quantity.quantity}</td>
                </tr>`;
            })

            orderPackages += `</tbody>
                    </table>
                </div>
            </div>`;
        })
                        
        orderPackages += `</div>
            </div>
        </div>`;

        if ((i + 1) % 2 === 0 || i === packages.length - 1) {
            orderPackages += '</div>'; // Cierra el row
        }
    });

    $('#orderPackages').html(orderPackages);
}

function IndexOrderPackedDetailAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.info(response.message);
    }
}

function IndexOrderPackedDetailAjaxError(xhr) {
    if(xhr.status === 403) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
    }

    if(xhr.status === 404) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
    }

    if(xhr.status === 419) {
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
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
    }
}
