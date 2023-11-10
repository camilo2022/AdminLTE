<div class="modal" id="EditModelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Edicion de Modelo de producto</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="name">Nombre</label>
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
                        <label for="code">Codigo</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="code_e" name="code">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-code"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="description">Descripcion</label>
                        <div class="input-group">
                            <textarea class="form-control" id="description_e" name="description" cols="30" rows="3"></textarea>
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
                <button type="button" class="btn btn-primary" id="EditModelButton" onclick="" title="Actualizar modelo de producto.">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>