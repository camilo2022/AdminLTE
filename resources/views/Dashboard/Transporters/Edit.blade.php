<div class="modal fade" id="EditTransporterModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Edicion de Transportadoras</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="name_e">Razon social</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="name_e" name="name">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-signature"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="document_number_e">Numero de documento</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="document_number_e" name="document_number">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-address-card"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="telephone_number_e">Numero de telefono</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="telephone_number_e" name="telephone_number">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-phone"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="email_e">Correo electronico</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="email_e" name="email">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
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
                <button type="button" class="btn btn-primary" id="EditTransporterButton" onclick="" title="Actualizar transportadora.">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
