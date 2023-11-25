<div class="modal fade bd-example-modal-lg" id="EditCategoryAndSubcategoriesModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Edicion de Categoria y Subcategorias</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="clothing_line_id_e">Lineas</label>
                            <div class="input-group">
                                <select class="form-control select2" id="clothing_line_id_e" name="clothing_line_id" style="width: 88%">
                                    <option value="">Seleccione</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-clothes-hanger"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name_e">Nombre</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="name_e" name="name">
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
                            <label for="code_e">Codigo</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="code_e" name="code">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-code"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description_e">Descripcion</label>
                            <div class="input-group">
                                <textarea class="form-control" id="description_e" name="description" cols="30" rows="1"></textarea>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-text-size"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="submodule">Subcategorias</label>
                            <div class="subcategories_e">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="EditCategoryAndSubcategoriesAddPermissionButton" title="Agregar subcategoria" onclick="EditCategoryAndSubcategoriesAddSubcategory(null, null, null, null, null)">
                    <i class="fas fa-plus"></i>
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Cerrar ventana">
                    <i class="fas fa-xmark"></i>
                </button>
                <button type="button" class="btn btn-primary" id="EditCategoryAndSubcategoriesButton" onclick="" title="Guardar Categoria y subcategorias">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
