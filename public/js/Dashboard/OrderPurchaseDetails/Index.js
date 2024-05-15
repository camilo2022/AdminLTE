$('#IndexOrderSellerDetail').trigger('click');

function IndexOrderSellerDetail(order_id) {
    $.ajax({
        url: `/Dashboard/Orders/Seller/Details/Index/Query`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'order_id': order_id
        },
        success: function(response) {
            IndexOrderSellerDetailModalCleaned(response.data.orderDetails, response.data.sizes);
            IndexOrderSellerDetailAjaxSuccess(response);
        },
        error: function(xhr, textStatus, errorThrown) {
            IndexOrderSellerDetailAjaxError(xhr);
        }
    });
}

function IndexOrderSellerDetailModalCleaned(details, sizes) {
    $('#OrderSellerDetailHead').html('');
    $('#OrderSellerDetailBody').html('');

    let columns = '';

    $.each(sizes, function(index, size) {
        columns += `<th>${size.code}</th>`;
    });

    let head = `<tr>
                    <th>#</th>
                    <th>Acciones</th>
                    <th>Precio Total</th>
                    <th>Referencia</th>
                    <th>Color</th>
                    <th>Tono</th>
                    ${columns}
                    <th>Total</th>
                    <th>Observacion</th>
                    <th>Estado</th>
                </tr>`;

    let foot = `<tr>
                    <th>#</th>
                    <th>-</th>`;

    let totalSum = 0;
    let quantitySum = 0;

    let body = '';

    $.each(details, function(index, detail) {
        body += `<tr>
                    <td>${detail.id}</td>`;
        let btn = '';

        switch (detail.status) {
            case 'Pendiente':
                btn += `<a onclick="EditOrderSellerDetailModal(${detail.id})" type="button"
                class="btn btn-primary btn-sm mr-2" title="Editar detalle de pedido.">
                    <i class="fas fa-pen text-white"></i>
                </a>`;

                btn += `<a onclick="CancelOrderSellerDetail(${detail.id})" type="button"
                class="btn btn-warning btn-sm mr-2" title="Cancelar detalle de pedido.">
                    <i class="fas fa-xmark text-white"></i>
                </a>`;
                break;
            case 'Cancelado':
                btn += `<a onclick="PendingOrderSellerDetail(${detail.id})" type="button"
                class="btn btn-info btn-sm mr-2" title="Pendiente detalle de pedido.">
                    <i class="fas fa-arrows-rotate text-white"></i>
                </a>`;
                break;
            default:
                btn += ``;
                break;
        };

        body += `<td><div class="text-center">${detail.order.seller_status == 'Pendiente' && detail.order.wallet_status == 'Pendiente' && detail.order.dispatched_status == 'Pendiente' ? btn : ''}</div></td>`;

        let quantities = 0;

        $.each(detail.quantities, function(index, quantity) {
            quantities += quantity.quantity;
        });

        totalSum += quantities * detail.price;
        quantitySum += quantities;

        body += `<td>${(quantities * detail.price).toLocaleString('es-CO', { style: 'currency', currency: 'COP' })} COP</td>
                <td>${detail.product.code}</td>
                <td>${detail.color.name + ' - ' + detail.color.code}</td>
                <td>${detail.tone.name + ' - ' + detail.tone.code}</td>`;

        $.each(sizes, function(index, size) {
            body += `<td>${detail.quantities[size.id].quantity}</td>`;
        });

        body += `<td>${quantities}</td>
            <td>${detail.seller_observation == null ? '' : detail.seller_observation}</td>`;

        switch (detail.status) {
            case 'Pendiente':
                body += `<td><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></td>`;
                break;
            case 'Cancelado':
                body += `<td><span class="badge badge-pill badge-warning text-white"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span></td>`;
                break;
            case 'Revision':
                body += `<td><span class="badge badge-pill"><i class="fas fa-magnifying-glass mr-2"></i>Revision</span></td>`;
                break;
            case 'Aprobado':
                body += `<td><span class="badge badge-pill badge-success"><i class="fas fa-check mr-2"></i>Aprobado</span></td>`;
                break;
            case 'Agotado':
                body += `<td><span class="badge badge-pill bg-orange" style="color:white !important;"><i class="fas fa-hourglass-half mr-2 text-white"></i>Agotado</span></td>`;
                break;
            case 'Rechazado':
                body += `<td><span class="badge badge-pill badge-danger text-white"><i class="fas fa-ban mr-2 text-white"></i>Rechazado</span></td>`;
                break;
            case 'Filtrado':
                body += `<td><span class="badge badge-pill bg-purple" style="color:white !important;"><i class="fas fa-filter mr-2 text-white"></i>Filtrado</span></td>`;
                break;
            case 'Empacado':
                body += `<td><span class="badge badge-pill bg-gray" style="color:white !important;"><i class="fas fa-box mr-2 text-white"></i>Empacado</span></td>`;
                break;
            case 'Despachado':
                body += `<td><span class="badge badge-pill badge-primary"><i class="fas fa-share mr-2 text-white"></i>Despachado</span></td>`;
                break;
            case 'Parcialmente Devuelto':
                body += `<td><span class="badge badge-pill text-white" style="background:saddlebrown !important;"><i class="fas fa-reply mr-2 text-white"></i>Parcialmente Devuelto</span></td>`;
                break;
            case 'Devuelto':
                body += `<td><span class="badge badge-pill bg-dark text-white"><i class="fas fa-reply-all mr-2 text-white"></i>Devuelto</span></td>`;
                break;
            default:
                body += `<td><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></td>`;
                break;
        };

        body += `</tr>`;
    });

    foot += `<th>${totalSum.toLocaleString('es-CO', { style: 'currency', currency: 'COP' })} COP</th>
            <th>-</th>
            <th>-</th>
            <th>-</th>`;

    $.each(sizes, function(index, size) {
        let sizeSum = 0;
        $.each(details, function(i, detail) {
            sizeSum += detail.quantities[size.id].quantity;
        });
        foot += `<th>${sizeSum}</th>`;
    });

    foot += `<th>${quantitySum}</th>
            <th>-</th>
            <th>-</th>
        </tr>`;

    $('#OrderSellerDetailHead').html(head);
    $('#OrderSellerDetailBody').html(body);
    $('#OrderSellerDetailFoot').html(foot);
}

function IndexOrderSellerDetailAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.info(response.message);
    }
}

function IndexOrderSellerDetailAjaxError(xhr) {
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
