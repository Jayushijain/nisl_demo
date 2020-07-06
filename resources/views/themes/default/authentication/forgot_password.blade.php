@extends('layouts.default')

@section('content')

<div class="container" style="margin-top:100px;">
	
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
            @include('themes.default.includes.alerts')
            <h5 class="content-group">{{ __('messages.forgot_password') }}
            <br><small class="display-block">{!! __('messages.forgot_password_instructions') !!}</small></h5>
			<form id="recovery_form" method="post" action="{{ route('authentication.forgot_password') }}">
                {{ csrf_field() }}
				<div class="form-group">
					<small class="req text-danger">* </small>
                    <label>{{ __('messages.email') }}</label>
                    <input type="email" class="form-control" placeholder="{{ __('messages.email') }}" name="email" id="email">
				</div>						
				<button type="submit" class="btn btn-primary btn-block" >{{ __('messages.confirm') }}</button>
                <a href="{{ route('authentication') }}">{{ __('messages.login') }}</a>
			</form>
		</div>
	</div>	

    @stop

    @section('scripts')
<script type="text/javascript">
$("#recovery_form").validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
                required:"{{ __('messages.please_enter_', ['Name' => __('messages.email')]) }}",
                email:"{{ __('messages.please_enter_valid_',['Name' => __('messages.email')]) }}"
            }
        }
    }); 
</script>

@stop
