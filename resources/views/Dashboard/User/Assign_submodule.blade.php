@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <h1 class="m-0 text-dark">Asignar SubModulo</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">User</li>
                    <li class="breadcrumb-item">Asignar SubModulo</li>
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
                            <h3 class="card-title">Asignar SubModulos de Usuario</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('Dashboard.User.Assign_submodule', $user->id) }}" 
                            name="formularioreset" id="formularioreset" onsubmit="validateAsignSubModule(event, this)">
                                @csrf
                                <div class="col-md-12">
                                    <h5>Usuario: <b><label>{{ $user->name }}</label></b></h5>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group form-float">
                                        <label for="formGroupExampleInput" class="mb-1">Modulos Asignados</label>
                                            <select class="form-control show-tick ms select2 choices-remove-button" id="getmodule"
                                            name="module_id">
                                                <option value="" selected disabled>Seleccione</option>
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

        $("#getmodule").change(function() {
            let id_user = @json($user->id);
            let data = {
                id: $("#getmodule").val(),
                id_user: id_user,
            };
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('Dashboard.User.Show.SubModule.allsubmodule') }}",
                type: "POST",
                data: JSON.stringify(data),
                contentType: "application/json",
                success: function(response) {
                    Toast.fire({
                        icon: 'success',
                        title: '¡Consulta exitosa!'
                    });
                    
                    let options_add = response.map(item => `<option value='${item.id}'>${item.name_submodules}</option>`).join('');

                    $("#resultquery").html(`<label for="formGroupExampleInput" class="mb-1">Seleccione los submodulos a asignar</label>
                    <select class="form-control show-tick ms select2 choices-multiple-remove-button" id="sub_modules" name="sub_modules[]"
                    placeholder="Seleccione los submodulos que desea asignarle al modulo del usuario" multiple>
                    ${options_add}</select>`);

                    $(document).ready(function() {
                        let multipleCancelButton = new Choices('.choices-multiple-remove-button', {
                            removeItemButton: true,
                        });
                    });
                },
                error: function(xhr, status, error) {
                    Toast.fire({
                        icon: 'error',
                        title: '¡Error en la Consulta!'
                    });
                }
            });
        });
        
        function validateAsignSubModule(event, form) {
            event.preventDefault();

            let modulos = $(".choices-remove-button").val();
            let submodulos = $(".choices-multiple-remove-button").val();
            let submit = true;

            if (modulos.length == 0) {
                toastr.warning('Seleccione el modulo para consultar los submodulos.')
                submit = false;
            } else {
                toastr.success('¡Modulo válido! Puede continuar a seleccionar los submodulos.')
            }

            if (submodulos == "") {
                toastr.warning('Seleccione los submodulos que desea asignarle al usuario.')
                submit = false;
            } else {
                toastr.success('¡SubModulos válidos! Puede continuar con la acción solicitada.')
            }
            
            if(submit){
                Swal.fire({
                    title: '¿Desea asignarle los submodulos?',
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
                            title: 'Asignacion de submodulos confirmada.'
                        });
                        form.submit();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Asignacion de submodulos cancelada.'
                        });
                    }
                });
            }
        };

        $(document).ready(function() {
            new Choices('.choices-remove-button', {
                removeItemButton: false,
            });

            new Choices('.choices-multiple-remove-button', {
                removeItemButton: true,
            });
        });
    </script>
@endsection
