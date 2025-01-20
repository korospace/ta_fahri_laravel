@extends('layouts.dashboard-wraper')

@push('dashboard-wraper.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/d3/7.8.4/d3.min.css">
@endpush

@push('dashboard-wraper.jscript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/7.8.4/d3.min.js"></script>
    <script src="{{ asset('js/pages/dashboard_overview/dashboard_overview.js') }}"></script>
@endpush

@section('dashboard-wraper.content')
    <div class="content mt-4">
		<div class="container-fluid">
            <div class="row px-2">
                <div class="col">
                    <div class="card card-secondary card-outline">
                        <div class="card-body p-5">
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="text-bold mb-3" style="color: #343A40;">Centrality Measure</h3>
                                    <div class="card card-secondary card-outline card-tabs">
                                        <div class="card-body">
                                            <table id="table-centrality-measure" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="align-middle">
                                                            No
                                                        </th>
                                                        <th class="align-middle">
                                                            Username
                                                        </th>
                                                        <th class="align-middle">
                                                            Degree Centrality
                                                        </th>
                                                        <th class="align-middle">
                                                            Betweeness Centrality
                                                        </th>
                                                        <th class="align-middle">
                                                            Closeness Centrality
                                                        </th>
                                                        <th class="align-middle">
                                                            Eigenvector Centrality
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-5 mt-5">
                                    <hr>
                                </div>
                                <div class="col-12" style="max-width: 100%;overflow: auto;">
                                    <h3 class="text-bold mb-3" style="color: #343A40;">Visualization Nodes & Edges</h3>

                                    <div class="card card-secondary card-outline card-tabs">
                                        <div class="card-body" style="max-width: 100%;overflow: auto;">
                                            <div id="graph-container"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
