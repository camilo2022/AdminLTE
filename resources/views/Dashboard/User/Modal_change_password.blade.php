<div class="modal" id="modalUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="card-header text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Cambiar Contraseña</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Dashboard.User.Password')}}" method="POST" name="formuserupdate" id="formuserupdate">
                    @csrf

                    <div class="form-group">
                        <label for="formGroupExampleInput">Nombre del usuario</label>
                        <input type="text" class="form-control" id="username" name="username" id="username" readonly>
                    </div>
                   
                    <div class="form-group">
                        <label for="formGroupExampleInput">Nueva contraseña</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="id_user" name="id_user"
                            placeholder="Example input" readonly="readonly" minlength="8">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="save_password">Guardar</button>
            </div>
        </div>
    </div>
</div>
