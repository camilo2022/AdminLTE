@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Listado Usuarios</h1>
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
                                <li class="nav-item"><a class="nav-link active"
                                        href="{{ route('Dashboard.User.Create') }}">Registrar usuario</a></li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example2" class="table table-bordered table-hover dataTable dtr-inline">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th colspan="3">Informacion</th>
                                            <th colspan="3">Gestión</th>
                                        </tr>
                                        <tr>
                                            <th>#</th>
                                            <th>Usuario</th>
                                            <th>Email</th>
                                            <th>Password</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->name . ' ' . $user->lastname }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <a class="btn btn-sm text-white" data-toggle='modal'
                                                        data-target='#modalUser' style="background:#000;"
                                                        onclick="viewUserInfo({{ $user }})">
                                                        <i class="fas fa-key text-white"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('Dashboard.User.Edit', $user->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fas fa-pen text-white"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <form method="post"
                                                        action="{{ route('Dashboard.User.Destroy', $user->id) }}"
                                                        onsubmit="deleteUser(event, this)">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash text-white"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('Dashboard.User.ModalChangePassword')
    </section>
@endsection
@section('script')
    <script>
        $(function() {
            $("#example2").DataTable({
                "responsive": true,
                "autoWidth": true,
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "retrieve": true,
            });
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
