<div class="modal fade bd-example-modal-lg" id="CreateUserModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Creacion de Usuario</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name">Nombres</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="name_c" name="name">
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
                            <label for="last_name">Apellidos</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="last_name_c" name="last_name">
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
                            <label for="document_number_c">Numero de documento</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="document_number_c" name="document_number" onkeypress="Numbers(this)">
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
                            <label for="phone_number_c">Numero de telefono</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="phone_number_c" name="phone_number" onkeypress="Numbers(this)">
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
                            <label for="address_c">Direccion</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="address_c" name="address">
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
                            <label for="email_c">Correo Electronico</label>
                            <div class="input-group">
                                <input type="email" class="form-control" id="email_c" name="email">
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
                            <label for="password_c">Nueva contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_c" name="password">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="password-toggle" onclick="PasswordUserVisibility('password_c')">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="password_confirmation_c">Confirmacion contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation_c" name="password_confirmation">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="password-confirmation-toggle" onclick="PasswordUserVisibility('password_confirmation_c')">
                                        <i class="fas fa-eye"></i>
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
                <button type="button" class="btn btn-primary" id="CreateUserButton" onclick="CreateUser()" title="Guardar usuario">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
