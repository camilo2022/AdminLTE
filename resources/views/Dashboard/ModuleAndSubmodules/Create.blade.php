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
                    <label for="icon">Icono</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="icon_c" name="icon" onkeyup="CreateModuleAndSubmodulesChangeClassIcon(this, 'icon_module')">
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
                                        <input type="text" class="form-control" id="submodule0_c" name="submodule">
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-info btn-sm ml-2 mt-2" data-card-widget="collapse">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm ml-2 mt-2" data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body" style="display: none;">
                                    <div class="p-4">
                                        <select name="" id="" class="form-control select2" style="width: 100%;"></select>
                                    </div>
                                    <div class="table-responsive pb-4" id="permissions_access_c">
                                        <div class="row pl-2 icheck-primary">
                                            <input type="checkbox" id="Dashboard.ModulesAndSubmodules.Index">
                                            <label for="Dashboard.ModulesAndSubmodules.Index" class="mt-3 ml-3">
                                                Dashboard.ModulesAndSubmodules.Index
                                            </label>
                                        </div>
                                    </div>
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