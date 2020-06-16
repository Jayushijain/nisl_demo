@extends('layouts.admin')

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
</div>
<!-- /Content area -->

@stop