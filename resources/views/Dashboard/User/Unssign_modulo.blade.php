@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Remover Modulo</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item">User</li>
                            <li class="breadcrumb-item">Remover Modulo</li>
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
                            <h3 class="card-title">Remove Modulos de Usuario</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('Dashboard.User.Unssign_module', $user->id) }}" name="formularioassigr"
                                id="formularioassigr" method="post" onsubmit="validateUnsignModule(event, this)">

                                @csrf
                                <div class="col-md-12">
                                    <h5>Usuario: <b><label>{{ $user->name }}</label></b></h5>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" id="resultquery">
                                        <label for="user_rol">Seleccione los modulos a remover</label></label>
                                        <select class="form-control show-tick ms select2 choices-multiple-remove-button"
                                            placeholder="Seleccione los modulos que desea removerle al usuario"
                                            id="remmodules" name="remmodules[]" multiple>
                                            @foreach ($mod_noasig as $modulo)
                                                <option value="{{ $modulo->id }}">{{ $modulo->name_modules }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 mt-4">
                                    <a href="{{ route('Dashboard.User.Index') }}" class="btn btn-secondary">Devolver</a>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
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

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        function validateUnsignModule(event, form) {
            event.preventDefault();

            let module_rem = $("#remmodules").val();
            let submit = true;

            if (module_rem.length == 0) {
                toastr.warning('Seleccione los modulos que desea removerle al usuario.')
                submit = false;
            }

            if (submit) {
                Swal.fire({
                    title: '¿Desea remover los modulos?',
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
                            title: 'Asignacion de modulos confirmada.'
                        });
                        form.submit();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Desasignación de modulos cancelada.'
                        });
                    }
                });
            }
        };

        $(document).ready(function() {
            new Choices('.choices-multiple-remove-button', {
                removeItemButton: true,
            });
        });
    </script>
@endsection
