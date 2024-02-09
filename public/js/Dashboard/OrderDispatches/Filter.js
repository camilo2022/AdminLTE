let references = [];
let sizes = [];

ReferencesOrderDispatch();

function ReferencesOrderDispatch(position = 0) {
    $.ajax({
        url: `/Dashboard/Orders/Dispatch/Filter/Query/References`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function(response) {
            references = response.data;
            FilterOrderDispatchAjaxSuccess(response);
            InventoriesAndOrderDetailsOrderDispatch(position);
        },
        error: function(xhr, textStatus, errorThrown) {
            FilterOrderDispatchAjaxError(xhr);
        }
    });
}

function InventoriesAndOrderDetailsOrderDispatch(position) {
    let reference = references[position];
    $('#Reference').text(reference.reference);
    $('#Reference').attr('product_id', reference.product_id);
    $('#Reference').attr('color_id', reference.color_id);
    $('#Reference').attr('tone_id', reference.tone_id);
    $.ajax({
        url: `/Dashboard/Orders/Dispatch/Filter/Query/Orders`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'product_id': reference.product_id,
            'color_id': reference.color_id,
            'tone_id': reference.tone_id
        },
        success: function(response) {
            sizes = response.data.sizes;
            FilterOrderDispatchAjaxSuccess(response);
            $('#PositionReference').text(parseInt(position) + 1);
            $('#QuantityReference').text(references.length);
            InventoriesOrderDispatch(response.data.warehouseDiscount, response.data.warehouseNoDiscount, response.data.sizes);
            OrdersDetailsOrderDispatch(response.data.ordersDetails, response.data.sizes);
        },
        error: function(xhr, textStatus, errorThrown) {
            FilterOrderDispatchAjaxError(xhr);
        }
    });
}

function InventoriesOrderDispatch(warehouseDiscount, warehouseNoDiscount, sizes) {
    $('#InventoryHead').empty();
    $('#InventoryBodyWarehouseNoDiscount').empty();
    $('#InventoryBodyWarehouseDiscount').empty();
    $('#InventoryFootQuentityDiscount').empty();

    let headColumnsSizes = '';
    $.each(sizes, function(index, size) {
        headColumnsSizes += `<th class="text-center">${size.code}</th>`;
    })

    let headInventory = `<tr>
            <th>BODEGA</th>
            <th>CODIGO</th>
            ${headColumnsSizes}
            <th class="text-center">TOTAL</th>
        </tr>`;
    
    let bodyWarehouseNoDiscountColumnsSizes = '';
    let sumWarehouseNoDiscountColumnsSizes = 0;
    $.each(sizes, function(index, size) {
        bodyWarehouseNoDiscountColumnsSizes += `<td class="text-center"><b>${warehouseNoDiscount.quantities[size.id]}</b></td>`;
        sumWarehouseNoDiscountColumnsSizes += warehouseNoDiscount.quantities[size.id];
    })

    let trBodyWarehouseNoDiscount = `<td><b>${warehouseNoDiscount.name}</b></td>
        <td><b>${warehouseNoDiscount.code}</b></td>
        ${bodyWarehouseNoDiscountColumnsSizes}
        <td class="text-center"><b>${sumWarehouseNoDiscountColumnsSizes}</b></td>`;
    
    let bodyWarehouseDiscountColumnsSizes = '';
    let sumWarehouseDiscountColumnsSizes = 0;
    $.each(sizes, function(index, size) {
        bodyWarehouseDiscountColumnsSizes += `<td class="text-center"><b>${warehouseDiscount.quantities[size.id]}</b></td>`;
        sumWarehouseDiscountColumnsSizes += warehouseDiscount.quantities[size.id];
    })

    let trBodyWarehouseDiscount = `<td><b>${warehouseDiscount.name}</b></td>
        <td><b>${warehouseDiscount.code}</b></td>
        ${bodyWarehouseDiscountColumnsSizes}
        <td class="text-center"><b>${sumWarehouseDiscountColumnsSizes}</b></td>`;

    let footQuantityDiscountColumnsSizes = '';
    let sumQuantityDiscountColumnsSizes = 0;
    $.each(sizes, function(index, size) {
        footQuantityDiscountColumnsSizes += `<th class="text-center"><input type="number" class="form-control filterInputNumber text-white" id="res_t${size.id}" value="${warehouseDiscount.quantities[size.id]}" data-size_id="${size.id}" readonly></th>`;
        sumQuantityDiscountColumnsSizes += warehouseDiscount.quantities[size.id];
    })

    let trFootQuantityDiscount = `<th><b>UNIDADES RESTANTES</b></th><th>-</th>
        ${footQuantityDiscountColumnsSizes}
        <th class="text-center"><input type="number" class="form-control filterInputNumber text-white" id="res_total" value="${sumQuantityDiscountColumnsSizes}" readonly></th>`;

    $('#InventoryHead').html(headInventory);
    $('#InventoryBodyWarehouseNoDiscount').html(trBodyWarehouseNoDiscount);
    $('#InventoryBodyWarehouseDiscount').html(trBodyWarehouseDiscount);
    $('#InventoryFootQuentityDiscount').html(trFootQuantityDiscount);
    
}

