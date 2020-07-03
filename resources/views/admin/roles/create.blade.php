@extends('layouts.admin')

@section('content')

<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h4>
				<span class="text-semibold">{{ __('messages.add_role') }}</span>
			</h4>
		</div>
	</div>
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li>
				<a href="{{ route('dashboard') }}"><i class="icon-home2 position-left"></i>{{ __('messages.dashboard') }}</a>
			</li>
			<li>
				<a href="{{ route('roles.index') }}">{{ __('messages.roles') }}</a>
			</li>
			<li class="active">{{ __('messages.add') }}</li>
		</ul>
	</div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
	<form method="POST" action="{{ route('roles.store') }}" id="roleform">
		{{ csrf_field() }}
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<!-- Panel -->
				<div class="panel panel-flat">
					<!-- Panel heading -->
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-10">
								<h5 class="panel-title"><strong>{{ __('messages.roles') }}</strong></h5>
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
									<label>{{ __('messages.role_name') }}:</label>
									<input type="text" class="form-control" placeholder="{{ __('messages.role_name') }}" id="name" name="name">
								</div>
								<div>@include('admin.roles.permissions')</div>
							<div id="validation_msg"></div>
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

@stop

@section('scripts')
<script type="text/javascript">

$("#roleform").validate({
	rules: {
		name: {
			required: true,
		},   	
	},
	messages: {
		name: {
			required:"{{ __('messages.please_enter_',['Name' => __('messages.role_name')]) }}",
		},
	}
});  


function select_permissions() 
{ 
    var check_permission = $(".permission").serializeArray(); 
    if (check_permission.length === 0) 
    { 
         $("#validation_msg").html("<p style='color:red'>{{ __('messages.please_select_some_role_permissions') }}</p>");
        return false;
    } 
}

$('#roleform').submit(select_permissions)
</script>
@stop

