<div class="modal" id="ShowProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Visualizacion de Producto</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card collapsed-card">
                            <div class="card-header border-0 ui-sortable-handle">
                                <h3 class="card-title mt-1">
                                    <i class="fas fa-arrow-up-9-1"></i><label class="ml-2">Tallas</label>
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-info btn-sm ml-2" data-card-widget="collapse">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body table-responsive" style="max-height: 500px; overflow-y: auto;" id="sizes_s">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card collapsed-card">
                            <div class="card-header border-0 ui-sortable-handle">
                                <h3 class="card-title mt-1">
                                    <i class="fas fa-palette"></i><label class="ml-2">Colores y tonos</label>
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-info btn-sm ml-2" data-card-widget="collapse">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body table-responsive" style="max-height: 500px; overflow-y: auto;" id="colors_tones_s">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-image"></i>
                                    <label class="ml-2">Carga de Archivos | Imagenes | Videos</label>
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div id="accordion">
                                    <!-- we are adding the .class so bootstrap.js collapse plugin detects it -->
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4 class="card-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                                    Collapsible Group Item #1
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse in">
                                            <div class="card-body">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="card card-danger">
                                        <div class="card-header">
                                            <h4 class="card-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                                    Collapsible Group Danger
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseTwo" class="panel-collapse collapse">
                                            <div class="card-body">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="card card-success">
                                        <div class="card-header">
                                            <h4 class="card-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                                    Collapsible Group Success
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseThree" class="panel-collapse collapse">
                                            <div class="card-body">

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
                <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Cerrar ventana">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
        </div>
    </div>
</div>
