<div class="modal fade" id="CreateProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Creacion de Producto</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="code_c">Codigo</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="code_c" name="code">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-code"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="clothing_line_id_c">Lineas</label>
                            <div class="input-group">
                                <select class="form-control select2" id="clothing_line_id_c" name="clothing_line_id" style="width: 87%" onchange="CreateProductModalClothingLineGetCategory(this)">
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
                            <label for="category_id_c">Categoria</label>
                            <div class="input-group">
                                <select class="form-control select2" id="category_id_c" name="category_id" style="width: 86%" onchange="CreateProductModalCategoryGetSubcategory(this)">
                                    <option value="">Seleccione</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-shirt"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="correria_id_c">Correria</label>
                            <div class="input-group">
                                <select class="form-control select2" id="correria_id_c" name="correria_id" style="width: 87%">
                                    <option value="">Seleccione</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-rectangle-vertical-history"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="model_id_c">Modelo</label>
                            <div class="input-group">
                                <select class="form-control select2" id="model_id_c" name="model_id" style="width: 87%">
                                    <option value="">Seleccione</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-scissors"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subcategory_id_c">Subcategoria</label>
                            <div class="input-group">
                                <select class="form-control select2" id="subcategory_id_c" name="subcategory_id" style="width: 89%">
                                    <option value="">Seleccione</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-shirt-tank-top"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="price_c">Precio de Venta</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="price_c" name="price">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-dollar-sign"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cost_c">Costo de fabricacion</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="cost_c" name="cost">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-coin"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trademark_id_c">Marca</label>
                            <div class="input-group">
                                <select class="form-control select2" id="trademark_id_c" name="trademark_id" style="width: 88%">
                                    <option value="">Seleccione</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-registered"></i>
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
                <button type="button" class="btn btn-primary" id="CreateProductButton" onclick="CreateProduct()" title="Guardar producto.">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
