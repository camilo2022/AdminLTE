<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 3 | Dashboard</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/dist/css/adminlte.min.css') }}">

    <style>
        .centrado {
            position: relative;
        }
        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, 100%);
        }
    </style>
</head>
<body>
    <div class="centrado">
        <section class="content">
            <div class="error-page">
            <h2 class="headline text-info"> 403</h2>

            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-info"></i> This action is not authorized.</h3>

                <p>
                We could not authorize this action. Please log in with the correct credentials to proceed.
                Meanwhile, you may <a href="{{ route('home') }}">return to dashboard</a> or try using the search form.
                </p>

                <form class="search-form">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search">

                    <div class="input-group-append">
                    <button type="submit" name="submit" class="btn btn-info"><i class="fas fa-search"></i>
                    </button>
                    </div>
                </div>
                <!-- /.input-group -->
                </form>
            </div>
            <!-- /.error-content -->
            </div>
            <!-- /.error-page -->
        </section>
    </div>
</body>
</html>

