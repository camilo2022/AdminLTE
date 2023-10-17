<div class="modal" id="CreateModuleAndSubmodulesModal" tabindex="-1" role="dialog" 
    aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Creacion de Modulo y Submodulos</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="module">Modulo</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="module_c" name="module">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-sliders"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="icon_c">Icono Modulo</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="icon_c" name="icon_c" onkeyup="CreateModuleAndSubmodulesChangeClassIcon(this, 'icon_module')">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="" id="icon_module"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Roles de Acceso</label>
                    <div class="card collapsed-card">
                        <div class="card-header border-0 ui-sortable-handle">
                            <h3 class="card-title mt-1">
                                Roles
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-info btn-sm ml-2" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm ml-2" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="display: none;">
                            <div class="table-responsive pb-4" id="roles_access_c">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="submodule">Submodulos</label>
                    <div class="submodules_c">
                        <div class="form-group">
                            <div class="card collapsed-card">
                                <div class="card-header border-0 ui-sortable-handle">
                                    <h3 class="card-title mt-1">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="" id="">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fas fa-slider"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-info btn-sm ml-2 mt-2" data-card-widget="collapse">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm ml-2 mt-2" data-card-widget="remove" onclick="CreateModuleAndSubmodulesRemoveSubmodule(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body" style="display: none;">
                                    <div class="form-group">
                                        <label for="">Role</label>
                                        <select name="role_c" id="role_c" class="form-control role_c">
                                            <option value="">Seleccione</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Permission</label>
                                        <select name="permission_access_c" id="permission_access_c" class="form-control permission_access_c">
                                            <option value="">Seleccione</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Ruta</label>
                                        <div class="input-group">
                                            <input type="text" name="url_c" id="url_c" class="form-control url_c" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fas fa-route-highway"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Icono Submodulo</label>
                                        <div class="input-group">
                                            <input type="text" name="subicon_c" id="subicon_c" class="form-control subicon_c">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="" id="icon_module"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="CreateRoleAndPermissionsAddPermissionButton" data-count="1" title="Agregar permiso">
                    <i class="fas fa-plus"></i>
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Cerrar ventana">
                    <i class="fas fa-xmark"></i>
                </button>
                <button type="button" class="btn btn-primary" id="CreateRoleAndPermissionsButton" onclick="CreateRoleAndPermissions()" title="Guardar Rol y permisos">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>