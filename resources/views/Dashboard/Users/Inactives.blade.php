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
    <script src="{{ asset('js/Dashboard/Users/DataTableInactives.js') }}"></script>
    <script src="{{ asset('js/Dashboard/Users/Restore.js') }}"></script>
    <script>
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
