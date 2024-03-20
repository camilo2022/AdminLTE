<div class="modal fade" id="DetailOrderPackageDetailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Cantidad a alistar y empacar</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="reference_d">Codigo de referencia</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="reference_d" name="reference_d" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-code"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="quantity_d">Cantidad</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="quantity_d" name="quantity_d">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-hashtag"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Cerrar ventana">
                    <i class="fas fa-xmark"></i>
                </button>
                <button type="button" class="btn btn-primary" id="DetailOrderPackageDetailButton" onclick="" title="Guardar cantidad en el empaque.">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
