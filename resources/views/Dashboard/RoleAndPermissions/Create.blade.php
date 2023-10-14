<div class="modal fade" id="CreateRoleAndPermissionsModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Creacion de Rol y permisos</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="CreateRoleAndPermissionsModal()">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="role_c" class="mr-2">Rol</label> 
                    <i class="far fa-circle-question" onclick="SuggestionRole()"></i>
                    <div class="input-group">
                        <input type="text" class="form-control" id="role_c" name="role">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-shield-keyhole"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="mr-2">Permisos</label>
                    <i class="far fa-circle-question" onclick="SuggestionPermissions()"></i>
                    <div class="permissions_c">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control" id="permission_c0" name="permissions_c[]">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="CreateRoleAndPermissionsAddPermissionButton" data-count="1" onclick="CreateRoleAndPermissionsAddPermission(this)" title="Agregar permiso">
                    <i class="fas fa-plus"></i>
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Cerrar ventana" onclick="CreateRoleAndPermissionsModal()">
                    <i class="fas fa-xmark"></i>
                </button>
                <button type="button" class="btn btn-primary" id="CreateRoleAndPermissionsButton" onclick="CreateRoleAndPermissions()" title="Guardar Rol y permisos">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
