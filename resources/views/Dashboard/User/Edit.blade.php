@extends('Templates.Dashboard')
@section('content')
    <section class="content">
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
                            <li class="breadcrumb-item">Edit</li>
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
                        <div class="card-header">
                            <h3 class="card-title">Asignacion de Rol de Usuario</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('Dashboard.User.Update', $user->id) }}" name="formAsignRole"
                                id="formAsignRole" method="post" class="mt-2" onsubmit="validateFormEdit(event, this)">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="formGroupExampleInput">Nombre del usuario</label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                value="{{ $user->name }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="formGroupExampleInput">Correo del usuario</label>
                                            <input type="text" class="form-control" name="email" id="email"
                                                value="{{ $user->email }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="user_rol">Rol del usuario</label>
                                            <select class="form-control show-tick ms select2 choices-remove-button"
                                                id="user_rol" name="rol">
                                                <option value="" selected disabled>Seleccionar</option>
                                                @forelse ($roles as $rol)
                                                    <option value="{{ $rol->name }}"
                                                        @if (isset($user->roles[0]) && $user->roles[0]->id == $rol->id) selected @endif>
                                                        {{ $rol->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('Dashboard.User.Index') }}" class="btn btn-secondary">Devolver</a>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function validateFormEdit(event, form) {
            event.preventDefault();

            let name = $.trim($("#name").val());
            let email = $.trim($("#email").val());
            let rol = $("#rol").val();
            let submit = true;

            if (name == "") {
                toastr.warning('Por favor, complete el campo de usuario.')
                submit = false;
            } else {
                toastr.success('¡Usuario válido! Puede continuar con la acción solicitada.')
            }

            if (email == "") {
                toastr.warning('Por favor, complete el campo de correo.')
                submit = false;
            } else if (!isValidEmail(email)) {
                toastr.error('La dirección de correo electrónico ingresada no es válida.')
                submit = false;
            } else {
                toastr.success('¡Correo válido! Puede continuar con la acción solicitada.')
            }
            if (rol == "") {
                toastr.warning('Por favor, complete el campo de rol.')
                submit = false;
            } else {
                toastr.success('¡Rol válido! Puede continuar con la acción solicitada.')
            }

            if (submit) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });

                Swal.fire({
                    title: '¿Desea editar el usuario?',
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
                            title: 'Edicion de Usuario confirmada.'
                        })
                        form.submit();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Edicion de Usuario cancelada.'
                        })
                    }
                });
            }

        };

        $(document).ready(function() {
            new Choices('.choices-remove-button', {
                removeItemButton: false,
            });
        });
    </script>
@endsection
