@extends('layouts.admin')

@section('content')

<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h4>
				<span class="text-semibold">{{ __('messages.edit_user') }}</span>
			</h4>
		</div>
	</div>
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li>
				<a href="{{ route('dashboard') }}"><i class="icon-home2 position-left"></i>{{ __('messages.dashboard') }}</a>
			</li>
			<li>
				<a href="{{ route('users.index') }}">{{ __('messages.users') }}</a>
			</li>
			<li class="active">{{ __('messages.edit') }}</li>
		</ul>
	</div>
</div>
<!-- Page header -->
<!-- Content area -->
<div class="content">	
	<form action="{{ route('users.update',$user->id) }}" id="profileform" method="POST">
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
									<strong>{{ __('messages.users') }}</strong>
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
									<small class="req text-danger">* </small>
									<label>{{ __('messages.firstname') }}:</label>
									<input type="text" class="form-control" placeholder="{{ __('messages.firstname') }}" id="firstname" name="firstname" value="{{ $user->firstname }}">
								</div>
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label>{{ __('messages.lastname') }}:</label>
									<input type="text" class="form-control" placeholder="{{ __('messages.lastname') }}" id="lastname" name="lastname" value="{{ $user->lastname }}">
								</div>
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label>{{ __('messages.email') }}:</label>
									<input type="text" class="form-control" placeholder="{{ __('messages.email') }}" id="email" name="email" class="email"value="{{ $user->email }}">
								</div>
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label>{{ __('messages.mobile_no') }}:</label>
									<input type="text" class="form-control" placeholder="{{ __('messages.mobile_no') }}" id="mobile_no" name="mobile_no" value="{{ $user->mobile_no }}">
								</div>
								<div class="form-group">							
									<label>{{ __('messages.new_password') }}:</label>
									<input type="password" class="form-control" placeholder="{{ __('messages.enter_new_password_only_if_you_want_to_change_password') }}" id="newpassword" name="newpassword" value="" >											
								</div>
								<div class="form-group">
									<label>{{ __('messages.status') }}:</label>
									@php 
									$readonly = '';
									@endphp
									@if ($user->id==get_loggedin_user_id())
										@php
											$readonly="readonly";
										@endphp
									@endif 
									<input type="checkbox" class="switchery" name="is_active" 
									id="{{ $user->id }}" @if($user['is_active']==1) {{ "checked" }} @endif  {{ $readonly }} >
								</div>
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label>{{ __('messages.role') }}</label>
									<select class="select" name="role" id="role">
										@foreach ($roles as $key => $role) 								
										<option value="{{ $role->id }}" name="role" @if($user->role==$role->id) {{  "selected" }} @endif>{{ $role->name }}
										</option>
										@endforeach
									</select>
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
			<button type="submit" class="btn btn-success" >{{ __('messages.save') }}</button>
			<a class="btn btn-default" onclick="window.history.back();">{{ __('messages.back') }}</a>
		</div>
	</form>
</div>
<!-- /Content area -->

<script type="text/javascript">
function isPasswordPresent() {
	return $('#newpassword').val().length > 0;
}

$("#profileform").validate({
	rules: {
		firstname: {
			required: true,
		},
		lastname: {
			required: true,
		},
		mobile_no: {
			required: true,
            number: true,
            minlength:10,
		},
		email: {
			required: true,
			email: true
		},
		newpassword: {            
            minlength: {
                depends: isPasswordPresent,
                param: 8
            }
        },
		role: {
			required: true
		}
	},
	messages: {
		 firstname: {
            required:"{{ __('messages.please_enter_',['Name' => __('messages.firstname')] ) }}",
        },
        lastname: {
            required:"{{ __('messages.please_enter_',['Name' => __('messages.lastname')] ) }}",
        },
        mobile_no: 'Please enter a valid 10 digit mobile number',
        email: {
            required:"{{ __('messages.please_enter_',['Name' => __('messages.email')] ) }}",
            email:"{{ __('messages.please_enter_valid_', ['Name' => __('messages.email')]) }}"
        },        
        password: {
            required:"{{ __('messages.please_enter_',['Name' => __('messages.password')] ) }}",
            minlength: "{{ __('messages.password_min_length_must_be_', ['Name'=>8]) }}",
        },
        confirm_password: {
            required:"{{ __('messages.please_enter_',['Name' => __('messages.password')] ) }}",
            equalTo: "{{ __('messages.conf_password_donot_match') }}",
        }, 
        role: {
            required:"{{ __('messages.please_enter_',['Name' => __('messages.role')] ) }}",
        },
	}
});  

</script>

@stop

