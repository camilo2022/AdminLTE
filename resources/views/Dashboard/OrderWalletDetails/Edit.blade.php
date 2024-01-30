<div class="modal" id="EditOrderWalletDetailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Edicion de Detalle del Pedido</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="product_id_e">Productos</label>
                    <div class="input-group">
                        <select class="form-control select2" id="product_id_e" name="product_id" style="width: 90%" onchange="EditOrderWalletDetailModalProductGetColorTone(this)">
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
                        <select class="form-control select2" id="color_id_tone_id_e" name="color_id_tone_id" style="width: 90%" onchange="EditOrderWalletDetailModalColorToneGetSizesQuantity()">
                            <option value="">Seleccione</option>
                        </select>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-brush"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="div" id="sizes_e">

                </div>
                <div class="form-group">
                    <label for="seller_observation_e">Observacion</label>
                    <div class="input-group">
                        <textarea class="form-control" id="seller_observation_e" name="seller_observation_e" cols="30" rows="3"></textarea>
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
                <button type="button" class="btn btn-primary" id="EditOrderWalletDetailButton" onclick="" title="Actualizar pedido.">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
