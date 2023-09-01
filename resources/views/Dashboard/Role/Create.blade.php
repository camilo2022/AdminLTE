<div class="modal" id="modal_rol" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Crear Rol</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="formGroupExampleInput">Nombre del Rol</label>
                    <input type="text" class="form-control" name="name" autocomplete="off" id="name">
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput">Acceso del Rol</label>
                    <select id="access" class="form-control" name="access">
                        <option value="">Seleccione</option>
                        @foreach ($accesses as $access)
                            <option value="{{ $access->id }}">{{ $access->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" onclick="createRol()">Guardar</button>
            </div>
        </div>
    </div>
</div>
