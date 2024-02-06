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
            InventoriesOrderDispatch(response.data.warehouseDiscount, response.data.warehouseNoDiscount, response.data.sizes)
        },
        error: function(xhr, textStatus, errorThrown) {
            FilterOrderDispatchAjaxError(xhr);
        }
    });
}

function InventoriesOrderDispatch(warehouseDiscount, warehouseNoDiscount, sizes) {
    $('#InventoryHead').empty();
    $('#InventoryBodyWarehouseNoDiscount').empty();

    let headColumnsSizes = '';
    $.each(sizes, function(index, size) {
        headColumnsSizes += `<th>${size.code}</th>`;
    })

    let headInventory = `<tr>
            <th>BODEGA</th>
            <th>CODIGO</th>
            ${headColumnsSizes}
            <th>TOTAL</th>
        </tr>`;
    
    let bodyWarehouseNoDiscountColumnsSizes = '';
    let sumWarehouseNoDiscountColumnsSizes = 0;
    $.each(sizes, function(index, size) {
        bodyWarehouseNoDiscountColumnsSizes += `<td>${warehouseNoDiscount.quantites[size.id]}</td>`;
        sumWarehouseNoDiscountColumnsSizes += warehouseNoDiscount.quantites[size.id];
    })

    let trBodyWarehouseNoDiscount = `<td>${warehouseNoDiscount.name}</td>
        <td>${warehouseNoDiscount.code}</td>
        ${bodyWarehouseNoDiscountColumnsSizes}
        <td>${sumWarehouseNoDiscountColumnsSizes}</td>`;
    
        
    
    let bodyWarehouseDiscountColumnsSizes = '';
    let sumWarehouseDiscountColumnsSizes = 0;
    $.each(sizes, function(index, size) {
        bodyWarehouseDiscountColumnsSizes += `<td>${warehouseDiscount.quantites[size.id]}</td>`;
        sumWarehouseDiscountColumnsSizes += warehouseDiscount.quantites[size.id];
    })

    let trBodyWarehouseDiscount = `<td>${warehouseDiscount.name}</td>
        <td>${warehouseDiscount.code}</td>
        ${bodyWarehouseDiscountColumnsSizes}
        <td>${sumWarehouseDiscountColumnsSizes}</td>`;

    $('#InventoryHead').html(headInventory);
    $('#InventoryBodyWarehouseNoDiscount').html(trBodyWarehouseNoDiscount);
    $('#InventoryBodyWarehouseDiscount').html(trBodyWarehouseDiscount);
    
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
