<div class="modal" id="EditOrderSellerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Edicion de Pedido</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="client_id_e">Cliente</label>
                    <div class="input-group">
                        <select class="form-control select2" id="client_id_e" name="client_id" style="width: 88%" onchange="EditOrderSellerModalClientGetClientBranch(this)">
                            <option value="">Seleccione</option>
                        </select>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-user-tie"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="client_branch_id_e">Sucursal</label>
                    <div class="input-group">
                        <select class="form-control select2" id="client_branch_id_e" name="client_branch_id" style="width: 88%">
                            <option value="">Seleccione</option>
                        </select>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-code-branch"></i>
                            </span>
                        </div>
                    </div>
                </div> 
                <div class="form-group">
                    <label for="seller_observation_e">Observacion</label>
                    <div class="input-group">
                        <textarea class="form-control" id="seller_observation_e" name="seller_observation" cols="30" rows="4"></textarea>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-text-size"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="dispatch_e">Cuando despachar</label>
                    <div class="input-group">
                        <select class="form-control" id="dispatch_e" name="dispatch" style="width: 88%" onchange="EditOrderSellerModalDispatchGetDispatchDate(this)">
                            <option value="">Seleccione</option>
                            <option value="De inmediato">De inmediato</option>
                            <option value="Antes de">Antes de</option>
                            <option value="Despues de">Despues de</option>
                        </select>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-code-branch"></i>
                            </span>
                        </div>
                    </div>
                </div>   
                <div class="form-group" id="div_dispatch_date_e">
                    <label for="dispatch_date_e">Fecha despachar</label>
                    <div class="input-group date" id="dispatch_date" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#dispatch_date_e" id="dispatch_date_e" name="dispatch_date">
                        <div class="input-group-append" data-target="#dispatch_date_e" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Cerrar ventana">
                    <i class="fas fa-xmark"></i>
                </button>
                <button type="button" class="btn btn-primary" id="EditOrderSellerButton" onclick="" title="Actualizar pedido.">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
