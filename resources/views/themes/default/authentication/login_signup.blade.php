@extends('layouts.default')

@section('content')

<div class="container" style="margin-top:100px;">
    @include('themes.default.includes.alerts')
	<div class="row">
		<div class="col-md-4 col-md-offset-1">
            <h4>Sign Up</h4><hr/>
			<form id="signup_form" method="post" action="{{ url('/authentication/signup') }}">
                {{ csrf_field() }}
				<div class="form-group">
					<small class="req text-danger">* </small>
                    <label>{{ __('messages.firstname') }}:</label>
					<input type="text" class="form-control" placeholder="{{ __('messages.firstname') }}" id="firstname" name="firstname">
				</div>
				<div class="form-group">
					<small class="req text-danger">* </small>
                    <label>{{ __('messages.lastname') }}:</label>
                    <input type="text" class="form-control" placeholder="{{ __('messages.lastname') }}" id="lastname" name="lastname">
				</div>
				<div class="form-group">
					<small class="req text-danger">* </small>
                    <label>{{ __('messages.email') }}:</label>
                    <input type="text" class="form-control" placeholder="{{ __('messages.email') }}" id="email" name="email" class="email">
				</div>
				<div class="form-group">
					<small class="req text-danger">* </small>
                    <label>{{ __('messages.mobile_no') }}:</label>
                    <input type="text" class="form-control" placeholder="{{ __('messages.mobile_no') }}" id="mobile_no" name="mobile_no">
				</div>
				<div class="form-group">
					<small class="req text-danger">* </small>
                    <label>{{ __('messages.password') }}:</label>
                    <input type="password" class="form-control" placeholder="{{ __('messages.password') }}" id="password" name="password" >
				</div>	
				<div class="form-group">
                    <small class="req text-danger">* </small>
                    <label>{{ __('messages.confirm_password') }}:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="{{ __('messages.confirm_password') }}">
                </div>			
				<button type="submit" class="btn btn-primary">Sign Up</button>
			</form>
		</div>
		<div class="col-md-4 col-md-offset-1">
            <h4>Login</h4><hr/>
			<form id="login_form" method="post" action="{{ url('/authentication') }}">
                {{ csrf_field() }}
				<div class="form-group">
                    <small class="req text-danger">* </small>
					<label for="email">Email</label>
					<input type="email" class="form-control" placeholder="{{ __('messages.email') }}" name="email" id="email" >
				</div>
				<div class="form-group">
                    <small class="req text-danger">* </small>
					<label for="password">Password</label>
					<input type="password" class="form-control" placeholder="{{ __('messages.password') }}" name="password" id="password" >
				</div>
                <div class="form-group">
                    <label class="checkbox-inline">
                        <input type="checkbox" class="styled" name="remember" >
                        {{ __('messages.remember_me') }}
                    </label>
                    <a class="pull-right" href="{{ route('authentication.forgot_password') }}">{{ __('messages.forgot_password') }}</a>
                </div>	
				<button type="submit" class="btn btn-primary">{{ __('messages.login') }}</button>
			</form>
		</div>
	</div>

    @stop

@section('scripts')

<script type="text/javascript">

$.validator.addMethod("emailExists", function(value, element) 
{
    var mail_id = $(element).val();
    var ret_val = '';
    $.ajax({
        url:'/authentication/email_exists',
        type: 'POST',
        data: { email: mail_id },
        async: false,
        success: function(msg) 
        {   
            if(msg==1)
            {
                ret_val = false;
            }
            else
            {
                ret_val = true;
            }
        }
    }); 

    return ret_val;
            
}, "{{ __('messages.email_exists') }}");

$("#signup_form").validate({
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
            email: true,
            emailExists: true,
        },
        password: {
            required: true,
            minlength: 8
        },
        confirm_password: {
            required: true,
            equalTo: "#password",
        },
        role: {
            required: true,
        },
    },
    messages: {
        firstname: {
            required:"{{ __('messages.please_enter_', ['Name' => __('messages.firstname')]) }}",
        },
        lastname: {
            required:"{{ __('messages.please_enter_', ['Name' => __('messages.lastname')]) }}",
        },
        mobile_no: 'Please enter a valid 10 digit mobile number',
        email: {
            required:"{{ __('messages.please_enter_', ['Name' => __('messages.email')]) }}",
            email:"{{ __('messages.please_enter_valid_', [ __('messages.email') ]) }}"
        },        
        password: {
            required:"{{ __('messages.please_enter_', ['Name' => __('messages.password')]) }}",
            minlength: "{{ __('messages.password_min_length_must_be_', ['Name' => 8]) }}",
        },
        confirm_password: {
            required:"{{ __('messages.please_enter_', ['Name' => __('messages.password')]) }}",
            equalTo: "{{ __('messages.conf_password_donot_match') }}",
        }, 
        role: {
            required:"{{ __('messages.please_enter_', ['Name' => __('messages.role')]) }}",
        },
    },
}); 

$("#login_form").validate
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
        messages: {
            email: {
                required:"{{ __('messages.please_enter_', ['Name' => __('messages.email')]) }}",
                email:"{{ __('messages.please_enter_valid_', [ __('messages.email') ]) }}"
            },
            password: {
                required:"{{ __('messages.please_enter_', ['Name' => __('messages.password')]) }}"
            },
        }
    });
    	
</script>

@stop
