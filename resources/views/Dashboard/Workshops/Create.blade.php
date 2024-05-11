<div class="modal" id="CreateWorkshopModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header w-100">
                <div class="text-center w-100" style="background: white;">
                    <label style="font-size:20px;font-weight:bold;">Creacion de Taller</label>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name_c">Razon social</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="name_c" name="name_c">
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
                            <label for="person_type_id_c">Tipo de persona</label>
                            <div class="input-group">
                                <select class="form-control select2" id="person_type_id_c" name="person_type_id_c" style="width: 88%" onchange="CreateWorkshopModalPersonTypeGetDocumentType(this)">
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
                            <label for="country_id_c">Pais</label>
                            <div class="input-group">
                                <select class="form-control select2" id="country_id_c" name="country_id_c" style="width: 88%" onchange="CreateWorkshopModalCountryGetDepartament(this)">
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
                            <label for="departament_id_c">Departamento</label>
                            <div class="input-group">
                                <select class="form-control select2" id="departament_id_c" name="departament_id_c" style="width: 88%" onchange="CreateWorkshopModalDepartamentGetCity(this)">
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
                            <label for="city_id_c">Municipio</label>
                            <div class="input-group">
                                <select class="form-control select2" id="city_id_c" name="city_id_c" style="width: 88%">
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
                            <label for="document_type_id_c">Tipo de documento</label>
                            <div class="input-group">
                                <select class="form-control select2" id="document_type_id_c" name="document_type_id_c" style="width: 86%">
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
                            <label for="address_c">Direccion</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="address_c" name="address_c">
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
                            <label for="document_number_c">Numero de documento</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="document_number_c" name="document_number_c">
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
                            <label for="neighborhood_c">Barrio</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="neighborhood_c" name="neighborhood_c">
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
                            <label for="email_c">Correo electronico</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="email_c" name="email_c">
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
                            <label for="telephone_number_first_c">Numero de telefono</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="telephone_number_first_c" name="telephone_number_first_c">
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
                            <label for="telephone_number_second_c">Numero de telefono</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="telephone_number_second_c" name="telephone_number_second_c">
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
                <button type="button" class="btn btn-primary" id="CreateWorkshopButton" onclick="CreateWorkshop()" title="Guardar taller.">
                    <i class="fas fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>
</div>
