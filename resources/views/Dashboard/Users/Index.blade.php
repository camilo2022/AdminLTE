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
                                    <a class="nav-link active" href="">
                                        Registrar Usuario
                                    </a>
                                </li>
                                <li class="nav-item ml-auto">
                                    <a class="nav-link active" href="#">
                                        Activos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="">
                                        Inactivos
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
                                            <th>Password</th>
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
                    request._token = "{{ csrf_token() }}";
                    request.perPage = request.length;
                    request.page = (request.start / request.length) + 1;
                    request.search = request.search.value;
                    // Add other form data if needed
                    
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
                        return '<a href="/edit/' + data.id + '">Edit</a>';
                    }
                },
                { 
                    data: null,
                    render: function (data, type, row) {
                        return '<a href="/edit/' + data.id + '">Edit</a>';
                    }
                },
                { 
                    data: null,
                    render: function (data, type, row) {
                        return '<a href="/edit/' + data.id + '">Edit</a>';
                    }
                },
                { 
                    data: null,
                    render: function (data, type, row) {
                        return '<a href="/edit/' + data.id + '">Edit</a>';
                    }
                },
                { 
                    data: null,
                    render: function (data, type, row) {
                        return '<a href="/edit/' + data.id + '">Edit</a>';
                    }
                },
                // Add columns for Delete, Assign, and Remove buttons similarly
            ],
            "pagingType": "full_numbers",
            "language": {
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "serverSide": true,
            "pageLength": 10,
            "lengthMenu": [10, 25, 50, 100],
            "paging": true,
            "info": false,
            "searching": true
        });




        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        @if (session('success'))
            $(document).Toasts('create', {
                class: 'bg-success',
                title: 'Accion Exitosa',
                body: '{{ session('success') }}'
            })
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Accion Fallida',
                    body: '{{ $error }}'
                })
            @endforeach
        @endif
        function viewUserInfo(data) {
            $("#id_user").val(data.id);
            $("#username").val(data.name);
        }
        function validateFormChangePassword(event, form) {
            event.preventDefault();
            let password = $("#password").val();
            let submit = true;
            if (password.length == "") {
                toastr.warning('Por favor, complete el campo contraseña.')
                submit = false;
            } else if (password.length < 8) {
                toastr.error('La contraseña ingresada no es válida, debe tener al menos 8 caracteres.')
                submit = false;
            } else {
                toastr.success('Contraseña valida! Puede continuar con la acción solicitada.')
            }
            if (submit) {
                Swal.fire({
                    title: '¿Desea actualizar la contraseña?',
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
                        Toast.fire({
                            icon: 'success',
                            title: 'Cambio de contraseña confirmada.'
                        });
                        form.submit();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Cambio de contraseña cancelada.'
                        });
                    }
                });
            }
        };
        function deleteUser(event, form) {
            event.preventDefault();
            Swal.fire({
                title: '¿Desea inactivar el usuario?',
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
                    Toast.fire({
                        icon: 'success',
                        title: 'Inactivacion de usuario confirmada.'
                    });
                    form.submit();
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Inactivacion de usuario cancelada.'
                    });
                }
            });
        }
    </script>
@endsection