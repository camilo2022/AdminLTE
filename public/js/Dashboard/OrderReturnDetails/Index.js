$('#IndexOrderReturnDetail').trigger('click');

function IndexOrderReturnDetail(order_return_id) {
    $.ajax({
        url: `/Dashboard/Orders/Return/Details/Index/Query`,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'order_return_id': order_return_id
        },
        success: function(response) {
            IndexOrderReturnDetailModalCleaned(response.data.orderReturn, response.data.orderReturnDetails, response.data.sizes);
            IndexOrderReturnDetailAjaxSuccess(response);
        },
        error: function(xhr, textStatus, errorThrown) {
            IndexOrderReturnDetailAjaxError(xhr);
        }
    });
}

function IndexOrderReturnDetailModalCleaned(order, details, sizes) {
    $('#OrderReturnDetailHead').html('');
    $('#OrderReturnDetailBody').html('');

    let columns = '';

    $.each(sizes, function(index, size) {
        columns += `<th>${size.code}</th>`;
    });

    let head = `<tr>
                    <th>#</th>
                    <th>Acciones</th>
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
                btn += `<a onclick="EditOrderReturnDetailModal(${detail.id})" type="button"
                class="btn btn-primary btn-sm mr-2" title="Editar detalle de pedido.">
                    <i class="fas fa-pen text-white"></i>
                </a>`;

                btn += `<a onclick="CancelOrderReturnDetail(${detail.id})" type="button"
                class="btn btn-warning btn-sm mr-2" title="Cancelar detalle de pedido.">
                    <i class="fas fa-xmark text-white"></i>
                </a>`;
                break;
            default:
                btn += ``;
                break;
        };

        body += `<td><div class="text-center">${detail.order_return.return_status == 'Pendiente' ? btn : ''}</div></td>`;

        let quantities = 0;

        $.each(detail.quantities, function(index, quantity) {
            quantities += quantity.quantity;
        });

        totalSum += quantities * detail.price;
        quantitySum += quantities;

        body += `<td>${detail.product.code}</td>
                <td>${detail.color.name + ' - ' + detail.color.code}</td>
                <td>${detail.tone.name + ' - ' + detail.tone.code}</td>`;

        $.each(sizes, function(index, size) {
            body += `<td>${detail.quantities[size.id].quantity}</td>`;
        });

        body += `<td>${quantities}</td>
            <td>${detail.return_observation == null ? '' : detail.return_observation}</td>`;

        switch (detail.status) {
            case 'Pendiente':
                body += `<td><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></td>`;
                break;
            case 'Cancelado':
                body += `<td><span class="badge badge-pill badge-danger text-white"><i class="fas fa-xmark mr-2 text-white"></i>Cancelado</span></td>`;
                break;
            case 'Aprobado':
                body += `<td><span class="badge badge-pill badge-success"><i class="fas fa-check mr-2"></i>Aprobado</span></td>`;
                break;
            default:
                body += `<td><span class="badge badge-pill badge-info"><i class="fas fa-arrows-rotate mr-2"></i>Pendiente</span></td>`;
                break;
        };

        body += `</tr>`;
    });

    foot += `<th>-</th>
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

    $('#OrderReturnDetailHead').html(head);
    $('#OrderReturnDetailBody').html(body);
    $('#OrderReturnDetailFoot').html(foot);
}

function IndexOrderReturnDetailAjaxSuccess(response) {
    if(response.status === 204) {
        toastr.info(response.message);
    }
}

function IndexOrderReturnDetailAjaxError(xhr) {
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
