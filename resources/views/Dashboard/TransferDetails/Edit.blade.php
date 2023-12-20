<div class="modal" id="EditTransferDetailModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Edicion Detalle de la Transferencia</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="product_id_e">Productos</label>
                        <div class="input-group">
                            <select class="form-control select2" id="product_id_e" name="product_id" style="width: 90%" onchange="EditTransferDetailsModalProductGetColorToneSizes(this)">
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
                        <label for="color_id_tone_id_e">Colores y Tonos</label>
                        <div class="input-group">
                            <select class="form-control select2" id="color_id_tone_id_e" name="color_id_tone_id" style="width: 90%" onchange="EditTransferDetailsModalColorToneSizesGetQuantity()">
                                <option value="">Seleccione</option>
                            </select>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-brush"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="size_id_e">Tallas</label>
                        <div class="input-group">
                            <select class="form-control select2" id="size_id_e" name="size_id" style="width: 90%" onchange="EditTransferDetailsModalColorToneSizesGetQuantity()">
                                <option value="">Seleccione</option>
                            </select>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-arrow-up-9-1"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="quantity_e">Cantidad</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="quantity_e" name="quantity" min="0" max="0">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                            </div>
                        </div>
                        <small id="message_quantity_e"></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Cerrar ventana">
                    <i class="fas fa-xmark"></i>
                </button>
                <button type="button" class="btn btn-primary" id="EditTransferDetailButton" onclick="EditTransferDetail()" title="Actualizar detalle de la transferencia.">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
