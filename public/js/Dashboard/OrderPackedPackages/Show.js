$('#ShowOrderPackage').trigger('click');

function ShowOrderPackage(order_package_id) {
    $.ajax({
        url: `/Dashboard/Orders/Packed/Packages/Show/Query`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'order_package_id': order_package_id
        },
        success: function(response) {
            
            response.data.url == null ? ShowOrderPackageCleaned(response.data.orderPackage) : window.location.href = response.data.url ;
            ShowOrderPackageAjaxSuccess(response);
        },
        error: function(xhr, textStatus, errorThrown) {
            ShowOrderPackageAjaxError(xhr);
        }
    });
}

function ShowOrderPackageCleaned(packageDetails) {
    console.log(packageDetails)
    $('#orderPackageDetails').html('');

    let orderPackageDetails = '';
    
    $.each(packageDetails.order_packing.order_dispatch.order_dispatch_details, function(i, packageDetail) {
        if (i % 2 === 0) {
            orderPackageDetails += '<div class="row">';
        }

        let contar = packageDetail.order_packages_details.reduce((total, objeto) => {
                return objeto.order_package_detail_quantities.reduce((subTotal, subObjeto) => {
                    return subTotal + subObjeto.quantity;
                }, 0) + total;
            }, 0);

        let total = packageDetail.order_dispatch_detail_quantities.reduce((total, objeto) => total + objeto.quantity, 0)

        orderPackageDetails += `<div class="col-lg-6">
            <button type="button" class="mb-2 btn w-100 collapsed btn-dark" data-toggle="collapse" data-target="#collapsePackage${i}" aria-expanded="false" aria-controls="#collapsePackage${i}">
                <b>
                    <div class="table-responsive">
                        <span>REF: ${packageDetail.order_detail.product.code}-${packageDetail.order_detail.color.code}-${packageDetail.order_detail.tone.code}</span> | <span class="badge badge-light" id="${packageDetail.order_detail.product.code}-${packageDetail.order_detail.color.code}-${packageDetail.order_detail.tone.code}-contar">${contar}</span> de <span class="badge badge-${contar == total ? 'success' : 'danger'}" id="${packageDetail.order_detail.product.code}-${packageDetail.order_detail.color.code}-${packageDetail.order_detail.tone.code}-total">${total}</span> | <span class="badge badge-danger" id="${packageDetail.order_detail.product.code}-${packageDetail.order_detail.color.code}-${packageDetail.order_detail.tone.code}-badge">${contar == total ? 'Completado' : 'Hace falta'}</span>
                    </div>
                </b>
            </button>
            <div class="table-responsive collapse" id="collapsePackage${i}">
                <div class="col-12">
                    <div class="row mb-2 text-center">
                        <div class="col-12">
                            <input onkeyup="DetailOrderPackage" type="text" class="mb-2 w-100 form-control" style="border: 1px solid black !important;" value="">
                        </div>
                    </div>
                    <table id="2-table" class="table text-center">
                        <thead>
                            <tr>
                                <th scope="col">TALLA</th>
                                <th scope="col">CP</th>
                                <th scope="col">CD</th>
                            </tr>
                        </thead>
                        <tbody>`;
        $.each(packageDetail.order_dispatch_detail_quantities, function(i, orderDispatchDetailQuantity) {
            if(orderDispatchDetailQuantity.quantity != 0) {
                orderPackageDetails += `<tr>
                    <th scope="col">${orderDispatchDetailQuantity.order_detail_quantity.size.code}</th>
                    <td id="${packageDetail.order_detail.product.code}-${packageDetail.order_detail.color.code}-${packageDetail.order_detail.tone.code}-${orderDispatchDetailQuantity.order_detail_quantity.size.code.replace('T', '')}-CP">0</td>
                    <td id="${packageDetail.order_detail.product.code}-${packageDetail.order_detail.color.code}-${packageDetail.order_detail.tone.code}-${orderDispatchDetailQuantity.order_detail_quantity.size.code.replace('T', '')}-CD">0</td>
                </tr>`;
            }
        })
        
                        
        orderPackageDetails += `</tbody>
                    </table>
                </div>
            </div>
        </div>`;

        if ((i + 1) % 2 === 0 || i === packageDetails.order_packing.order_dispatch.order_dispatch_details.length - 1) {
            orderPackageDetails += '</div>'; // Cierra el row
        }
    });

    $('#orderPackageDetails').html(orderPackageDetails);
}

function ShowOrderPackageAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.info(response.message);
    }
}

function ShowOrderPackageAjaxError(xhr) {
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
            $.each(messages, function(Show, message) {
                toastr.error(message);
            });
        });
    }

    if(xhr.status === 500){
        toastr.error(xhr.responseJSON.error ? xhr.responseJSON.error.message : xhr.responseJSON.message);
    }
}
