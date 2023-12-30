<div class="modal fade" id="CreateClientBranchModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Creacion de Sucursal</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="code_cb_c">Codigo</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="code_cb_c" name="code">
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
                            <label for="address_cb_c">Direccion</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="address_cb_c" name="address">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-location-dot"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="country_id_cb_c">Pais</label>
                            <div class="input-group">
                                <select class="form-control select2" id="country_id_cb_c" name="country_id" style="width: 88%" onchange="CreateClientBranchModalCountryGetDepartament(this)">
                                    <option value="">Seleccione</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-earth-americas"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="neighborhood_cb_c">Barrio</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="neighborhood_cb_c" name="neighbourhood">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-location-arrow"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="departament_id_cb_c">Departamento</label>
                            <div class="input-group">
                                <select class="form-control select2" id="departament_id_cb_c" name="departament_id" style="width: 88%" onchange="CreateClientBranchModalDepartamentGetCity(this)">
                                    <option value="">Seleccione</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-map"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="email_cb_c">Correo electronico</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="email_cb_c" name="email">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="city_id_cb_c">Municipio</label>
                            <div class="input-group">
                                <select class="form-control select2" id="city_id_cb_c" name="city_id" style="width: 88%">
                                    <option value="">Seleccione</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-map-location-dot"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="telephone_number_first_cb_c">Numero de telefono</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="telephone_number_first_cb_c" name="telephone_number">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="telephone_number_second_cb_c">Numero de telefono</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="telephone_number_second_cb_c" name="telephone_number">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="description_cb_c">Descripcion</label>
                            <div class="input-group">
                                <textarea class="form-control" id="description_cb_c" name="description" cols="30" rows="8"></textarea>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-text-size"></i>
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
                <button type="button" class="btn btn-primary" id="CreateClientBranchButton" onclick="CreateClientBranch()" title="Guardar sucursal.">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