function OrdersDetailsOrderDispatch(ordersDetails, sizes) {
    $('#OrdersReferenceHead').empty();
    
    let headColumnsSizes = '';
    $.each(sizes, function(index, size) {
        headColumnsSizes += `<th class="text-center">${size.code}</th>`;
    })

    let headOrdersDetails = `<tr>
            <th>OD</th>
            <th>PED</th>
            <th>CLIENTE</th>
            <th>DIRECCION</th>
            <th>OBSERVACIONES</th>
            ${headColumnsSizes}
            <th class="text-center">TOTAL</th>
        </tr>`;
    
    let bodyOrdersDetails = '';
    $.each(ordersDetails, function(index, ordersDetail) {

        let bodyColumnsSizes = '';
        let sumColumnsSizes = 0;
        $.each(sizes, function(index, size) {
            bodyColumnsSizes += `<td><input type="number" class="form-control filterInputNumber" id="${ordersDetail.id}_t${size.id}" value="-${ordersDetail.quantities[size.id].quantity}" data-size_id="${size.id}" data-quantity="${ordersDetail.quantities[size.id].quantity}" data-detail="${ordersDetail.id}" onkeyup="" onblur="ResetInputValueOrderDispatch('${ordersDetail.id}_t${size.id}')"></td>`;
            sumColumnsSizes += ordersDetail.quantities[size.id].quantity;
        })
        
        bodyOrdersDetails += `<tr>
                <td>   
                    <div class="icheck-primary">
                        <input type="checkbox" id="${ordersDetail.id}"><label for="${ordersDetail.id}"></label>
                    </div>
                </td>
                <td>${ordersDetail.order.id}</td>
                <td style="font-size: 14px;">${ordersDetail.order.client.name} | ${ordersDetail.order.client_branch.name}-${ordersDetail.order.client_branch.code}</td>
                <td style="font-size: 14px;">${ordersDetail.order.client_branch.departament.name} - ${ordersDetail.order.client_branch.city.name} - ${ordersDetail.order.client_branch.neighborhood} - ${ordersDetail.order.client_branch.address}</td>
                <td style="font-size: 14px;">${ordersDetail.order.seller_observation ?? ''} | ${ordersDetail.order.wallet_observation ?? ''} | ${ordersDetail.seller_observation ?? ''}</td>
                ${bodyColumnsSizes}
                <td><input type="number" class="form-control filterInputNumber" id="${ordersDetail.id}_total" value="-${sumColumnsSizes}" readonly></td>
            </tr>`;
    })

    let footColumnsSizes = '';
    $.each(sizes, function(index, size) {
        footColumnsSizes += `<th class="text-center"><input type="number" class="form-control filterInputNumber text-white" id="sum_t${size.id}" value="0" data-size_id="${size.id}" readonly></th>`;
    })

    let footOrdersDetails = `<th>   
            <div class="icheck-primary">
                <input type="checkbox" id="0"><label for="0"></label>
            </div>
        </th><th>-</th><th>-</th><th>-</th><th>-</th>
        ${footColumnsSizes}
        <th class="text-center"><input type="number" class="form-control filterInputNumber text-white" id="sum_total" value="0" readonly></th>`;
    
    $('#OrdersReferenceHead').html(headOrdersDetails);
    $('#OrdersReferenceBody').html(bodyOrdersDetails);
    $('#OrdersReferenceFoot').html(footOrdersDetails);
}

function ResetInputValueOrderDispatch(id) {
    if($(`#${id}`).val() == '') {
        let detail = $(`#${id}`).attr('data-detail');
        let quantity = $(`#${id}`).attr('data-quantity');
        $(`#${id}`).val(parseInt(quantity) * -1);

        let quantities = 0;
        $.each(sizes, function(index, size) {
            quantities += parseInt($(`#${detail}_t${size.id}`).val());
        })
    
        $(`#${detail}_total`).val(quantities);
    }
}

function FilterOrderDispatchAjaxSuccess(response) {
    if(response.status === 201) {
        toastr.success(response.message);
    }
}

function FilterOrderDispatchAjaxError(xhr) {
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
