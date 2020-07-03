@extends('layouts.admin')

@section('content')

<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h4>
				<span class="text-semibold">{{ __('messages.edit_role') }}</span>
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
			<li class="active">{{ __('messages.edit') }}</li>
		</ul>
	</div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
	<form method="POST" action="{{ route('roles.update',$role->id) }}" id="roleform">
		{{ method_field('PATCH') }}
		{{ csrf_field() }}
		<div class="row">
		<div class="row">
			<div class="col-md-6">
				<!-- Panel -->
				<div class="panel panel-flat">
					<!-- Panel heading -->
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-12">
								<h5 class="pull-left"><strong>{{ __('messages.edit_role') }}: {{ $role->name }}</strong></h5>
								@if (has_permissions('roles','create'))
								<a href="{{ route('roles.create') }}" class="btn btn-primary pull-right">{{ __('messages.add_new') }}</a>
								@endif
							</div>											
						</div>
						<hr/>
					</div>
					<!-- /Panel heading -->
					<!-- Panel body -->
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="alert alert-warning alert-styled-left">
										<span>{{ __('messages.edit_role_warning_alert') }}</span>
									</div>
								</div>
								<div class="form-group">
									<small class="req text-danger">*</small>
									<label>{{ __('messages.role_name') }}:</label>
									<input type="text" class="form-control" placeholder="{{ __('messages.role_name') }}" id="name" name="name" value="{{ $role->name }}">
								</div>
								<div>@include('admin.roles.permissions')
								</div>
								<div id="validation_msg"></div>
							</div>
						</div>
					</div>
					<!-- /Panel body -->
				</div>
				<!-- /Panel -->
			</div>
			<div class="col-md-6">
				<!-- Panel -->
				<div class="panel panel-flat">
					<!-- Panel heading -->
					<div class="panel-heading">
						<h5 class="panel-title">
							<strong>{{ __('messages.users_using_role_msg') }}: {{ $role->name }}</strong>
						</h5>
						<hr/>
					</div>

					<!-- /Panel heading -->
					<!-- Panel body -->
					<div class="panel-body">
						<table id="users_table" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>{{ __('messages.name') }}</th>
									<th>{{ __('messages.email') }}</th>
								</tr>	
							</thead>
							<tbody>
								@foreach ($users as $user) 
								<tr>
									<td>{!! ucfirst($user->firstname)."&nbsp;". ucfirst($user->lastname) !!}</td>
									<td><a href="mailto:">{{ $user->email }}</a></td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<!-- /Panel body -->
				</div>
				<!-- /Panel -->
			</div>
		</div>
		<div class="btn-bottom-toolbar text-right btn-toolbar-container-out">
		<button type="submit" class="btn btn-success" name="submit">{{ __('messages.save') }}</button>
		<a class="btn btn-default" onclick="window.history.back();">{{ __('messages.back') }}</a>
		</div>
	</form>
</div>
<!-- /Content area -->
@stop

@section('scripts')
<script type="text/javascript">
$(function() {

    $('#users_table').DataTable({
    	buttons: []
    });

    //add class to style style datatable select box
    $('div.dataTables_length select').addClass('datatable-select');
 });


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

