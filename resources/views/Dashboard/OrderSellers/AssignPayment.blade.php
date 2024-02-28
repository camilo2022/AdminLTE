<div class="modal fade" id="AssignPaymentOrderSellerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Pago del Pedido</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="value_a">Valor</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="value_a" name="value_a">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="reference_a">Referencia de Pago</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="reference_a" name="reference_a">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-paperclip"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="payment_type_id_a">Metodo de Pago</label>
                    <div class="input-group">
                        <select class="form-control select2" id="payment_type_id_a" name="payment_type_id_a" style="width: 89%" onchange="AssignPaymentOrderSellerModalPaymentTypeGetBank(this)">
                            <option value="">Seleccione</option>
                        </select>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-money-check-dollar-pen"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="div_bank_id_a">
                    <label for="bank_id_a">Bancos</label>
                    <div class="input-group">
                        <select class="form-control select2" id="bank_id_a" name="bank_id_a" style="width: 90%">
                            <option value="">Seleccione</option>
                        </select>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-building-columns"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_a">Fecha de Pago</label>
                    <div class="input-group date" id="date" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#date_a" id="date_a" name="date_a">
                        <div class="input-group-append" data-target="#date_a" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                    <div class="form-group">
                        <label for="supports_c">Soportes</label>
                        <div class="input-group">
                            <input type="file" class="form-control dropify" id="supports_c" name="supports_c"
                            accept=".jpg, .jpeg, .png, .gif, .pdf" multiple>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Cerrar ventana">
                    <i class="fas fa-xmark"></i>
                </button>
                <button type="button" class="btn btn-primary" id="AssignPaymentOrderSellerButton" onclick="AssignPaymentOrderSeller()" title="Guardar pago del pedido.">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
