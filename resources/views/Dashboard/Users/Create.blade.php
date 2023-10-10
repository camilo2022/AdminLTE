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
                            <input type="text" class="form-control" id="name_s" name="name">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="last_name">Apellidos</label>
                            <input type="text" class="form-control" id="last_name_s" name="last_name">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="document_number_s">Numero de documento</label>
                            <input type="number" class="form-control" id="document_number_s" name="document_number">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="phone_number_s">Numero de telefono</label>
                            <input type="number" class="form-control" id="phone_number_s" name="phone_number">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="address_s">Direccion</label>
                            <input type="text" class="form-control" id="address_s" name="address">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="email_s">Correo Electronico</label>
                            <input type="email" class="form-control" id="email_s" name="email">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="password_s">Nueva contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_s" name="password">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="password-toggle" onclick="PasswordUserVisibility('password_s')">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="password_confirmation_s">Confirmacion contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation_s" name="password_confirmation">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="password-confirmation-toggle" onclick="PasswordUserVisibility('password_confirmation_s')">
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
