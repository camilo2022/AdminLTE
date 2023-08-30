@extends('Templates.Dashboard')
@section('content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Configuracion</h1>
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
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2>
                    <a href=" {{ route('Dashboard.User.Create') }} "
                        class="btn btn-primary">Registrar usuario
                    </a>
                </h2>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Listado de Usuarios Activos</h3>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example2" class="table table-bordered table-hover dataTable dtr-inline">
                                <thead class="thead-dark">
                                    <tr>
                                        <th colspan="3">Informacion</th>
                                        <th colspan="3">Gestión</th>
                                        <th colspan="2">Módulos</th>
                                        <th colspan="2">Submódulos</th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th>Usuario</th>
                                        <th>Email</th>
                                        <th>Password</th>
                                        <th>Editar</th>
                                        <th>Eliminar</th>
                                        <th>Asignar</th>
                                        <th>Quitar</th>
                                        <th>Asignar</th>
                                        <th>Quitar</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->name.' '.$user->lastname }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <a class="btn btn-sm text-white" data-toggle='modal'
                                                    data-target='#modalUser' style="background:#000;"
                                                        onclick="userinfo({{ $user }})">
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
                                                <form method="post" action="{{ route('Dashboard.User.Destroy', $user->id) }}" onsubmit="deleteData(event,this)">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash text-white"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="{{ route('Dashboard.User.Show.Module', $user->id) }}"
                                                    class="btn btn-success btn-sm"><i class="fas fa-plus-circle text-white"></i></a>
                                            </td>
                                            <td>
                                                <a href="{{ route('Dashboard.User.Hide.Module', $user->id) }}"
                                                    class="btn btn-warning btn-sm"><i class="fas fa-minus-circle text-white"></i></a>
                                            </td>
                                            <td>
                                                <a href="{{ route('Dashboard.User.Show.SubModule', $user->id) }}"
                                                    class="btn btn-info btn-sm"><i class="fas fa-check-circle text-white"></i></a>
                                            </td>
                                            <td>
                                                <a href="{{ route('Dashboard.User.Hide.SubModule', $user->id) }}"
                                                    class="btn btn-sm" style="background: #ff7519;"><i class="fas fa-times-circle text-white"></i></a>
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
    @include('Dashboard.User.Modal_change_password')
</section>
@endsection
@section('script')
    <script>
        $(function () {
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

        @if(session('success'))
            $(document).Toasts('create', {
                class: 'bg-success', 
                title: 'Accion Exitosa',
                body: '{{ session("success") }}'
            })
        @endif

        @if ($errors->any())
            $(document).Toasts('create', {
                class: 'bg-danger', 
                title: 'Accion Fallida',
                body: '{{ $errors->first() }}'
            })
        @endif

        function userinfo(data) {
            let id_user = $("#id_user").val(data.id);
            let name_user = $("#username").val(data.name);
        }

        $("#save_password").click(function() {
            let password = $("#password").val();
            if (password.length == "") {
                Swal.fire({
                    title: 'Campo Vacio',
                    text: 'Debe diligenciar la contraseña',
                    type: 'warning'
                })
                return false;
            } else if (password.length < 8) {
                Swal.fire({
                    title: 'Opss...',
                    text: 'La contraseña debe ser mayor a 8 caracteres',
                    type: 'warning'
                })
                return false;
            }
            swal.fire({
                title: '¿Desea actualizar la contraseña?',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#DD6B55',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Si, guardar!',
                cancelButtonText: 'No, cancelar!',
                closeOnConfirm: false,
                closeOnCancel: false
            }).then((result) => {
                if (result.value) {
                    document.formuserupdate.submit();
                } else {
                    Swal.fire({
                        title: 'Cancelado',
                        type: 'error'
                    });
                }
            });
        });

        function deleteData(event, form){
            event.preventDefault();
            swal.fire({
                title: '¿Desea inactivar el usuario?',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#DD6B55',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Si, guardar!',
                cancelButtonText: 'No, cancelar!',
                closeOnConfirm: false,
                closeOnCancel: false
            }).then((result) => {
                if (result.value) {
                    form.submit();
                } else {
                    Swal.fire({
                        title: 'Cancelado',
                        type: 'error'
                    });
                }
            });
        }

    </script>
@endsection
