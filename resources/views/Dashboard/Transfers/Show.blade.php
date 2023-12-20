<div class="modal" id="ShowTransferModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Detalles de Transferencia</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item" id="CreateTransferDetail">
                                        <a class="nav-link active" type="button" onclick="CreateTransferDetailModal()" title="Agregar detalle de la transferencia.">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="transferDetails" class="table table-bordered table-hover dataTable dtr-inline nowrap w-100">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Consecutivo</th>
                                                <th>Producto</th>
                                                <th>Talla</th>
                                                <th>Color</th>
                                                <th>Tono</th>
                                                <th>Cantidad</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="ShowTransferButton" data-dismiss="modal" title="Cerrar ventana">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
        </div>
    </div>
</div>
