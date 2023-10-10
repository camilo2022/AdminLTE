@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Usuarios</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item">User</li>
                            <li class="breadcrumb-item">Index</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link active" type="button" data-target="#CreateUserModal" data-toggle='modal' title="Agregar usuario">
                                        <i class="fas fa-user-plus"></i>
                                    </a>
                                </li>
                                <li class="nav-item ml-auto">
                                    <a class="nav-link active" href="/Dashboard/Users/Index" title="Usuarios activos">
                                        <i class="fas fa-user-check"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/Dashboard/Users/Inactives" title="Usuarios inactivos">
                                        <i class="fas fa-user-xmark"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="users" class="table table-bordered table-hover dataTable dtr-inline">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th colspan="7">Información Personal</th>
                                            <th colspan="3">Gestionar Usuario</th>
                                            <th colspan="2">Roles y Permisos</th>
                                        </tr>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Documento</th>
                                            <th>Telefono</th>
                                            <th>Direccion</th>
                                            <th>Email</th>
                                            <th>Contraseña</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>
                                            <th>Asignar</th>
                                            <th>Remover</th>
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
        @include('Dashboard.Users.Create')
        @include('Dashboard.Users.Edit')
        @include('Dashboard.Users.Password')
        @include('Dashboard.Users.AssignRoleAndPermission')
    </section>
@endsection
@section('script')
    <script src="{{ asset('js/Dashboard/Users/DataTableIndex.js') }}"></script>
    <script src="{{ asset('js/Dashboard/Users/Validators.js') }}"></script>
    <script src="{{ asset('js/Dashboard/Users/Create.js') }}"></script>
    <script src="{{ asset('js/Dashboard/Users/Edit.js') }}"></script>
    <script src="{{ asset('js/Dashboard/Users/Password.js') }}"></script>
    <script src="{{ asset('js/Dashboard/Users/Delete.js') }}"></script>
    <script>

        function AssignRoleAndPermissionUserModal(id) {
            $.ajax({
                url: `/Dashboard/Users/AssignRoleAndPermissions/Query`,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id
                },
                success: function(response) {
                    if (response.data.length == 0) {
                        toastr.info('Tiene todos los roles y permisos ya asignados.')
                        return false;
                    }
                    $('#permissions-container').empty();
                    $.each(response.data, function (index, item) {

                        var header = $(`<div class="card-header m-2" data-toggle="collapse" data-target="#role-${index}">`);
                        header.text(item.role);

                        var body = $(`<div id="role-${index}" class="collapse">`);

                        var checkallDiv = $('<div class="row pl-4 ml-2">');
                        var checkall = $('<input type="checkbox">');
                        var checkallLabel = $('<label>')
                        checkall.change(function() {
                            var checkboxes = body.find('input[type="checkbox"]');
                            checkboxes.prop('checked', checkall.prop('checked'));
                        });

                        checkallLabel.text('Seleccionar todos los permisos');

                        checkallDiv.append(checkall);
                        checkallDiv.append(checkallLabel);

                        body.append(checkallDiv);

                        $.each(item.permissions, function (i, permission) {
                            var permissionDiv = $('<div class="row pl-4 ml-2">');

                            var permissionCheckbox = $(`<input type="checkbox" id="${permission}">`);

                            var permissionLabel = $('<label>');
                            permissionLabel.text(permission);

                            permissionDiv.append(permissionCheckbox);
                            permissionDiv.append(permissionLabel);

                            body.append(permissionDiv);
                        });

                        var footerDiv = $('<div class="footer d-flex justify-content-end mr-4 pb-4">'); // Utilizamos las clases de Bootstrap para alineación
                        var saveButton = $('<button type="button" class="btn btn-primary" title="Asignar rol y permisos.">');
                        saveButton.append('<i class="fas fa-floppy-disk"></i>');

                        saveButton.click(function() {
                            var selectedPermissions = [];
                            body.find('input[type="checkbox"]:checked').each(function() {
                                selectedPermissions.push($(this).attr('id'));
                            });

                            // Llamar a la función AssignRoleAndPermission con el nombre del rol y los permisos
                            AssignRoleAndPermission(item.role, selectedPermissions);
                        });

                        footerDiv.append(saveButton);
                        body.append(footerDiv);

                        $('#permissions-container').append(header);
                        $('#permissions-container').append(body);

                        $('#AssignRoleAndPermissionUserModal').modal('show');
                    });

                },
                error: function(xhr, textStatus, errorThrown) {
                    tableUsers.ajax.reload();
                    if(xhr.responseJSON.error){
                        toastr.error(xhr.responseJSON.error.message)
                    }
                    if(xhr.responseJSON.errors){
                        $.each(xhr.responseJSON.errors, function(field, messages) {
                            $.each(messages, function(index, message) {
                                toastr.error(message)
                            });
                        });
                    }
                }
            });
        }

        @if (session('success'))
            toastr.success(' {{ session('success') }} ')
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error(' {{ $error }} ')
            @endforeach
        @endif
    </script>
@endsection
