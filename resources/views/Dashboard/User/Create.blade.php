@extends('Templates.Dashboard')

@section('content')
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Registro Usuario</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item">User</li>
                            <li class="breadcrumb-item">Create</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12" style="display: flex; justify-content: center; align-items: center;">
                    <div class="register-box">
                        <div class="register-logo">
                            <a href="#"><b>Admin</b>LTE</a>
                        </div>
                        <div class="card">
                            <div class="card-body register-card-body">
                                <p class="login-box-msg">Register a new membership</p>
                                <form action="../../index.html" method="post">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Full name">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="email" class="form-control" placeholder="Email">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-envelope"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="password" class="form-control" placeholder="Password">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-lock"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="password" class="form-control" placeholder="Retype password">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-lock"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="icheck-primary">
                                                <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                                                <label for="agreeTerms">
                                                    I agree to the <a href="#">terms</a>
                                                </label>
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-4">
                                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                </form>
                                <div class="social-auth-links text-center">
                                    <p>- OR -</p>
                                    <a href="#" class="btn btn-block btn-primary">
                                        <i class="fab fa-facebook mr-2"></i>
                                        Sign up using Facebook
                                    </a>
                                    <a href="#" class="btn btn-block btn-danger">
                                        <i class="fab fa-google-plus mr-2"></i>
                                        Sign up using Google+
                                    </a>
                                </div>
                                <a href="login.html" class="text-center">I already have a membership</a>
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
        $("#save").click(function() {
            Swal.fire({
                title: '¿Desea registrar el usuario?',
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
                    document.formulario1.submit();
                } else {
                    Swal.fire({
                        title: 'Cancelado',
                        type: 'error'
                    });
                }
            });
        });

        $('#dur').not('.alert-important').delay(3000).fadeOut(350);
    </script>
@endsection
