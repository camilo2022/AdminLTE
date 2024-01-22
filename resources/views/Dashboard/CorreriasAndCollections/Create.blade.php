<div class="modal fade bd-example-modal-lg" id="CreateCorreriaAndCollectionModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Creacion de Correria y Coleccion</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="name_c" name="name">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-signature"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="code">Codigo</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="code_c" name="code">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-code"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="start_date_create">Fecha Inicio</label>
                            <div class="input-group date" id="start_date_create" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#start_date_create" id="start_date_c" name="start_date">
                                <div class="input-group-append" data-target="#start_date_create" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="end_date_create">Fecha Fin</label>
                            <div class="input-group date" id="end_date_create" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#end_date_create" id="end_date_c" name="end_date">
                                <div class="input-group-append" data-target="#end_date_create" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="date_definition_start_pilots_create">Fecha definicion e inicio de Pilotos</label>
                            <div class="input-group date" id="date_definition_start_pilots_create" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#date_definition_start_pilots_create" id="date_definition_start_pilots_c" name="date_definition_start_pilots">
                                <div class="input-group-append" data-target="#date_definition_start_pilots_create" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="date_definition_start_samples_create">Fecha definicion entrega Muestras</label>
                            <div class="input-group date" id="date_definition_start_samples_create" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#date_definition_start_samples_create" id="date_definition_start_samples_c" name="date_definition_start_samples">
                                <div class="input-group-append" data-target="#date_definition_start_samples_create" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="proyection_stop_warehouse">Porcentaje proyeccion Bodega</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="proyection_stop_warehouse_c" name="proyection_stop_warehouse">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-percent"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="number_samples_include_suitcase">Numero de muestras a incluir por Maleta</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="number_samples_include_suitcase_c" name="number_samples_include_suitcase">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-hashtag"></i>
                                    </span>
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
                <button type="button" class="btn btn-primary" id="CreateCorreriaAndCollectionButton" onclick="CreateCorreriaAndCollection()" title="Guardar correria y coleccion.">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
