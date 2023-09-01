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
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">Roles de Usuario</h3>
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="{{ route('Dashboard.User.Edit', $id) }}" onclick="table.ajax.reload();" data-source-selector="#card-refresh-content">
                                                            <i class="fas fa-sync-alt"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table id="example" class="table table-bordered table-hover dataTable dtr-inline">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Roles</th>
                                                                        <th>Acciones</th>
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

        let table = $('#example').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('Dashboard.User.Edit', $id) }}",
            "columns": [
                {
                    data: 'name',
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return '<button type="button" class="btn btn-primary btn-expand" data-row-id="' + row.id + '">Expandir</button>';
                    },
                    orderable: true,
                    searchable: false,
                },
            ],
        });

        $('#example tbody').on('click', 'button.btn-expand', function () {
            let tr = $(this).closest('tr');
            let row = table.row(tr);
            let rowData = row.data();
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                row.child(formatRoles(rowData.roles)).show();
                tr.addClass('shown');
                $("input[data-bootstrap-switch]").each(function(){
                    $(this).bootstrapSwitch('state');
                    var originalOnChange = $(this).attr('onchange');
                    if (originalOnChange) {
                        $(this).next().find('input[type="checkbox"]').attr('onchange', originalOnChange);
                    }
                });
            }

        });

        function formatRoles(roles) {
            let html = '<table class="table table-bordered table-hover dataTable dtr-inline">';
            roles.forEach(function (role) {
                if(role.asignado){
                    html += `<tr style="width: 50%;"><td>` + role.name + `</td><td><input type="checkbox" name="my-checkbox" checked data-bootstrap-switch data-off-color="danger" data-on-color="success" onchange='unassignRoleByUser({{$id}}, "` + role.name + `", this)'></td></tr>`;
                }else{
                    html += `<tr style="width: 50%;"><td>` + role.name + `</td><td><input type="checkbox" name="my-checkbox" data-bootstrap-switch data-off-color="danger" data-on-color="success" onchange='assignRoleByUser({{$id}}, "` + role.name + `", this)'></td></tr>`;
                }
            });
            html += '</table>';
            return html;
        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function validateFormEdit(event, form) {
            event.preventDefault();

            let name = $.trim($("#name").val());
            let email = $.trim($("#email").val());
            let submit = true;

            if (name == "") {
                toastr.warning('Por favor, complete el campo de usuario.')
                submit = false;
            } else {
                toastr.success('¡Usuario válido! Puede continuar con la acción solicitada.')
            }

            if (email == "") {
                toastr.warning('Por favor, complete el campo correo electronico.')
                submit = false;
            } else if (!isValidEmail(email)) {
                toastr.error('La dirección de correo electrónico ingresada no es válida.')
                submit = false;
            } else {
                toastr.success('¡Correo válido! Puede continuar con la acción solicitada.')
            }


            if (submit) {
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

        function assignRoleByUser(user, rol, button) {
            let data = {
                "id_user": user,
                "rol_name": rol
            };
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('Dashboard.User.AssignRole') }}",
                type: "POST",
                data: JSON.stringify(data),
                contentType: "application/json",
                success: function (respuesta) {
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: 'Accion Exitosa',
                        body: 'Se asignó el rol al usuario correctamente,'
                    })
                },
                error: function(xhr, status, error) {
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Accion Fallida',
                        body: 'Usted no tiene permisos para asignar roles.'
                    })
                }
            });
            let currentOnChange = button.getAttribute('onchange');
            let newOnChange = currentOnChange.replace('assign', 'unassign');
            button.setAttribute('onchange', newOnChange);
        }

        function unassignRoleByUser(user, rol, button) {
            let data = {
                "id_user": user,
                "rol_name": rol
            };
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('Dashboard.User.UnassignRole') }}",
                type: "POST",
                data: JSON.stringify(data),
                contentType: "application/json",
                success: function (respuesta) {
                    $(document).Toasts('create', {
                        class: 'bg-warning',
                        title: 'Accion Exitosa',
                        body: 'Se desasignó el rol al usuario correctamente,'
                    })
                },
                error: function(xhr, status, error) {
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Accion Fallida',
                        body: 'Usted no tiene permisos para desasignar roles.'
                    })
                }
            });
            let currentOnChange = button.getAttribute('onchange');
            let newOnChange = currentOnChange.replace('unassign', 'assign');
            button.setAttribute('onchange', newOnChange);
        }
    </script>
@endsection
