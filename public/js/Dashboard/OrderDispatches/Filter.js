let inventories = [];
ReferencesOrderDispatch();

function ReferencesOrderDispatch() {
    $.ajax({
        url: `/Dashboard/Orders/Dispatch/Filter/Query/Details`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'order_id': $('#IndexOrderDispatchDetail').attr('data-id')
        },
        success: function(response) {
            FilterOrderDispatchAjaxSuccess(response);
            OrdersDetailsOrderDispatch(response.data.orderDetails, response.data.sizes);
        },
        error: function(xhr, textStatus, errorThrown) {
            FilterOrderDispatchAjaxError(xhr);
        }
    });
}

function OrdersDetailsOrderDispatch(ordersDetails, sizes) {
    $('#OrderDispatchDetailHead').empty();
    $('#OrderDispatchDetailBody').empty();

    let headColumnsSizes = '';
    $.each(sizes, function(index, size) {
        headColumnsSizes += `<th class="text-center">${size.code}</th>`;
    })

    let headOrdersDetails = `<tr>
            <th>#</th>
            <th>Referencia</th>
            <th>Color</th>
            <th>Tono</th>
            ${headColumnsSizes}
            <th class="text-center">Total</th>
            <th>Observacion</th>
        </tr>`;

    let bodyOrdersDetails = '';
    
    $.each(ordersDetails, function(index, orderDetail) {

        InventoriesOrderDispatch(orderDetail.product.id, orderDetail.color.id, orderDetail.tone.id, sizes);

        let bodyColumnsSizes = '';
        let bodyColumnsInventories = '';
        let sumColumnsSizes = 0;
        let sumColumnsInventories = 0;
        
        $.each(sizes, function(index, size) {
            console.log(inventories);
            bodyColumnsSizes += `<td><input type="number" class="form-control filterInputNumber" id="detail_${orderDetail.id}_t${size.id}" value="-${orderDetail.quantities[size.id].quantity}" data-size_id="${size.id}" data-quantity="${orderDetail.quantities[size.id].quantity}" data-detail="${orderDetail.id}" onkeyup="" onblur="ResetInputValueOrderDispatch('detail_${orderDetail.id}_t${size.id}')"></td>`;
            bodyColumnsInventories += `<td><input type="number" class="form-control filterInputNumber" id="inventory_${orderDetail.id}_t${size.id}" value="${inventories[size.id].quantity}" data-size_id="${size.id}" data-quantity="${inventories[size.id].quantity}" onblur="ResetInputValueOrderDispatch('inventory_${orderDetail.id}_t${size.id}')" readonly></td>`;
            sumColumnsSizes += orderDetail.quantities[size.id].quantity;
            sumColumnsInventories += inventories[size.id].quantity;
        })
        bodyColumnsSizes += `<td><input type="number" class="form-control filterInputNumber" id="detail_${orderDetail.id}_total" value="-${sumColumnsSizes}" readonly></td>`;
        bodyColumnsInventories += `<td><input type="number" class="form-control filterInputNumber" id="inventory_${orderDetail.id}_total" value="${sumColumnsInventories}" readonly></td>`;

        bodyOrdersDetails += `<tr class="row-${index}"  onmouseenter="CssRowOrderDispatch(true, 'row-${index}')" onmouseleave="CssRowOrderDispatch(false, 'row-${index}')">
                <td rowspan="2" style="vertical-align: middle;">   
                    <div class="icheck-primary">
                        <input type="checkbox" id="${orderDetail.id}"><label for="${orderDetail.id}"></label>
                    </div>
                </td>
                <td rowspan="2" style="vertical-align: middle;">${orderDetail.product.code}</td>
                <td rowspan="2" style="vertical-align: middle;">${orderDetail.color.name + '-' + orderDetail.color.code}</td>
                <td rowspan="2" style="vertical-align: middle;">${orderDetail.tone.name + '-' + orderDetail.tone.code}</td>
                ${bodyColumnsSizes}
                <td rowspan="2" style="vertical-align: middle;">${orderDetail.seller_observation ?? ''}</td>
                <td hidden></td>
            </tr>
            <tr class="row-${index}"  onmouseenter="CssRowOrderDispatch(true, 'row-${index}')" onmouseleave="CssRowOrderDispatch(false, 'row-${index}')">
                <td hidden></td>
                <td hidden></td>
                <td hidden></td>
                <td hidden></td>
                ${bodyColumnsInventories}
                <td hidden></td>
                <td hidden></td>
            </tr>`;
    })

    $('#OrderDispatchDetailHead').html(headOrdersDetails);
    $('#OrderDispatchDetailBody').html(bodyOrdersDetails);
}

function CssRowOrderDispatch(boolean, className) {
    $(`.${className}`).css({'background': boolean ? '#f2f2f2' : '#fff'});
}

function InventoriesOrderDispatch(product_id, color_id, tone_id, size_ids) {
    $.ajax({
        url: `/Dashboard/Orders/Dispatch/Filter/Query/Inventories`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'product_id': product_id,
            'color_id': color_id,
            'tone_id': tone_id,
            'size_ids': size_ids
        },
        success: function(response) {
            inventories = response.data;
            console.log(inventories)
        },
        error: function(xhr, textStatus, errorThrown) {
            FilterOrderDispatchAjaxError(xhr);
        }
    });
}

function ResetInputValueOrderDispatch(id, type) {
    if($(`#${id}`).val() == '') {
        let detail = $(`#${id}`).attr(`data-${type}`);
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