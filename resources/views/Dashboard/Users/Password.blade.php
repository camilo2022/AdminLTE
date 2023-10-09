<div class="modal" id="PasswordUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Cambiar Contraseña</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="email">Correo Electronico</label>
                    <input type="text" class="form-control" id="email" name="email" readonly>
                </div>
                <div class="form-group">
                    <label for="password">Nueva contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password">
                        <div class="input-group-append">
                            <span class="input-group-text" id="password-toggle" onclick="PasswordUserVisibility('password')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirmacion contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        <div class="input-group-append">
                            <span class="input-group-text" id="password-confirmation-toggle" onclick="PasswordUserVisibility('password_confirmation')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="PasswordUserButton" onclick="">Guardar</button>
            </div>
        </div>
    </div>
</div>
