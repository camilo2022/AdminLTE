<div class="modal fade" id="CreateOrderReturnDetailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Creacion de Detalle del Pedido</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="product_id_c">Productos</label>
                    <div class="input-group">
                        <select class="form-control select2" id="product_id_c" name="product_id" style="width: 90%" onchange="CreateOrderReturnDetailModalProductGetColorTone(this)">
                            <option value="">Seleccione</option>
                        </select>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-bookmark"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="color_id_tone_id_c">Colores y Tonos</label>
                    <div class="input-group">
                        <select class="form-control select2" id="color_id_tone_id_c" name="color_id_tone_id" style="width: 90%" onchange="CreateOrderReturnDetailModalColorToneGetSizesQuantity()">
                            <option value="">Seleccione</option>
                        </select>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-brush"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="div" id="sizes_c">

                </div>
                <div class="form-group">
                    <label for="observation_c">Observacion</label>
                    <div class="input-group">
                        <textarea class="form-control" id="observation_c" name="observation_c" cols="30" rows="3"></textarea>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-text-size"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Cerrar ventana">
                    <i class="fas fa-xmark"></i>
                </button>
                <button type="button" class="btn btn-primary" id="CreateOrderReturnDetailButton" onclick="CreateOrderReturnDetail()" title="Guardar detalle de la orden de devolucion del pedido.">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
