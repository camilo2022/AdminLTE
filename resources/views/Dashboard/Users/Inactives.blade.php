@extends('Templates.Dashboard')
@section('content')
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Usuarios Inactivos</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item">User</li>
                            <li class="breadcrumb-item">Inactives</li>
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
                                <li class="nav-item">
                                    <a class="nav-link active" href="" title="Agregar usuario">
                                        <i class="fas fa-user-plus"></i>
                                    </a>
                                </li>
                                <li class="nav-item ml-auto">
                                    <a class="nav-link" href="/Dashboard/Users/Index" title="Usuarios activos">
                                        <i class="fas fa-user-check"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="/Dashboard/Users/Inactives" title="Usuarios inactivos">
                                        <i class="fas fa-user-xmark"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="users" class="table table-bordered table-hover dataTable dtr-inline">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th colspan="7">Información Personal</th>
                                            <th colspan="1">Gestionar Usuario</th>
                                        </tr>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Documento</th>
                                            <th>Telefono</th>
                                            <th>Direccion</th>
                                            <th>Email</th>
                                            <th>Restaurar</th>
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
@endsection
@section('script')
    <script>

        let tableUsers = $('#users').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/Dashboard/Users/Inactives/Query",
                "type": "POST",
                "data": function (request) {
                    var columnMappings = {
                        0: 'id',
                        1: 'name',
                        2: 'last_name',
                        3: 'document_number',
                        4: 'phone_number',
                        5: 'address',
                        6: 'email'
                    };
                    request._token = "{{ csrf_token() }}";
                    request.perPage = request.length;
                    request.page = (request.start / request.length) + 1;
                    request.search = request.search.value;
                    request.column = columnMappings[request.order[0].column];
                    request.dir = request.order[0].dir;
                },
                "dataSrc": function (response) {
                    return response.data.users;
                }
            },
            "columns": [
                { data: 'id' },
                { data: 'name' },
                { data: 'last_name' },
                { data: 'document_number' },
                { data: 'phone_number' },
                { data: 'address' },
                { data: 'email' },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<a class="btn btn-info btn-sm" onclick="RestoreUser(${data.id})"
                            title="Restaurar usuario">
                                <i class="fas fa-user-plus text-white"></i>
                            </a>`;
                    }
                },
            ],
            "columnDefs": [
                { "orderable": true, "targets": [0, 1, 2, 3, 4, 5, 6] },
                { "orderable": false, "targets": [7] }
            ],
            "pagingType": "full_numbers",
            "language": {
                oPaginate: {
                    sFirst: "Primero",
                    sLast: "Último",
                    sNext: "Siguiente",
                    sPrevious: "Anterior",
                    sProcessing: "Procesando...",
                },
                "emptyTable": "No hay datos disponibles.",
                "lengthMenu": "Mostrar _MENU_ registros por página.",
                "search": "Buscar:",
                "zeroRecords": "No se encontraron registros coincidentes.",
                "decimal" : ",",
                "thousands": ".",
                "sEmptyTable" : "No se ha llamado información o no está disponible.",
                "sZeroRecords" : "No se encuentran resultados.",
            },
            "pageLength": 10,
            "lengthMenu": [10, 25, 50, 100],
            "paging": true,
            "info": false,
            "searching": true
        });

        function RestoreUser(id) {
            Swal.fire({
                title: '¿Desea restaurar el usuario?',
                text: 'El usuario será restaurado.',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#DD6B55',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Si, restaurar!',
                cancelButtonText: 'No, cancelar!',
                closeOnConfirm: false,
                closeOnCancel: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/Dashboard/Users/Restore',
                        type: 'PUT',
                        data: {
                            '_token': "{{ csrf_token() }}",
                            'id': id
                        },
                        success: function(response) {
                            tableUsers.ajax.reload();
                            toastr.success(response.message)
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            tableUsers.ajax.reload();
                            if(xhr.responseJSON.error){
                                toastr.error(xhr.responseJSON.error.message)
                            }
                            if(xhr.responseJSON.errors){
                                $.each(xhr.responseJSON.errors, function(field, messages) {
                                    $.each(messages, function(index, message) {
                                        toastr.error(message)
                                    });
                                });
                            }
                        }
                    });
                } else {
                    toastr.error('El usuario seleccionado no fue restaurado.')
                }
            });
        }

        @if (session('success'))
            toastr.success(' {{ session('success') }} ')
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error(' {{ $error }} ')
            @endforeach
        @endif


    </script>
@endsection
