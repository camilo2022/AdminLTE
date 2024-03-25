<div class="modal" id="QuotaClientModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Edicion de Cliente</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name_c_q">Razon social</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="name_c_q" name="name_c_q" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-signature"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="document_number_c_q">Numero de documento</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="document_number_c_q" name="document_number_c_q" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-address-card"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="quota_c_q">Cupo aprobado</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="quota_c_q" name="quota_c_q">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-circle-dollar-to-slot"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Cerrar ventana">
                    <i class="fas fa-xmark"></i>
                </button>
                <button type="button" class="btn btn-primary" id="QuotaClientButton" onclick="" title="Actualizar cliente.">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
