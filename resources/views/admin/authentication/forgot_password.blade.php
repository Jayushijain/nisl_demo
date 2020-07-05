@extends('layouts.admin_login')

@section('content')

<!-- Password recovery -->
<form  id="recovery_form" action="{{ route('forgot_password') }}" method="POST">
	{{ csrf_field() }}
	<div class="panel panel-body login-form">
		<div class="text-center">
			<div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
			<h5 class="content-group">{!! __('messages.forgot_password') !!}<small class="display-block">{!! __('messages.forgot_password_instructions') !!}</small></h5>
		</div>

		@include('admin.includes.alerts')

		<div class="form-group">
			<div class="form-control-feedback">
				<i class="icon-mail5 text-muted"></i>
			</div>
			<input type="email" class="form-control" placeholder="{{ __('messages.email') }}" name="email" id="email" autocomplete="off">			
		</div>
		<div class="form-group">
		<button type="submit" class="btn bg-blue btn-block" name="submit">{{  __('messages.confirm') }}<i class="icon-arrow-right14 position-right"></i></button>
		</div>

		<a href="{{ url('/admin/authentication') }}">{{ __('messages.login') }}</a>
		
	</div>
</form>			
<script type="text/javascript">
$(function () {
	$("#recovery_form").validate({
	    rules: {
	        email: {
                required: true,
                email: true
	        }
	    },
    	messages: {
	        email: {
				required:"{{ __('messages.please_enter_',['Name' => 'Email']) }}",
				email:"{{ __('messages.please_enter_valid_',['Name' => 'Email']) }}"
	        }
	    }
	});  
});
</script>

@stop