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


    $.each(ordersDetails, function(index, orderDetail) {

        InventoriesOrderDispatch(orderDetail, sizes, index);

    })

    $('#OrderDispatchDetailHead').html(headOrdersDetails);
}

function CssRowOrderDispatch(boolean, className) {
    $(`.${className}`).css({'background': boolean ? '#f2f2f2' : '#fff'});
}

function InventoriesOrderDispatch(orderDetail, sizes, index) {
    $.ajax({
        url: `/Dashboard/Orders/Dispatch/Filter/Query/Inventories`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'product_id': orderDetail.product_id,
            'color_id': orderDetail.color_id,
            'tone_id': orderDetail.tone_id,
            'size_ids': sizes
        },
        success: function(response) {
            FilterOrderDispatchAjaxSuccess(response);
            OrdersDetailsRowsOrderDispatch(orderDetail, sizes, response.data, index);
        },
        error: function(xhr, textStatus, errorThrown) {
            FilterOrderDispatchAjaxError(xhr);
        }
    });
}

function OrdersDetailsRowsOrderDispatch(orderDetail, sizes, inventories, index) {
    let bodyOrdersDetails = '';

    let bodyColumnsSizes = '';
    let bodyColumnsInventories = '';
    let sumColumnsSizes = 0;
    let sumColumnsInventories = 0;

    $.each(sizes, function(index, size) {
        bodyColumnsSizes += `<td><input type="number" class="form-control filterInputNumber details" id="detail_${orderDetail.id}_t${size.id}" value="-${orderDetail.quantities[size.id].quantity}" data-size_id="${size.id}" data-quantity="-${orderDetail.quantities[size.id].quantity}" data-id="${orderDetail.quantities[size.id].id}" onkeyup="SubtractInputValueOrderDispatch('detail_${orderDetail.id}_t${size.id}', 'inventory_${orderDetail.id}_t${size.id}')" onblur="ResetInputValueOrderDispatch('detail_${orderDetail.id}_t${size.id}', 'inventory_${orderDetail.id}_t${size.id}', ${orderDetail.id}, ${JSON.stringify(sizes).replace(/"/g, "'")})"></td>`;
        bodyColumnsInventories += `<td><input type="number" class="form-control filterInputNumber" id="inventory_${orderDetail.id}_t${size.id}" value="${inventories[size.id].quantity}" data-size_id="${size.id}" data-quantity="${inventories[size.id].quantity}" readonly></td>`;
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

    $('#OrderDispatchDetailBody').append(bodyOrdersDetails);
}

function ResetInputValueOrderDispatch(quantity, inventory, id, sizes) {
    let valueQuantity = parseInt($(`#${quantity}`).val());
    let valueInventory = parseInt($(`#${inventory}`).val());

    if (valueQuantity >= 0) {
        $(`#${quantity}`).val(valueQuantity * -1).trigger('onkeyup');
    }

    if (valueQuantity === '-' || isNaN(valueQuantity) || valueQuantity === '') {
        $(`#${quantity}`).val($(`#${quantity}`).attr(`data-quantity`));

        let quantities = 0;
        $.each(sizes, function(index, size) {
            quantities += parseInt($(`#detail_${id}_t${size.id}`).val());
        })

        $(`#detail_${id}_total`).val(quantities);
    }

    if (valueQuantity === '-' || isNaN(valueQuantity) || valueQuantity === '') {
        $(`#${inventory}`).val($(`#${inventory}`).attr(`data-quantity`));

        let inventories = 0;
        $.each(sizes, function(index, size) {
            inventories += parseInt($(`#inventory_${id}_t${size.id}`).val());
        })

        $(`#inventory_${id}_total`).val(inventories);
    }
}

function SubtractInputValueOrderDispatch(quantity, inventory) {
    let valueQuantity = parseInt($(`#${quantity}`).val());
    let dataQuantity = parseInt($(`#${quantity}`).attr('data-quantity'));

    let valueInventory = parseInt($(`#${inventory}`).val());
    let dataInventory = parseInt($(`#${inventory}`).attr('data-quantity'));


    if (valueQuantity === '-' || isNaN(valueQuantity) || valueQuantity === '') {
        $(`#${inventory}`).val(dataInventory);
    } else {
        let difference = valueQuantity - (dataQuantity * -1);
        $(`#${inventory}`).val(difference);
    }
}

function GetDataFilterOrderDetailsOrderDispatch() {
    return $('#orderDetailsDispatch tbody tr').map(function() {
        if ($(this).find('input[type="checkbox"]').prop('checked')) {
            let quantities = $(this).find('.details').map(function() {
                return {
                    id: $(this).attr('data-id'),
                    quantity: $(this).val()
                };
            }).get();

            return {
                id: $(this).find('input[type="checkbox"]').attr('id'),
                quantities: quantities
            };
        }
    }).get();
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
