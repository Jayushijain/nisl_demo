@extends('layouts.admin')
@push('head-css-script')
{{-- <script type="text/javascript" src="{{ asset('admin/js/plugins') }}/visualization/d3/d3.min.js"></script>
	<script type="text/javascript" src="{{ asset('admin/js/plugins') }}/visualization/d3/d3_tooltip.js"></script>

	<script type="text/javascript" src="{{ asset('admin/js/core') }}/app.js"></script> --}}
	{{-- <script type="text/javascript" src="{{ asset('admin/js/pages') }}/general_widgets_stats.js"></script> --}}
	@endpush
@section('content')

<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h4></i> <span class="text-semibold">{{ __('messages.dashboard') }}</span></h4>
		</div>
	</div>
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li>
				<a href="{{ url('admin/dashboard') }}"><i class="icon-home2 position-left"></i>{{ __('messages.dashboard') }} </a>
			</li>
		</ul>
	</div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
	<div class="row">
		<div class="col-sm-6 col-md-3">
			<div class="panel panel-body bg-blue-400 has-bg-image">
				<div class="media no-margin">
					<div class="media-body">
						<h3 class="no-margin">{{ $user_count }}</h3>
						<span class="text-uppercase text-size-mini">total Active Users</span>
					</div>

					<div class="media-right media-middle">
						<i class="icon-bubbles4 icon-3x opacity-75"></i>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-md-3">
			<div class="panel panel-body bg-danger-400 has-bg-image">
				<div class="media no-margin">
					<div class="media-body">
						<h3 class="no-margin">{{ $category_count }}</h3>
						<span class="text-uppercase text-size-mini">total categories</span>
					</div>

					<div class="media-right media-middle">
						<i class="icon-bag icon-3x opacity-75"></i>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-md-3">
			<div class="panel panel-body bg-success-400 has-bg-image">
				<div class="media no-margin">
					<div class="media-left media-middle">
						<i class="icon-pointer icon-3x opacity-75"></i>
					</div>

					<div class="media-body text-right">
						<h3 class="no-margin">{{ $product_count }}</h3>
						<span class="text-uppercase text-size-mini">total projects</span>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-md-3">
			<div class="panel panel-body bg-indigo-400 has-bg-image">
				<div class="media no-margin">
					<div class="media-left media-middle">
						<i class="icon-enter6 icon-3x opacity-75"></i>
					</div>

					<div class="media-body text-right">
						<h3 class="no-margin">{{ $role_count }}</h3>
						<span class="text-uppercase text-size-mini">total role</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Content area -->

@stop