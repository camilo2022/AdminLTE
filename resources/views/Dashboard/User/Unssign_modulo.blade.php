@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="body_scroll">

            <div class="block-header">
                <div class="row">
                    <div class="col-lg-7 col-md-6 col-sm-12">
                        <h2>Remover Modulo</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <i class="zmdi zmdi-settings"></i>
                                <a href="javascript:void(0);">Administracion</a>
                            </li>
                            <li class="breadcrumb-item active">Usuarios</li>
                            <li class="breadcrumb-item active">Remover Modulo</li>
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
                                <form action="{{ route('Dashboard.User.Unssign_module', $user->id) }}" name="formularioassigr"
                                    id="formularioassigr" method="post">

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
                                        <button type="submit" class="btn btn-primary" id="algo">Guardar</button>
                                    </div>
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

        $("#algo").click(function() {

            let module_rem = $("#remmodules").val();

            if (module_rem.length == 0) {
                Swal.fire({
                    title: 'Campo Vacio ',
                    text: 'Seleccione los modulos que desea removerle al usuario.',
                    type: 'warning'
                })
                $("#remmodules").focus();
                return false;
            }
            Swal.fire({
                title: '¿Desea remover los modulos?',
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
            new Choices('.choices-multiple-remove-button', {
                removeItemButton: true,
            });
        });
    </script>
@endsection
