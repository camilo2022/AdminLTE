<div class="modal" id="CreateTransferModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Creacion de Transferencia</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="from_warehouse_id_c">Bodega Envia</label>
                        <div class="input-group">
                            <select class="form-control select2" id="from_warehouse_id_c" name="from_warehouse_id" style="width: 90%" onchange="CreateTransfersModalFromWarehoseGetToWarehouse(this)">
                                <option value="">Seleccione</option>
                            </select>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-share-all"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="to_warehouse_id_c">Bodega Recibe</label>
                        <div class="input-group">
                            <select class="form-control select2" id="to_warehouse_id_c" name="to_warehouse_id" style="width: 90%">
                                <option value="">Seleccione</option>
                            </select>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-reply-all"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="from_observation_c">Descripcion</label>
                        <div class="input-group">
                            <textarea class="form-control" id="from_observation_c" name="from_observation" cols="30" rows="4"></textarea>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-text-size"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Cerrar ventana">
                    <i class="fas fa-xmark"></i>
                </button>
                <button type="button" class="btn btn-primary" id="CreateTransferButton" onclick="CreateTransfer()" title="Guardar transferencia.">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
