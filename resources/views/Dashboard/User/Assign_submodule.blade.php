@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="body_scroll">
            @if (session('success'))
                <div class="text-center dur" id="dur"
                    style="border:1px;border-radius:4px;background-color:rgb(21, 199, 21);color: white;position: relative;width:100%;height:60px;">
                    <p style="position: relative;top:18px;font-size:14px;font-weight:bold;">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="text-center dur" id="dur"
                    style="border:1px;border-radius:4px;background-color:rgb(199, 21, 21);color: white;position: relative;width:100%;height:60px;">
                    <p style="position: relative;top:18px;font-size:14px;font-weight:bold;">{{ $errors->first() }}</p>
                </div>
            @endif
            <div class="block-header">
                <div class="row">
                    <div class="col-lg-7 col-md-6 col-sm-12">
                        <h2>Asignar SubModulo</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <i class="zmdi zmdi-settings"></i>
                                <a href="javascript:void(0);">Administracion</a>
                            </li>
                            <li class="breadcrumb-item active">Usuarios</li>
                            <li class="breadcrumb-item active">Asignar SubModulo</li>
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
                                    <form method="POST" action="{{ route('Dashboard.User.Assign_submodule', $user->id) }}" name="formularioreset">
                                        @csrf
                                        <div class="col-md-12">
                                            <h5>Usuario: <b><label>{{ $user->name }}</label></b></h5>
                                        </div>

                                        <div class="col-md-12">

                                                <div class="form-group form-float">
                                                    <label for="formGroupExampleInput" class="mb-1">Modulos Asignados</label>

                                                        <select class="form-control show-tick ms select2 choices-remove-button" id="getmodule"
                                                        name="module_id">
                                                            <option value="moduleall" selected disabled>Seleccione</option>
                                                            @foreach ($modulesdata as $modulesda)
                                                                <option value="{{ $modulesda->id }}">{{ $modulesda->name_modules }}</option>
                                                            @endforeach
                                                        </select>

                                                </div>
                                                <div class="form-group form-float" id="resultquery">
                                                    <label for="formGroupExampleInput" class="mb-1">Seleccione los submodulos a asignar</label>
                                                    <select class="form-control show-tick ms select2 choices-multiple-remove-button"
                                                    placeholder="Seleccione los submodulos que desea asignarle al modulo del usuario" id="sub_modules" name="sub_modules[]" multiple>
                                                    </select>
                                                </div>
                                        </div>
                                        <div class="col-md-12">
                                            <a href="{{ route('Dashboard.User.Index') }}" class="btn btn-dark">Devolver</a>
                                            <button type="button" class="btn btn-primary" id="savemodulsubm">Guardar</button>
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
        $('#dur').not('.alert-important').delay(3000).fadeOut(350);
        $(document).ready(function() {
            new Choices('.choices-remove-button', {
                removeItemButton: false,
            });

            new Choices('.choices-multiple-remove-button', {
                removeItemButton: true,
            });
        });
        //carga del modulo seleccionado
        $("#getmodule").change(function() {
            let idmodule = $("#getmodule").val();
            let iduser = @json($user->id);
            let data = {
                id: idmodule,
                id_user: iduser
            };
            console.log(data)
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('Dashboard.User.Show.SubModule.allsubmodule') }}",
                type: "POST",
                data: JSON.stringify(data),
                contentType: "application/json",
                success: function(response) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        type: 'success',
                        title: '¡Consulta existosa!'
                    })
                    //permite inicializar donde se van a mostrar los submodulos
                    let options_add = "";
                    //resultado de la consulta de todos los submodulos asociados a un modulo seleccionado
                    for (var i = 0; i < response.length; i++) {
                        options_add += "<option value='"+response[i].id+"'>"+response[i].name_submodules+"</option>";
                    }
                    $("#resultquery").html(`<label for="formGroupExampleInput" class="mb-1">Seleccione los submodulos a asignar</label>
                    <select class="form-control show-tick ms select2 choices-multiple-remove-button" id="sub_modules" name="sub_modules[]"
                    placeholder="Seleccione los submodulos que desea asignarle al modulo del usuario" multiple>
                    `+options_add+`</select>`);

                    $(document).ready(function() {
                        let multipleCancelButton = new Choices('.choices-multiple-remove-button', {
                            removeItemButton: true,
                        });
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error)
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });

                    Toast.fire({
                        type: 'error',
                        title: '¡Error en la consulta!'
                    });
                }
            });
        });
        //funcion para guardar la infomacion del modulo, submodulo y el usuario
        $("#savemodulsubm").click(function(e) {
            e.preventDefault();
            let data3 = $(".choices-multiple-remove-button").val();

            if (data3.length == 0) {
                Swal.fire({
                    title: 'Campo Vacio ',
                    text: 'Seleccione los submodulos que desea asignarle al modulo del usuario',
                    type: 'warning'
                })
                $("#moduleall").focus();
                return false;
            }
            Swal.fire({
                title: '¿Desea asignarle los submodulos?',
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
                    document.formularioreset.submit();
                } else {
                    Swal.fire({
                        title: 'Cancelado',
                        type: 'error'
                    });
                }
            });
        });
    </script>
@endsection
