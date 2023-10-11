@if (session('info'))
    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-info"></i> Alerts Informacion!</h5>
        {{ session('info') }}
    </div>
@endif

