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
        @include('Dashboard.Users.Password')
        @include('Dashboard.Users.Create')
    </section>
@endsection
@section('script')
    <script>

        let tableUsers = $('#users').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/Dashboard/Users/Index/Query",
                "type": "POST",
                "data": function (request) {
                    var columnMappings = {
                        0: 'id',
                        1: 'name',
                        2: 'last_name',
                        3: 'document_number',
                        4: 'phone_number',
                        5: 'address',
                        6: 'email'
                    };
                    request._token = "{{ csrf_token() }}";
                    request.perPage = request.length;
                    request.page = (request.start / request.length) + 1;
                    request.search = request.search.value;
                    request.column = columnMappings[request.order[0].column];
                    request.dir = request.order[0].dir;
                },
                "dataSrc": function (response) {
                    return response.data.users;
                }
            },
            "columns": [
                { data: 'id' },
                { data: 'name' },
                { data: 'last_name' },
                { data: 'document_number' },
                { data: 'phone_number' },
                { data: 'address' },
                { data: 'email' },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<a onclick="PasswordUserModal(${data.id}, '${data.email}')"
                            type="button" data-target="#PasswordUserModal" data-toggle='modal'
                            class="btn bg-dark btn-sm" title="Recuperar contraseña">
                                <i class="fas fa-user-gear text-white"></i>
                            </a>`;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<a href="/Dashboard/Users/Edit/${data.id}" class="btn btn-primary btn-sm"
                            title="Editar usuario">
                                <i class="fas fa-user-pen"></i>
                            </a>`;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<a class="btn btn-danger btn-sm" onclick="DeleteUser(${data.id})"
                            title="Eliminar usuario" id="DeleteUserButton">
                                <i class="fas fa-user-minus text-white"></i>
                            </a>`;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<a href="/Dashboard/Users/Edit/${data.id}" class="btn btn-success btn-sm"
                            title="Asignar rol y permisos al usuario">
                                <i class="fas fa-user-unlock"></i>
                            </a>`;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<a href="/Dashboard/Users/Edit/${data.id}" class="btn btn-warning btn-sm"
                            title="Remover rol y permisos al usuario">
                                <i class="fas fa-user-lock"></i>
                            </a>`;
                    }
                },
            ],
            "columnDefs": [
                { "orderable": true, "targets": [0, 1, 2, 3, 4, 5, 6] },
                { "orderable": false, "targets": [7, 8, 9, 10, 11] }
            ],
            "pagingType": "full_numbers",
            "language": {
                oPaginate: {
                    sFirst: "Primero",
                    sLast: "Último",
                    sNext: "Siguiente",
                    sPrevious: "Anterior",
                    sProcessing: "Procesando...",
                },
                "emptyTable": "No hay datos disponibles.",
                "lengthMenu": "Mostrar _MENU_ registros por página.",
                "search": "Buscar:",
                "zeroRecords": "No se encontraron registros coincidentes.",
                "decimal" : ",",
                "thousands": ".",
                "sEmptyTable" : "No se ha llamado información o no está disponible.",
                "sZeroRecords" : "No se encuentran resultados.",
            },
            "pageLength": 10,
            "lengthMenu": [10, 25, 50, 100],
            "paging": true,
            "info": false,
            "searching": true,
            "autoWidth": true,
        });

        function CreateUser() {
            Swal.fire({
                title: '¿Desea guardar el usuario?',
                text: 'El usuario será creado.',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#DD6B55',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Si, guardar!',
                cancelButtonText: 'No, cancelar!',
                closeOnConfirm: false,
                closeOnCancel: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: `/Dashboard/Users/Store`,
                        type: 'POST',
                        data: {
                            '_token': "{{ csrf_token() }}",
                            'name': $("#name_s").val(),
                            'last_name': $("#last_name_s").val(),
                            'document_number': $("#document_number_s").val(),
                            'phone_number': $("#phone_number_s").val(),
                            'address': $("#address_s").val(),
                            'email': $("#email_s").val(),
                            'password': $("#password_s").val(),
                            'password_confirmation': $("#password_confirmation_s").val()
                        },
                        success: function(response) {
                            tableUsers.ajax.reload();
                            toastr.success(response.message);
                            $('#CreateUserModal').modal('hide');
                            $("#name_s").val('');
                            $("#last_name_s").val('');
                            $("#document_number_s").val('');
                            $("#phone_number_s").val('');
                            $("#address_s").val('');
                            $("#email_s").val('');
                            $("#password_s").val('');
                            $("#password_confirmation_s").val('');
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            tableUsers.ajax.reload();
                            if(xhr.responseJSON.error){
                                toastr.error(xhr.responseJSON.error.message);
                            }
                            if(xhr.responseJSON.errors){
                                $.each(xhr.responseJSON.errors, function(field, messages) {
                                    $.each(messages, function(index, message) {
                                        toastr.error(message);
                                    });
                                });
                            }
                        }
                    });
                } else {
                    toastr.error('El usuario no fue creado.')
                }
            });
        }

        function PasswordUserModal(id, email) {
            $('#PasswordUserButton').attr('onclick', `PasswordUser(${id})`);

            $("#email_p").val(email);
            $("#password_p").val('')
            $("#password_confirmation_p").val('')
        }

        function PasswordUserVisibility(id) {
            let passwordInput = $(`#${id}`);
            let passwordIcon = passwordInput.closest('.input-group');
            if (passwordInput.attr('type') == 'password') {
                passwordInput.attr('type', 'text');
                passwordIcon.find('.fa-eye').toggleClass('fa-eye fa-eye-slash');
            } else if (passwordInput.attr('type') == 'text') {
                passwordInput.attr('type', 'password');
                passwordIcon.find('.fa-eye-slash').toggleClass('fa-eye-slash fa-eye');
            }
        }

        function PasswordUser(id) {
            Swal.fire({
                title: '¿Desea actualizar la contraseña el usuario?',
                text: 'El usuario se le actualizara la contraseña.',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#DD6B55',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Si, guardar!',
                cancelButtonText: 'No, cancelar!',
                closeOnConfirm: false,
                closeOnCancel: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: `/Dashboard/Users/Password/${id}`,
                        type: 'PUT',
                        data: {
                            '_token': "{{ csrf_token() }}",
                            'id': id,
                            'password': $("#password_p").val(),
                            'password_confirmation': $("#password_confirmation_p").val()
                        },
                        success: function(response) {
                            tableUsers.ajax.reload();
                            toastr.success(response.message);
                            $('#PasswordUserModal').modal('hide');
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            tableUsers.ajax.reload();
                            if(xhr.responseJSON.error){
                                toastr.error(xhr.responseJSON.error.message);
                            }
                            if(xhr.responseJSON.errors){
                                $.each(xhr.responseJSON.errors, function(field, messages) {
                                    $.each(messages, function(index, message) {
                                        toastr.error(message);
                                    });
                                });
                            }
                        }
                    });
                } else {
                    toastr.error('El usuario no se le actualizo la contraseña.')
                }
            });
        }

        function DeleteUser(id) {
            Swal.fire({
                title: '¿Desea eliminar el usuario?',
                text: 'El usuario será desactivado.',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#DD6B55',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Si, eliminar!',
                cancelButtonText: 'No, cancelar!',
                closeOnConfirm: false,
                closeOnCancel: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: `/Dashboard/Users/Delete`,
                        type: 'DELETE',
                        data: {
                            '_token': "{{ csrf_token() }}",
                            'id': id
                        },
                        success: function(response) {
                            tableUsers.ajax.reload();
                            toastr.success(response.message);
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            tableUsers.ajax.reload();
                            if(xhr.responseJSON.error){
                                toastr.error(xhr.responseJSON.error.message);
                            }
                            if(xhr.responseJSON.errors){
                                $.each(xhr.responseJSON.errors, function(field, messages) {
                                    $.each(messages, function(index, message) {
                                        toastr.error(message);
                                    });
                                });
                            }
                        }
                    });
                } else {
                    toastr.error('El usuario seleccionado no fue eliminado.')
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
