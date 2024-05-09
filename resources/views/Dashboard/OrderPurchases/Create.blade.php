<div class="modal fade" id="CreateOrderPurchaseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Creacion de Orden de compra</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="workshop_id_c">Taller</label>
                    <div class="input-group">
                        <select class="form-control select2" id="workshop_id_c" name="workshop_id_c" style="width: 94%">
                            <option value="">Seleccione</option>
                        </select>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-user-tie"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="purchase_observation_c">Observacion</label>
                    <div class="input-group">
                        <textarea class="form-control" id="purchase_observation_c" name="purchase_observation_c" cols="30" rows="4"></textarea>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-text-size"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="div_payment_types_c">
                    <label for="div_payment_types_c">Metodos de pago</label>
                    <div class="form-group" id="payment_types_c">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Cerrar ventana">
                    <i class="fas fa-xmark"></i>
                </button>
                <button type="button" class="btn btn-primary" id="CreateOrderPurchaseButton" onclick="CreateOrderPurchase()" title="Guardar orden de compra.">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
