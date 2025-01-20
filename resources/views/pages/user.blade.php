@extends('layouts.dashboard-wraper')

@push('dashboard-wraper.css')
	<!-- Your CSS Here -->
@endpush

@push('dashboard-wraper.jscript')
    <script src="{{ asset('js/pages/user.js') }}"></script>
@endpush

@section('dashboard-wraper.content')
    @include('components/dashboard-head', ['headTitle' => 'Master User', 'headPage' => 'User'])

	<div class="content mt-4">
		<div class="container-fluid">
            {{-- Button Row --}}
            <div class="row mb-4 px-2">
				<div class="col-12 d-flex justify-content-end">
                    <a href="{{ route('user.create') }}" class="btn btn-block btn-secondary btn-md" style="width: max-content;">
						<i class="fa fa-plus"></i> &nbsp; tambah
                    </a>
				</div>
			</div>

            <div class="row px-2">
				<div class="col-12">
                    <div class="card card-secondary card-outline">
						<div class="card-header">
							<table id="tableUser" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>
											No
										</th>
										<th>
											Email
										</th>
										<th>
											Nama
										</th>
										<th>
											Akses
										</th>
										<th>
											Action
										</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
@endsection
