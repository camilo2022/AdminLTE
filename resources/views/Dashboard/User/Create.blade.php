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
                            <a href="{{ route('Dashboard') }}"><b>Admin</b>LTE</a>
                        </div>
                        <div class="card">
                            <div class="card-body register-card-body">
                                <p class="login-box-msg">Register a new membership</p>
                                <form action="{{ route('Dashboard.User.Store') }}" name="createUser"
                                    name="createUser" method="post" class="mt-2" onsubmit="validateFormCreateUser(event, this)">
                                    @csrf
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Full name">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-envelope"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-lock"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Retype password">
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
                                <a href="#" class="text-center">I already have a membership</a>
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

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        function validateFormCreateUser(event, form){
            event.preventDefault();

            let name = $.trim($("#name").val());
            let email = $.trim($("#email").val());
            let password = $.trim($("#password").val());
            let password_confirmation = $.trim($("#password_confirmation").val());
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

            if (password.length == "") {
                toastr.warning('Por favor, complete el campo contraseña.')
                submit = false;
            } else if (password.length < 8) {
                toastr.error('La contraseña ingresada no es válida, debe tener al menos 8 caracteres.')
                submit = false;
            } else {
                toastr.success('Contraseña valida! Puede continuar con la acción solicitada.')
            }

            if (password_confirmation.length == "") {
                toastr.warning('Por favor, confirme la contraseña ingresada.')
                submit = false;
            } else if (password_confirmation != password) {
                toastr.error('Las contraseñas no coinciden. Valide la contraseña.')
                submit = false;
            } else {
                toastr.success('Confirmacion contraseña valida! Puede continuar con la acción solicitada.')
            }

            if(submit) {
                Swal.fire({
                    title: '¿Desea registrar el usuario?',
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
                            title: 'Creacion de usuario confirmada.'
                        });
                        form.submit();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Creacion de usuario cancelada.'
                        });
                    }
                });
            }
        };

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    </script>
@endsection
