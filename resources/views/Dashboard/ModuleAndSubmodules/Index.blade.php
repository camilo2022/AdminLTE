@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Modulos y Submodulos</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item">ModulesAndSubmodules</li>
                            <li class="breadcrumb-item">Index</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
    </section>

    @if (session('success') || (session('info')) || (session('warning')) || (session('danger')))
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-bell mr-2"></i>Alertas</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                @include('Dashboard.Alerts.Success')
                                @include('Dashboard.Alerts.Info')
                                @include('Dashboard.Alerts.Warning')
                                @include('Dashboard.Alerts.Danger')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link active" type="button" onclick="CreateModuleAndSubmodulesModal()" data-target="#CreateModuleAndSubmodulesModal" data-toggle='modal' title="Agregar modulo y submodulos">
                                        <i class="fas fa-shield-plus"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="modulesAndSubmodules" class="table table-bordered table-hover dataTable dtr-inline">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th colspan="8">Información</th>
                                            <th colspan="2">Gestionar</th>
                                        </tr>
                                        <tr>
                                            <th>#</th>
                                            <th>Modulo</th>
                                            <th>Icono</th>
                                            <th>Rol de Accesso</th>
                                            <th>Sub modulos</th>
                                            <th>Rutas</th>
                                            <th>Iconos</th>
                                            <th>Permisos de Accesso</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>
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
        @include('Dashboard.ModuleAndSubmodules.Create')
    </section>
@endsection
@section('script')
    <script src="{{ asset('js/Dashboard/ModuleAndSubmodules/DataTableIndex.js') }}"></script>
    <script src="{{ asset('js/Dashboard/ModuleAndSubmodules/Delete.js') }}"></script>
    <script>
        function CreateModuleAndSubmodulesModal() {
            $.ajax({
                url: `/Dashboard/ModulesAndSubmodules/Store/Query`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    CreateModuleAndSubmodulesQueryRoles(response.data);
                },
                error: function(xhr, textStatus, errorThrown) {
                    CreateModuleAndSubmodulesAjaxError(xhr);
                }
            });
        }

        function CreateModuleAndSubmodulesAddSubmodule() {

        }

        function CreateModuleAndSubmodulesRemoveSubmodule() {

        }

        function CreateModuleAndSubmodulesChangeClassIcon(input, icon) {
            $(`#${icon}`).attr('class', input.value);
        }

        function CreateModuleAndSubmodulesQueryRoles(roles) {
            let rolesDiv = $('#roles_access_c').empty();

            $.each(roles, function (i, role) {

                let roleDiv = $('<div class="row pl-2 icheck-primary">');

                let roleCheckbox = $(`<input type="checkbox">`).attr('id', role.name);
                roleCheckbox.click(function() {
                    // Obtener IDs de los checkboxes marcados en #roles_access_c
                    let selectedRoleIDs = $('#roles_access_c .icheck-primary input[type="checkbox"]:checked').map(function() {
                        return $(this).attr('id');
                    }).get();
                    // Recorrer #submodules_c
                    $('.submodules_c').each(function() {
                        let submoduleElement = $(this);

                        let select = submoduleElement
                            .find('select#role_c')
                            .empty()
                            .append($('<option>', 
                                {
                                    'value': '', 
                                    'text': 'Seleccione'
                                }
                            ));
                        // Agregar opciones con los roles seleccionados
                        $.each(selectedRoleIDs, function(index, roleID) {
                            select.append($('<option>', 
                                {
                                    'value': roleID, 
                                    'text': roleID
                                }
                            ));
                        });
                        select.change(function() {    
                            CreateModuleAndSubmodulesQueryPermissions(this, submoduleElement);
                        });
                    });
                });
                
                let roleLabel = $('<label>').text(role.name);
                roleLabel.attr('for', role.name);
                roleLabel.attr('class', 'mt-3 ml-3');

                // Agregar elementos al cardBody
                roleDiv.append(roleCheckbox);
                roleDiv.append(roleLabel);
                rolesDiv.append(roleDiv);
            });
        }

        function CreateModuleAndSubmodulesQueryPermissions(select, submoduleElement) {
            $.ajax({
                url: `/Dashboard/ModulesAndSubmodules/Store/Query`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'role': $(select).val()
                },
                success: function(response) {
                    CreateModuleAndSubmodulesAddPermissions(submoduleElement, response.data.permissions);
                },
                error: function(xhr, textStatus, errorThrown) {
                    CreateModuleAndSubmodulesAjaxError(xhr);
                    submoduleElement.find('#permissions_access_c').empty();
                    submoduleElement.find('#permission_access_c').empty();
                }
            });
        }

        function CreateModuleAndSubmodulesAddPermissions(submoduleElement, permissions) {
            let select = submoduleElement
                .find('#permission_access_c')
                .empty()
                .append($('<option>', 
                    {
                        'value': '', 
                        'text': 'Seleccione'
                    }
                ));
            // Agregar opciones con los roles seleccionados
            $.each(permissions, function(index, permission) {
                console.log(permission)
                select.append($('<option>', 
                    {
                        'value': permission.id, 
                        'text': permission.name
                    }
                ));
            });
            select.change(function() {    
                submoduleElement.find('#url_c').val(`/${$(this).find('option:selected').text().replace(/\./g, '/')}`)
            });
        }

        function CreateModuleAndSubmodulesAjaxError(xhr) {
            if(xhr.responseJSON.error){
                toastr.error(xhr.responseJSON.error.message);
                toastr.error(xhr.responseJSON.error.error);
            } else if(xhr.responseJSON.errors){
                $.each(xhr.responseJSON.errors, function(field, messages) {
                    $.each(messages, function(index, message) {
                        toastr.error(message);
                    });
                });
            } else {
                toastr.error(xhr.responseJSON.message);
            }
        }
    </script>
@endsection    
