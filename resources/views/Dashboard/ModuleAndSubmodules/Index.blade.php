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
        function CreateModuleAndSubmodulesChangeClassIcon(input, icon) {
            $(`#${icon}`).attr('class', input.value);
        }

        function CreateModuleAndSubmodulesModal() {
            $.ajax({
                url: `/Dashboard/ModulesAndSubmodules/Store/Query`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    console.log(response);
                    let rolesDiv = $('#roles_access_c').empty();
                    $.each(response.data, function (i, role) {
                        let roleDiv = $('<div class="row pl-2 icheck-primary">');
                        let roleCheckbox = $(`<input type="checkbox">`);
                        roleCheckbox.attr('id', role.name);
                        roleCheckbox.click(function() {
                            // Obtener IDs de los checkboxes marcados en #roles_access_c
                            let selectedRoleIDs = [];
                            $('#roles_access_c input[type="checkbox"]:checked').each(function() {
                                selectedRoleIDs.push($(this).attr('id'));
                            });

                            // Recorrer #submodules_c
                            $('.submodules_c').each(function() {
                                let submoduleElement = $(this);
                                let select = submoduleElement.find('select');

                                // Vaciar el select
                                select.empty();
                                select.append($('<option>', {'value': '', 'text': 'Seleccione'}));
                                // Agregar opciones con los roles seleccionados
                                $.each(selectedRoleIDs, function(index, roleID) {
                                    select.append($('<option>', {'value': roleID, 'text': roleID}));
                                });

                                select.change(function() {
                                    
                                    $.ajax({
                                        url: `/Dashboard/ModulesAndSubmodules/Store/Query`,
                                        type: 'POST',
                                        data: {
                                            '_token': $('meta[name="csrf-token"]').attr('content'),
                                            'role': $(this).val()
                                        },
                                        success: function(response) {
                                            console.log(response)
                                            // Aquí puedes usar 'submoduleElement' para hacer referencia al elemento actual
                                            let permissionsAccessC = submoduleElement.find('#permissions_access_c');
                                            permissionsAccessC.empty();

                                            $.each(response.data.permissions, function (i, permission) {
                                                let permissionDiv = $('<div class="row pl-2 icheck-primary">');
                                                let permissionCheckbox = $(`<input type="checkbox">`);
                                                permissionCheckbox.attr('id', permission.name);

                                                let permissionLabel = $('<label>').text(permission.name);
                                                permissionLabel.attr('for', permission.name);
                                                permissionLabel.attr('class', 'mt-3 ml-3');

                                                // Agregar elementos al cardBody
                                                permissionDiv.append(permissionCheckbox);
                                                permissionDiv.append(permissionLabel);
                                                permissionsAccessC.append(permissionDiv);
                                            });
                                        },
                                        error: function(xhr, textStatus, errorThrown) {
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
                                            let permissionsAccessC = submoduleElement.find('#permissions_access_c');
                                            permissionsAccessC.empty();
                                        }
                                    });

                                });

                                // Vaciar el contenido de #permissions_access_c dentro del form-group actual
                                submoduleElement.find('#permissions_access_c').empty();
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
                },
                error: function(xhr, textStatus, errorThrown) {
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
            });
        }
        $('.select2').select2();
    </script>
@endsection    
