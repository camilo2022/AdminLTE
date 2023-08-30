@extends('Templates.Dashboard')

@section('content')

    <section class="content"
        style="background: linear-gradient(to bottom, #090920, #345dbda9);position: relative;height:70vh;top:-35px; ">

        <div class="authentication">
            <div class="container">

                <div class="row" style="position: relative;top:20px;">
                    <div class="col-lg-4 col-sm-12">
                        <form method="POST" action=" {{ route('Dashboard.User.Store') }} "
                            class="card auth_form mt-4" name="formulario1">

                            @csrf

                            <div class="header">
                                <img class="logo" src="{{ asset('images/icon_logo.png') }}"
                                    style="position: relative;width:30%;" alt="">

                            </div>
                            <div class="body ">

                                <div class="form-group form-float">
                                    <label for="formGroupExampleInput" class="mb-1">Nombre
                                        completo</label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}" autocomplete="name" autofocus
                                        placeholder="Digite su nombre">

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>

                                <div class="form-group form-float ">
                                    <label for="formGroupExampleInput" class="mb-1">Correo
                                        eletronico</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" autocomplete="email"
                                        placeholder="Digite el correo">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>


                                <div class="form-group form-float">
                                    <label for="formGroupExampleInput" class="mb-1">Contraseña</label>
                                    <input id="password" type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        autocomplete="new-password" placeholder="Ingrese Contraseña">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>

                                <div class="form-group form-float">
                                    <label for="formGroupExampleInput" class="mb-1">Confirmar contraseña</label>
                                    <input id="password-confirm" type="password" name="password_confirmation"
                                        class="form-control" autocomplete="new-password"
                                        placeholder="Confirmacion de contraseña">

                                </div>

                                <button type="submit" class="btn btn-primary btn-block waves-effect waves-light" id="save">
                                    {{ __('Registrar') }}
                                </button>

                            </div>
                        </form>

                    </div>
                   {{--  <div class="col-lg-8 col-sm-12">
                        <div class="card">
                            <img src="{{ asset('img/signup.svg') }}" alt="Sign Up" />
                        </div>
                    </div> --}}
                </div>


            </div>
        </div>


    </section>

    {{-- @if (count($errors) > 0)
        <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            Corrige los siguientes errores:
            <ul>
                @foreach ($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>

        </div>
    @endif --}}


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
