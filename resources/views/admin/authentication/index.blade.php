<!-- Simple login form -->
@extends('layouts.admin_login')

@section('content')

<form action="{{ url('/admin/authentication') }}" id="loginform" method="POST">
	{{ csrf_field() }}
	<div class="panel panel-body login-form">
		<div class="text-center">
			<div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i>
			</div>
			<h5 class="content-group">{{ __('messages.login_to_your_account') }}<small class="display-block">{{ __('messages.enter_your_credentials_below') }}</small></h5>
		</div>
		@include('admin.includes.alerts')
		<div class="form-group has-feedback has-feedback-left">
			<div class="form-control-feedback"><i class="icon-envelop text-muted"></i>
			</div>
			<input type="email" class="form-control" placeholder="{{ __('messages.email') }}" name="email" id="email" >
			
		</div>
		<div class="form-group has-feedback has-feedback-left">
			<div class="form-control-feedback">
				<i class="icon-lock2 text-muted"></i>
			</div>
			<input type="password" class="form-control" placeholder="{{ __('messages.password') }}" name="password" id="password" >
		</div>
		<div class="form-group login-options">
			<div class="row">
				<div class="col-sm-6">
					<label class="checkbox-inline">
						<input type="checkbox" class="styled" name="remember" >
						{{ __('messages.remember_me') }}
					</label>
				</div>
				<div class="col-sm-6 text-right">
					<a href="{{ route('forgot_password') }}">{{ __('messages.forgot_password') }}</a>
				</div>
			</div>
		</div>
		<div class="form-group">
			<button type="submit" class="btn bg-blue btn-block">{{ __('messages.login') }}<i class="icon-arrow-right14 position-right"></i></button>
		</div>
		
	</div>
</form>
<!-- /simple login form -->
<script type="text/javascript">
$(function () {
	$("#loginform").validate
	({
		rules: {
			email: {
				required: true,
				email: true
			},
			password: {
				required: true
			}
		},
		messagess: {
			email: {
				required:"{{ __('messages.please_enter_',['Name' => 'Email']) }}",
				email:"{{ __('messages.please_enter_valid_',['Name' => 'Email']) }}"
			},
			password: {
				required:"{{ __('messages.please_enter_', ['Name' => 'Password']) }}"
			},
		}
	});
});
</script>

@stop