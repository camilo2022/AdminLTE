<div class="modal fade bd-example-modal-lg" id="EditUserModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Edicion de Usuario</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name_e">Nombres</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="name_e" name="name" onblur="Trim(this)">
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
                            <label for="last_name_e">Apellidos</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="last_name_e" name="last_name" onblur="Trim(this)">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-signature"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="document_number_e">Numero de documento</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="document_number_e" name="document_number" onkeypress="Numbers(event)" onblur="Trim(this)">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-address-card"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="phone_number_e">Numero de telefono</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="phone_number_e" name="phone_number" onkeypress="Numbers(event)" onblur="Trim(this)">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="address_e">Direccion</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="address_e" name="address" onblur="Trim(this)">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-location-dot"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="email_e">Correo Electronico</label>
                            <div class="input-group">
                                <input type="email" class="form-control" id="email_e" name="email" onblur="Trim(this)">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="area_id_e">Areas</label>
                            <div class="input-group">
                                <select class="form-control select2" id="area_id_e" name="area_id" style="width: 88%">
                                    <option value="">Seleccione</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-bags-shopping"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="charge_id_e">Cargos</label>
                            <div class="input-group">
                                <select class="form-control select2" id="charge_id_e" name="charge_id" style="width: 88%">
                                    <option value="">Seleccione</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-arrow-up-1-9"></i>
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
                <button type="button" class="btn btn-primary" id="EditUserButton" onclick="" title="Actualizar usuario">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
