@extends('layouts.dashboard-wraper')

@push('dashboard-wraper.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/d3/7.8.4/d3.min.css">
@endpush

@push('dashboard-wraper.jscript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/7.8.4/d3.min.js"></script>
    <script src="{{ asset('js/pages/dashboard_upload/dashboard_upload.js') }}"></script>
    <script src="{{ asset('js/pages/dashboard_upload/tab_upload_data.js') }}"></script>
    <script src="{{ asset('js/pages/dashboard_upload/tab_mutual.js') }}"></script>
    <script src="{{ asset('js/pages/dashboard_upload/tab_mutual_detail.js') }}"></script>
    <script src="{{ asset('js/pages/dashboard_upload/tab_mutual_followers.js') }}"></script>
    <script src="{{ asset('js/pages/dashboard_upload/tab_nodes_edges.js') }}"></script>
@endpush

@section('dashboard-wraper.content')
    <div id="dashboard-upload-page" class="content mt-4">
		<div class="container-fluid">
            <div class="row px-2">
                <div class="col">
                    <div class="card card-secondary card-outline card-tabs">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link disabled {{$dataTab->tab_active == 'tab_upload_data' ? 'active' : ''}}" id="linktab_upload_data" data-toggle="pill" href="#tab_upload_data" role="tab">
                                        <i class="far {{$dataTab->tab_upload_data == 'finish' ? 'fa-check-circle' : 'fa-circle'}}"></i>
                                        Upload Data
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled {{$dataTab->tab_active == 'tab_mutual' ? 'active' : ''}}" id="linktab_mutual" data-toggle="pill" href="#tab_mutual" role="tab">
                                        <i class="far {{$dataTab->tab_mutual == 'finish' ? 'fa-check-circle' : 'fa-circle'}}"></i>
                                        Mutual
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled {{$dataTab->tab_active == 'tab_mutual_detail' ? 'active' : ''}}" id="linktab_mutual_detail" data-toggle="pill" href="#tab_mutual_detail" role="tab">
                                        <i class="far {{$dataTab->tab_mutual_detail == 'finish' ? 'fa-check-circle' : 'fa-circle'}}"></i>
                                        Mutual Detail
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled {{$dataTab->tab_active == 'tab_mutual_followers' ? 'active' : ''}}" id="linktab_mutual_followers" data-toggle="pill" href="#tab_mutual_followers" role="tab">
                                        <i class="far {{$dataTab->tab_mutual_followers == 'finish' ? 'fa-check-circle' : 'fa-circle'}}"></i>
                                        Mutual Followers
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled {{$dataTab->tab_active == 'tab_node_graph' ? 'active' : ''}}" id="linktab_node_graph" data-toggle="pill" href="#tab_node_graph" role="tab">
                                        <i class="far {{$dataTab->tab_node_graph == 'finish' ? 'fa-check-circle' : 'fa-circle'}}"></i>
                                        Node & Graph
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                @include('pages/dashboard_upload/tab_upload_data')

                                @include('pages/dashboard_upload/tab_mutual')
                                
                                @include('pages/dashboard_upload/tab_mutual_detail')
                                
                                @include('pages/dashboard_upload/tab_mutual_followers')
                                
                                @include('pages/dashboard_upload/tab_node_graph')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
