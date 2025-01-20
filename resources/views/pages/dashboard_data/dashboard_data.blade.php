@extends('layouts.dashboard-wraper')

@push('dashboard-wraper.css')
@endpush

@push('dashboard-wraper.jscript')
    <script src="{{ asset('js/pages/dashboard_data/table_followers.js') }}"></script>
    <script src="{{ asset('js/pages/dashboard_data/table_following.js') }}"></script>
    <script src="{{ asset('js/pages/dashboard_data/table_mutual.js') }}"></script>
@endpush

@section('dashboard-wraper.content')
    <div class="content mt-4">
		<div class="container-fluid">
            <div class="row px-2">
                <div class="col">
                    <div class="card card-secondary card-outline">
                        <div class="card-body p-5">
                            <div class="row">
                                <div class="col-12 mb-5">
                                    @include('pages/dashboard_data/table_followers')
                                </div>
                                <div class="col-12 mb-5">
                                    @include('pages/dashboard_data/table_following')
                                </div>
                                <div class="col">
                                    @include('pages/dashboard_data/table_mutual')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
