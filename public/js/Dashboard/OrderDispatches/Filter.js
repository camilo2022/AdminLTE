let references = [];

ReferencesOrderDispatch(0);

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
        footQuantityDiscountColumnsSizes += `<th class="text-center"><b id="res_t${size.code.replace(/\s+/g, '')}">${warehouseDiscount.quantities[size.id]}</b></th>`;
        sumQuantityDiscountColumnsSizes += warehouseDiscount.quantities[size.id];
    })

    let trFootQuantityDiscount = `<th><b>UNIDADES RESTANTES</b></th><th>-</th>
        ${footQuantityDiscountColumnsSizes}
        <th class="text-center"><b id="res_total">${sumQuantityDiscountColumnsSizes}</b></th>`;

    $('#InventoryHead').html(headInventory);
    $('#InventoryBodyWarehouseNoDiscount').html(trBodyWarehouseNoDiscount);
    $('#InventoryBodyWarehouseDiscount').html(trBodyWarehouseDiscount);
    $('#InventoryFootQuentityDiscount').html(trFootQuantityDiscount);
    
}

function OrdersDetailsOrderDispatch(ordersDetails, sizes) {
    console.log(ordersDetails);
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
            bodyColumnsSizes += `<td><input type="number" class="form-control" id="${ordersDetail.id}_t${size.code.replace(/\s+/g, '')}" value="-${ordersDetail.quantities[size.id].quantity}" onkeyup="" style="border: 0; box-shadow: none; width: 100%;"></td>`;
            sumColumnsSizes += ordersDetail.quantities[size.id].quantity;
        })

        let row = `<tr>
                <td>   
                    <div class="icheck-primary">
                        <input type="checkbox" id="${ordersDetail.id}"><label for="${ordersDetail.id}"></label>
                    </div>
                </td>
                <td>${ordersDetail.order.id}</td>
                <td style="font-size: 13px;">${ordersDetail.order.client.name} | ${ordersDetail.order.client_branch.name}-${ordersDetail.order.client_branch.code}</td>
                <td style="font-size: 13px;">${ordersDetail.order.client_branch.departament.name} - ${ordersDetail.order.client_branch.city.name} - ${ordersDetail.order.client_branch.neighborhood} - ${ordersDetail.order.client_branch.address}</td>
                <td style="font-size: 13px;">${ordersDetail.order.seller_observation ?? ''} | ${ordersDetail.order.wallet_observation ?? ''} | ${ordersDetail.seller_observation ?? ''}</td>
                ${bodyColumnsSizes}
                <td><input type="number" class="form-control" id="${ordersDetail.id}_total" value="-${sumColumnsSizes}" onkeyup="" style="border: 0; box-shadow: none; width: 100%; background: transparent;" readonly></td>
            </tr>`;

        bodyOrdersDetails += row;
    })
    
    $('#OrdersReferenceHead').html(headOrdersDetails);
    $('#OrdersReferenceBody').html(bodyOrdersDetails);
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
