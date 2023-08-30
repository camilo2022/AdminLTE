@extends('Templates.Dashboard')
@section('content')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>500 Error Page</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">500</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="error-page">
      <h2 class="headline text-danger"> 500</h2>

      <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Something went wrong.</h3>

        <p>
          We will work on fixing that right away.
          Meanwhile, you may <a href="{{ route('home') }}">return to dashboard</a> or try using the search form.
        </p>

        <form class="search-form">
          <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search">

            <div class="input-group-append">
              <button type="submit" name="submit" class="btn btn-danger"><i class="fas fa-search"></i>
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
@endsection
@section('script')

@endsection
