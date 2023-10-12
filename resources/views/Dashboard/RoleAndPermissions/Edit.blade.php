<div class="modal fade" id="EditRoleAndPermissionsModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Edicion de Rol y permisos</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="role_c">Rol</label>
                    <input type="text" class="form-control" id="role_e" name="role">
                </div>
                <div class="form-group">
                    <label for="">Permisos</label>
                    <div class="permissions_e">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="EditRoleAndPermissionsAddPermissionButton" data-count="0" onclick="EditRoleAndPermissionsAddPermission(this)" title="Agregar permiso">
                    <i class="fas fa-plus"></i>
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Cerrar ventana">
                    <i class="fas fa-xmark"></i>
                </button>
                <button type="button" class="btn btn-primary" id="EditRoleAndPermissionsButton" onclick="" title="Editar Rol y permisos">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
