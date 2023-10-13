<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 3 | Forgot Password</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.4.2/css/all.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.4.2/css/sharp-solid.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.4.2/css/sharp-regular.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.4.2/css/sharp-light.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('css/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('css/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('css/plugins/toastr/toastr.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('css/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('css/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('css/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('css/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- Chosen Choise -->
    <link rel="stylesheet" href="{{ asset('css/plugins/chosen-choise/main_choices.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/chosen-choise/chosen.min.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('css/plugins/summernote/summernote-bs4.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- fullCalendar -->
    <link rel="stylesheet" href="{{ asset('css/plugins/fullcalendar/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/fullcalendar-daygrid/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/fullcalendar-timegrid/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/fullcalendar-bootstrap/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/ekko-lightbox/ekko-lightbox.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('css/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>Admin</b>LTE</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <form action="{{ route('password.email') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Request new password</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="/login">Login</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('js/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('js/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('js/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('js/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('js/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('js/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('js/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('js/moment/moment.min.js') }}"></script>
    <script src="{{ asset('js/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('js/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('js/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('js/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('js/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!-- DataTables -->
    <script src="{{ asset('js/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('js/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('js/dist/js/demo.js') }}"></script>

    <script src="{{ asset('js/dist/js/demo.js') }}"></script>
    <!-- jQuery Mapael -->
    <script src="{{ asset('js/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
    <script src="{{ asset('js/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('js/jquery-mapael/jquery.mapael.min.js') }}"></script>
    <script src="{{ asset('js/jquery-mapael/maps/usa_states.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('js/chart.js/Chart.min.js') }}"></script>
    <!-- FLOT CHARTS -->
    <script src="{{ asset('js/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('js/flot-old/jquery.flot.resize.min.js') }}"></script>
    <script src="{{ asset('js/flot-old/jquery.flot.pie.min.js') }}"></script>
    <!-- fullCalendar 2.2.5 -->
    <script src="{{ asset('js/moment/moment.min.js') }}"></script>
    <script src="{{ asset('js/fullcalendar/main.min.js') }}"></script>
    <script src="{{ asset('js/fullcalendar-daygrid/main.min.js') }}"></script>
    <script src="{{ asset('js/fullcalendar-timegrid/main.min.js') }}"></script>
    <script src="{{ asset('js/fullcalendar-interaction/main.min.js') }}"></script>
    <script src="{{ asset('js/fullcalendar-bootstrap/main.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('js/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('js/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('js/toastr/toastr.min.js') }}"></script>
    <!-- Chosen Choise -->
    <script src="{{ asset('js/chosen-choise/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('js/chosen-choise/choices.min.js') }}"></script>

    <script src="{{ asset('js/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
    <script src="{{ asset('js/filterizr/jquery.filterizr.min.js') }}"></script>

    <script src="{{ asset('js/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    </script>

</body>

</html>
