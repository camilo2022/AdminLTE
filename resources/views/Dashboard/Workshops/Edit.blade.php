<div class="modal" id="EditWorkshopModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Edicion de Taller</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name_e">Razon social</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="name_e" name="name_e">
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
                            <label for="person_type_id_e">Tipo de persona</label>
                            <div class="input-group">
                                <select class="form-control select2" id="person_type_id_e" name="person_type_id_e" style="width: 88%" onchange="EditWorkshopModalPersonTypeGetDocumentType(this)">
                                    <option value="">Seleccione</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-people-simple"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="country_id_e">Pais</label>
                            <div class="input-group">
                                <select class="form-control select2" id="country_id_e" name="country_id_e" style="width: 88%" onchange="EditWorkshopModalCountryGetDepartament(this)">
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
                            <label for="departament_id_e">Departamento</label>
                            <div class="input-group">
                                <select class="form-control select2" id="departament_id_e" name="departament_id_e" style="width: 88%" onchange="EditWorkshopModalDepartamentGetCity(this)">
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
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="city_id_e">Municipio</label>
                            <div class="input-group">
                                <select class="form-control select2" id="city_id_e" name="city_id_e" style="width: 88%">
                                    <option value="">Seleccione</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-map-location-dot"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="document_type_id_e">Tipo de documento</label>
                            <div class="input-group">
                                <select class="form-control select2" id="document_type_id_e" name="document_type_id_e" style="width: 86%">
                                    <option value="">Seleccione</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-cards-blank"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="address_e">Direccion</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="address_e" name="address_e">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-location-dot"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="document_number_e">Numero de documento</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="document_number_e" name="document_number_e">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-address-card"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="neighborhood_e">Barrio</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="neighborhood_e" name="neighborhood_e">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-location-arrow"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="email_e">Correo electronico</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="email_e" name="email_e">
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
                            <label for="telephone_number_first_e">Numero de telefono</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="telephone_number_first_e" name="telephone_number_first_e">
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
                            <label for="telephone_number_second_e">Numero de telefono</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="telephone_number_second_e" name="telephone_number_second_e">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
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
                <button type="button" class="btn btn-primary" id="EditWorkshopButton" onclick="" title="Actualizar taller.">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
