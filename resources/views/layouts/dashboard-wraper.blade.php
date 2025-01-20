@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/adminlte/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/showLoadingSpinner.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/toastify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/datatable/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/datatable/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/datatable/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layouts/dashboard-wraper.css') }}">

    @stack('dashboard-wraper.css')
@endpush

@push('jscript')
    <script src="{{ asset('js/plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/plugins/adminlte/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery.cookie.min.js') }}"></script>
    <script src="{{ asset('js/plugins/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/plugins/showLoadingSpinner.js') }}"></script>
    <script src="{{ asset('js/plugins/showToastify.js') }}"></script>
    <script src="{{ asset('js/plugins/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatable/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-autocomplete.min.js') }}"></script>
    <script src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/layouts/dashboard-wraper.js') }}"></script>

    @stack('dashboard-wraper.jscript')
@endpush

@section('content')
<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        @include('components/dashboard-modal-followers')

        @include('components/dashboard-alert')

        @include('components/dashboard-nav')

        <div class="content-wrapper">
            <div class="container">
                @yield('dashboard-wraper.content')
            </div>
        </div>

        {{-- Loading Modalbox - Start --}}
        <div class="modal" id="modal-loading" data-backdrop="static">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <div class="loading-spinner mb-2"></div>
                        <div>Loading</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Loading Modalbox - End --}}
    </div>
</body>
@endsection
