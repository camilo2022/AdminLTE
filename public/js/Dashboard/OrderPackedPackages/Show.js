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

        let total = packageDetail.order_dispatch_detail_quantities.reduce((total, objeto) => total + objeto.quantity, 0);

        orderPackageDetails += `<div class="col-lg-6">
            <button type="button" class="mb-2 btn w-100 collapsed btn-dark" data-toggle="collapse" data-target="#collapsePackage${i}" aria-expanded="false" aria-controls="#collapsePackage${i}">
                <b>
                    <div class="table-responsive">
                        <span>REF: ${packageDetail.order_detail.product.code.toUpperCase()}-${packageDetail.order_detail.color.code.toUpperCase()}-${packageDetail.order_detail.tone.code.toUpperCase()}</span> | <span class="badge badge-light" id="${packageDetail.order_detail.product.code.toUpperCase()}-${packageDetail.order_detail.color.code.toUpperCase()}-${packageDetail.order_detail.tone.code.toUpperCase()}-CONTAR">${contar}</span> de <span class="badge badge-warning" id="${packageDetail.order_detail.product.code.toUpperCase()}-${packageDetail.order_detail.color.code.toUpperCase()}-${packageDetail.order_detail.tone.code.toUpperCase()}-TOTAL">${total}</span> | <span class="badge badge-${contar == total ? 'success' : 'danger'}" id="${packageDetail.order_detail.product.code.toUpperCase()}-${packageDetail.order_detail.color.code.toUpperCase()}-${packageDetail.order_detail.tone.code.toUpperCase()}-BADGE">${contar == total ? 'Completado' : 'Hace falta'}</span>
                    </div>
                </b>
            </button>
            <div class="table-responsive collapse" id="collapsePackage${i}">
                <div class="col-12">
                    <div class="row mb-2 text-center">
                        <div class="col-12">
                            <input onkeyup="ShowOrderPackageDetail('${packageDetail.order_detail.product.code.toUpperCase()}-${packageDetail.order_detail.color.code.toUpperCase()}-${packageDetail.order_detail.tone.code.toUpperCase()}', event, null, '${packageDetail.order_detail.product.code.toUpperCase()}-${packageDetail.order_detail.color.code.toUpperCase()}-${packageDetail.order_detail.tone.code.toUpperCase()}', ${packageDetails.id}, ${packageDetail.id})" id="${packageDetail.order_detail.product.code.toUpperCase()}-${packageDetail.order_detail.color.code.toUpperCase()}-${packageDetail.order_detail.tone.code.toUpperCase()}" type="text" class="mb-2 w-100 form-control" style="border: 1px solid black !important;" value="">
                        </div>
                    </div>
                    <table id="2-table" class="table text-center">
                        <thead>
                            <tr>
                                <th scope="col">TALLA</th>
                                <th scope="col">CP</th>
                                <th scope="col">CD</th>
                                <th scope="col"><i class="fas fa-info"></i></th>
                            </tr>
                        </thead>
                        <tbody>`;
        $.each(packageDetail.order_dispatch_detail_quantities, function(j, orderDispatchDetailQuantity) {
            if(orderDispatchDetailQuantity.quantity != 0) {

                let countPickingPackage = 0;

                $.each(packageDetails.order_package_details, function(k, orderPackageDetail) {
                    if(orderPackageDetail.order_dispatch_detail_id == packageDetail.id && orderPackageDetail.order_package_id == packageDetails.id) {

                        $.each(orderPackageDetail.order_package_detail_quantities, function(l, orderPackageDetailQuantity) {
                            if(orderPackageDetailQuantity.order_dispatch_detail_quantity.order_detail_quantity.size_id == orderDispatchDetailQuantity.order_detail_quantity.size_id) {
                                countPickingPackage = orderPackageDetailQuantity.quantity;
                                return false;
                            }
                        })

                    }
                })

                orderPackageDetails += `<tr>
                    <th id="${packageDetail.order_detail.product.code.toUpperCase()}-${packageDetail.order_detail.color.code.toUpperCase()}-${packageDetail.order_detail.tone.code.toUpperCase()}-${orderDispatchDetailQuantity.order_detail_quantity.size.code.replace('T', '').toUpperCase()}-DETAIL" data-product_id="${packageDetail.order_detail.product_id}" data-color_id="${packageDetail.order_detail.color_id}" data-tone_id="${packageDetail.order_detail.tone_id}" data-size_id="${orderDispatchDetailQuantity.order_detail_quantity.size_id}" scope="col">${orderDispatchDetailQuantity.order_detail_quantity.size.code}</th>
                    <td id="${packageDetail.order_detail.product.code.toUpperCase()}-${packageDetail.order_detail.color.code.toUpperCase()}-${packageDetail.order_detail.tone.code.toUpperCase()}-${orderDispatchDetailQuantity.order_detail_quantity.size.code.replace('T', '').toUpperCase()}-CP" data-countPickingPackage="${countPickingPackage}">${orderDispatchDetailQuantity.order_packages_details_quantities.reduce((total, objeto) => total + objeto.quantity, 0)}</td>
                    <td id="${packageDetail.order_detail.product.code.toUpperCase()}-${packageDetail.order_detail.color.code.toUpperCase()}-${packageDetail.order_detail.tone.code.toUpperCase()}-${orderDispatchDetailQuantity.order_detail_quantity.size.code.replace('T', '').toUpperCase()}-CD">${orderDispatchDetailQuantity.quantity}</td>
                    <td onclick="DetailOrderPackageDetailModal('reference_d', '${packageDetail.order_detail.product.code.toUpperCase()}-${packageDetail.order_detail.color.code.toUpperCase()}-${packageDetail.order_detail.tone.code.toUpperCase()}-${orderDispatchDetailQuantity.order_detail_quantity.size.code.replace('T', '').toUpperCase()}', ${packageDetails.id}, ${packageDetail.id})"><span class="badge badge-pill badge-info text-white" style="cursor: pointer;"><i class="fas fa-info text-white"></i></span></td>
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

function DetailOrderPackageDetailModal(id, referencia, order_package_id, order_dispatch_detail_id) {
    $(`#${id}`).val(referencia);
    $('#DetailOrderPackageDetailButton').attr('onclick', `DetailOrderPackageDetail('${id}', '${referencia.substring(0, referencia.lastIndexOf('-'))}', ${order_package_id}, ${order_dispatch_detail_id})`)
    $('#DetailOrderPackageDetailModal').modal('show');
}

function DetailOrderPackageDetail(id, referencia, order_package_id, order_dispatch_detail_id) {
    let quantity = parseInt($('#quantity_d').val());
    $('#quantity_d').val('');

    let event = new KeyboardEvent('keyup', {
        key: 'Enter',
        code: 'Enter',
        which: 13,
        keyCode: 13,
        bubbles: true,
        cancelable: true
    });

    if(quantity == '' || quantity == 0 || isNaN(quantity)) {
        toastr.error('La cantidad ingresada no es valida.')
    } else {
        ShowOrderPackageDetail(id, event, quantity, referencia, order_package_id, order_dispatch_detail_id, false);
    }
}

function ShowOrderPackageDetail(id, event, quantity, referencia, order_package_id, order_dispatch_detail_id, status = true) {

    if(event.which == 13){
        let value = $.trim($(`#${id}`).val()).toUpperCase();
        if(status) {
            $(`#${id}`).val('');
        }

        if(value.substring(0, value.lastIndexOf('-')) == referencia) {
            let countPicking = parseInt($(`#${value}-CP`).text());
            let countPickingPackage = parseInt($(`#${value}-CP`).attr('data-countPickingPackage'));
            let countDispatch = parseInt($(`#${value}-CD`).text());
            let countPickingPackageValue = countPickingPackage;

            let count = parseInt($(`#${value.substring(0, value.lastIndexOf('-'))}-CONTAR`).text());
            let total = parseInt($(`#${value.substring(0, value.lastIndexOf('-'))}-TOTAL`).text());
            let badge = $(`#${value.substring(0, value.lastIndexOf('-'))}-BADGE`);

            if(!status && (quantity > countDispatch - (countPicking - countPickingPackage) || quantity > countDispatch)){
                toastr.warning('La cantidad ingresada supera el maximo que puede empacar en este empaque.');
            } else if(isNaN(countPicking) || isNaN(countDispatch)) {
                toastr.error('El codigo ingresado es erroneo. Revisar el valor que arroja el codigo.');
            } else if(status && countPicking == countDispatch) {
                toastr.warning('Las unidades a despachar ya fueron alistadas y empacadas en su totalidad.');
            } else {
                if(status) {
                    count++;
                    countPicking++;
                    countPickingPackage++;
                } else {
                    count = count - countPickingPackage + quantity;
                    countPicking = countPicking - countPickingPackage + quantity;
                    countPickingPackage = quantity;
                }
                
                $(`#${value.substring(0, value.lastIndexOf('-'))}-CONTAR`).text(count);
                $(`#${value}-CP`).text(countPicking);
                $(`#${value}-CP`).attr('data-countPickingPackage', countPickingPackage);
                count == total ? badge.removeClass('badge-danger').addClass('badge-success').text('Completado') : badge.removeClass('badge-success').addClass('badge-danger').text('Hace falta') ;

                $.ajax({
                    url: `/Dashboard/Orders/Packed/Packages/Detail`,
                    type: 'POST',
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'product_id': parseInt($(`#${value}-DETAIL`).attr('data-product_id')),
                        'color_id': parseInt($(`#${value}-DETAIL`).attr('data-color_id')),
                        'tone_id': parseInt($(`#${value}-DETAIL`).attr('data-tone_id')),
                        'size_id': parseInt($(`#${value}-DETAIL`).attr('data-size_id')),
                        'order_package_id': order_package_id, 
                        'order_dispatch_detail_id': order_dispatch_detail_id,
                        'quantity': quantity
                    },
                    success: function(response) {
                        
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        ShowOrderPackageAjaxError(xhr);
                        if(status) {
                            count = count - countPickingPackage + quantity;
                            countPicking = countPicking - countPickingPackage + quantity;
                            countPickingPackage = quantity;
                        } else {
                            count = count - quantity + countPickingPackageValue;
                            countPicking = countPicking - quantity + countPickingPackageValue;
                            countPickingPackage = countPickingPackageValue;
                        }

                        $(`#${value.substring(0, value.lastIndexOf('-'))}-CONTAR`).text(count);
                        $(`#${value}-CP`).text(countPicking);
                        $(`#${value}-CP`).attr('data-countPickingPackage', countPickingPackage);
                        count == total ? badge.removeClass('badge-danger').addClass('badge-success').text('Completado') : badge.removeClass('badge-success').addClass('badge-danger').text('Hace falta') ;
                    }
                });
            }
        } else if (value == '') {
            toastr.error('Debe ingresar un codigo de referencia.');
        } else {
            toastr.warning(`El codigo ingresado fue ${value}, la referencia a alistar y empacar es ${referencia}. Por favor revisar si el codigo ingresado tiene el formato REFERENCIA-COLOR-TONO-TALLA o si la talla es valida.`);
        }
    }
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
