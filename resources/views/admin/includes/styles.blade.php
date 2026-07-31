<!-- VENDOR CSS -->
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/animate-css/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/chartist/css/chartist.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.css') }}">



<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css') }}" />
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/multi-select/css/multi-select.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/nouislider/nouislider.min.css') }}" />

<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css') }}">

<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/sweetalert/sweetalert.css') }}"/>
<link rel="stylesheet" href="{{ asset('public/admin/semantic.min.css') }}">


<!-- MAIN CSS -->
<link rel="stylesheet" href="{{ asset('public/admin/assets/css/main.css?321') }}">
<link rel="stylesheet" href="{{ route('admin.css.change') }}">
<!-- <link rel="stylesheet" href="/public/admin/assets/css/color_skins.css"> -->

<link href="{{ asset('public/toastr/toastr.css') }}" rel="stylesheet" />
<script src="{{ asset('public/toastr/jquery-3.6.0.min.js') }}" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="{{ asset('public/toastr/popper.min.js') }}" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="{{ asset('public/toastr/toastr.js') }}"></script>
<link rel="stylesheet" href="{{ asset('public/preloader.css') }}">

<!--<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'>-->
<style>
    .bootstrap-tagsinput .tag {
        margin-right: 2px;
        color: #1b1a1a;
        background-color: #6f82ff;
    }
    .title {
        margin-right: 10px;
    }
</style>



<script>
        $(document).ready(function() {
            toastr.options.timeOut = 1000;
            @if (Session::has('error'))
                toastr.error('{{ Session::get('error') }}');
            @elseif(Session::has('success'))
                toastr.success('{{ Session::get('success') }}');
            @endif
        });

</script> 