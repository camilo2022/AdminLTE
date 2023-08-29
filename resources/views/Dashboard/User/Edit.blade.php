@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="body_scroll">

            <div class="block-header">
                <div class="row">
                    <div class="col-lg-7 col-md-6 col-sm-12">
                        <h2>Asignar Rol</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <i class="zmdi zmdi-settings"></i>
                                <a href="javascript:void(0);">Administracion</a>
                            </li>
                            <li class="breadcrumb-item active">Usuarios</li>
                            <li class="breadcrumb-item active">Asignar Rol</li>
                        </ul>
                        <button class="btn btn-primary btn-icon mobile_menu" type="button"><i
                                class="zmdi zmdi-sort-amount-desc"></i></button>
                    </div>
                    <div class="col-lg-5 col-md-6 col-sm-12">
                        <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i
                                class="zmdi zmdi-arrow-right"></i></button>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <!-- Basic Examples -->
                <div class="row clearfix">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="body">
                                <form action="{{ route('Dashboard.User.Update', $user->id) }}" name="formularioassigr"
                                    id="formularioassigr" method="post" class="mt-2">

                                    @csrf

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput">Nombre del usuario</label>
                                                <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}">
                                                @error('name')
                                                    <span class="error text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="formGroupExampleInput">Correo del usuario</label>
                                                <input type="text" class="form-control" name="email" id="email" value="{{ $user->email }}">
                                                @error('name')
                                                    <span class="error text-danger">{{ $user->email }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="user_rol">Rol del usuario</label>
                                                <select class="form-control show-tick ms select2 choices-remove-button" id="user_rol" name="rol">
                                                    <option value="Sin rol" selected disabled>Seleccionar</option>
                                                    @forelse ($roles as $rol)
                                                        <option value="{{ $rol->name }}" @if(isset($user->roles[0]) && $user->roles[0]->id == $rol->id) selected @endif>{{ $rol->name }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('Dashboard.User.Index') }}" class="btn btn-secondary">Devolver</a>
                                    <button type="submit" class="btn btn-primary" id="algo">Guardar</button>
                                </form>
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
        let rol_inicial = $("#user_rol").val();

        $("#algo").click(function() {
            let name_user = $.trim($("#name").val());
            let email = $.trim($("#email").val());
            let rol = $("#user_rol").val();

            if (name_user == "") {
                Swal.fire({
                    title: 'Campo Vacio ',
                    text: 'El campo Nombre del Usuario es Requerido.',
                    type: 'warning'
                })
                $("#name").focus();
                return false;
            } else if (email == "") {
                Swal.fire({
                    title: 'Campo Vacio ',
                    text: 'El campo Correo del Usuario es Requerido.',
                    type: 'warning'
                })
                $("#email").focus();
                return false;
            } else if (rol == "Sin rol") {
                Swal.fire({
                    title: 'Campo Vacio ',
                    text: 'El campo Rol del Usuario es Requerido.',
                    type: 'warning'
                })
                $("#user_rol").focus();
                return false;
            }
            Swal.fire({
                title: '¿Desea editar el usuario?',
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
                    document.formularioassigr.submit();
                } else {
                    Swal.fire({
                        title: 'Cancelado',
                        type: 'error'
                    });
                }
            });
        });

        $(document).ready(function() {
            new Choices('.choices-remove-button', {
                removeItemButton: false,
            });
        });
    </script>
@endsection
