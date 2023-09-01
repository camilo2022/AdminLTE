<!-- Modal Rol -->
<div class="modal" id="modal_rol_editar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Editar Rol</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="formGroupExampleInput">Nombre del rol</label>
                    <input type="text" class="form-control" name="name" placeholder=""
                        autocomplete="off" id="name_e">
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput">Acceso del Rol</label>
                    <select id="access_e" class="form-control" name="access">
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="edit_rol">Guardar</button>
            </div>
        </div>
    </div>
</div>
