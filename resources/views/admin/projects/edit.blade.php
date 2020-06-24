@extends('layouts.admin')

@section('content')

<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h4>
				<span class="text-semibold">{{ __('messages.edit_project') }}</span>
			</h4>
		</div>
	</div>
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li>
				<a href="{{ route('dashboard') }}"><i class="icon-home2 position-left"></i>{{ __('messages.dashboard') }}</a>	
			</li>
			<li>
				<a href="{{ route('projects.index') }}">{{ __('messages.projects') }}</a>
			</li>
			<li class="active">{{ __('messages.edit') }}</li>
		</ul>
	</div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
	<form action="{{ route('projects.update',$project->id) }}" id="projectform" method="POST">
		{{ method_field('PATCH') }}
		{{ csrf_field() }}
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<!-- Panel -->
				<div class="panel panel-flat">
					<!-- Panel heading -->
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-10">
								<h5 class="panel-title">
									<strong>{{ __('messages.projects') }}</strong>
								</h5>
							</div>
						</div>
					</div>
					<!-- /Panel heading -->
					<!-- Panel body -->
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<small class="req text-danger">*</small>
									<label>{{ __('messages.project_name') }}:</label>
									<input type="text" class="form-control" placeholder="{{ __('messages.project_name') }}" id="name" name="name" value="{{ $project->name }}">
								</div>
								<div class="form-group">						
									<label>{{ __('messages.description') }}:</label>
									<input type="text" class="form-control" placeholder="{{ __('messages.description') }}" id="details" name="details" value="{{ $project->details }}">
								</div>
							</div>
						</div>
					</div>
					<!-- /Panel body -->
				</div>
				<!-- /Panel -->
			</div>
		</div>
		<div class="btn-bottom-toolbar text-right btn-toolbar-container-out">
		<button type="submit" class="btn btn-success">{{ __('messages.save') }}</button>
		<a class="btn btn-default" onclick="window.history.back();">{{ __('messages.back') }}</a>
		</div>
	</form>
</div>
<!-- /Content area -->

<script type="text/javascript">
$("#projectform").validate({
	rules: {
		name:
		{
			required: true,
		},	
	},
	messages: {
		name: 
		{
			required:"{{ __('messages.please_enter_',['Name' =>  __('messages.project_name')]) }}",
		},
	}
});  
</script>

@stop