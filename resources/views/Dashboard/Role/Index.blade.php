@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Roles</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item">Rol</li>
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
                                @role('RoleCreate')
                                    <li class="nav-item">
                                        <a type="button" class="btn btn-primary text-white" data-toggle="modal"
                                            data-target="#modal_rol">
                                            Crear Rol
                                        </a>
                                    </li>
                                @endrole
                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table table-bordered table-hover dataTable dtr-inline">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Permisos</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>
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
    @include('Dashboard.Role.Create')
    @include('Dashboard.Role.Edit')
@endsection
@section('script')
    <script>

        let accessCreateRol = new Choices('#access', {
            removeItemButton: false,
        });

        let accessEditRol = new Choices('#access_e', {
            removeItemButton: false,
        });

        let table = $('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('Dashboard.Rol.Index') }}",
            "columns": [
                {
                    data: 'id',
                },
                {
                    data: 'name',
                },
                {
                    data: 'permissions',
                    render: function (permissions) {
                        return permissions.map(permission => `<span class="badge badge-info">${permission.name}</span>`).join(' ');
                    }
                },
                {
                    data: 'btnEdit',
                },
                {
                    data: 'btnDelete',
                },
            ],
        });

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        function createRol() {

            let name = $.trim($("#name").val());
            let access = $.trim($("#access").val());
            let submit = true;

            if (name == "") {
                toastr.warning('Por favor, complete el campo nombre de rol.')
                submit = false;
            }  else {
                toastr.success('Rol valido! Puede continuar con la acción solicitada.')
            }

            if (access == "") {
                toastr.warning('Por favor, complete el campo acceso de rol.')
                submit = false;
            }  else {
                toastr.success('Acceso valido! Puede continuar con la acción solicitada.')
            }

            if(submit){
                Swal.fire({
                    title: '¿Desea guardar el rol?',
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
                        let data = {
                            "name": name,
                            "access": access
                        };

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ route('Dashboard.Rol.Store') }}",
                            type: "POST",
                            data: JSON.stringify(data),
                            contentType: "application/json",
                            success: function (respuesta) {
                                $(document).Toasts('create', {
                                    class: 'bg-success',
                                    title: 'Accion Exitosa',
                                    body: respuesta
                                })
                            },
                            error: function(xhr, status, error) {
                                $(document).Toasts('create', {
                                    class: 'bg-danger',
                                    title: 'Accion Fallida',
                                    body: 'Usted no tiene permisos para crear roles.'
                                })
                            }
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Creacion de rol cancelada.'
                        });
                    }
                    table.ajax.reload();
                    accessCreateRol.setValue("Seleccione");
                    $("#name").val("");
                });
            }
        };

        function updateRol(route) {

            let name = $.trim($("#name").val());
            let submit = true;

            if (name == "") {
                toastr.warning('Por favor, complete el campo nombre de rol.')
                submit = false;
            }  else {
                toastr.success('Rol valido! Puede continuar con la acción solicitada.')
            }

            if(submit){
                Swal.fire({
                    title: '¿Desea guardar el rol?',
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
                        let data = {
                            "name": name
                        };

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: route,
                            type: "POST",
                            data: JSON.stringify(data),
                            contentType: "application/json",
                            success: function (respuesta) {
                                $(document).Toasts('create', {
                                    class: 'bg-success',
                                    title: 'Accion Exitosa',
                                    body: respuesta
                                })
                            },
                            error: function(xhr, status, error) {
                                $(document).Toasts('create', {
                                    class: 'bg-danger',
                                    title: 'Accion Fallida',
                                    body: 'Usted no tiene permisos para crear roles.'
                                })
                            }
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Creacion de rol cancelada.'
                        });
                    }
                    table.ajax.reload();
                    accessCreateRol.setValue("Seleccione");
                    $("#name").val("");
                });
            }
        };

        function editRole(id, name, access) {
            $("#name_e").val(name);
            accessEditRol.setChoices(@json($accesses), 'id', 'name', false);
            accessEditRol.setValue(access);
            let updateUrl = "{{ route('Dashboard.Rol.Update', ':id') }}";
            let url = updateUrl.replace(':id', id);
            document.querySelector('#edit_rol').setAttribute("onclick","updateRole('"+url+"')")
        }

        function deleteRole(route) {
            swal.fire({
                title: '¿Desea eliminar el rol?',
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
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: route,
                        type: "POST",
                        contentType: "application/json",
                        success: function (respuesta) {
                            $(document).Toasts('create', {
                                class: 'bg-success',
                                title: 'Accion Exitosa',
                                body: respuesta
                            })
                        },
                        error: function(xhr, status, error) {
                            $(document).Toasts('create', {
                                class: 'bg-danger',
                                title: 'Accion Fallida',
                                body: 'Usted no tiene permisos para eliminar roles.'
                            })
                        }
                    });
                }  else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Eliminacion de rol cancelada.'
                    });
                }
                table.ajax.reload();
            });
        }
    </script>
@endsection
