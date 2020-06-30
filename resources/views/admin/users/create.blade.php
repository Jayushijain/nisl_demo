@extends('layouts.admin')

@section('content')

<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <span class="text-semibold">{{ __('messages.add_user') }}</span>
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
            <li class="active">{{ __('messages.add') }}</li>
        </ul>
    </div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
    <form action="{{ route('users.store') }}" id="userform" method="POST">
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
                                <strong>{{ __('messages.user') }}</strong>
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
                                <input type="password" id="confirm_password"  class="form-control" placeholder="{{ __('messages.confirm_password') }}">
                            </div>
                            <div class="form-group">
                                <small class="req text-danger">* </small>
                                <label>{{ __('messages.role') }}</label>
                                <select class="select" name="role" id="role">
                                    <option value="">{{ __('messages.please_select_',['Name'=>__('messages.role')] ) }}</option>
                                    @foreach ($roles as $key => $role)
                                    <option value="{{ $role->id }}" name="role">{{ $role->name }}</option>
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
var BASE_URL = "<?php echo url('/'); ?>";

// $.validator.addMethod("emailExists", function(value, element) 
// {
//     var mail_id = $(element).val();
//     var ret_val = '';
//     $.ajax({
//         url:BASE_URL+'admin/authentication/email_exists',
//         type: 'POST',
//         data: { email: mail_id },
//         async: false,
//         success: function(msg) 
//         {   
//             if(msg==1)
//             {
//                 ret_val = false;
//             }
//             else
//             {
//                 ret_val = true;
//             }
//         }
//     }); 

//     return ret_val;
            
// }, "{-- __('messags.email_exists') --}");

$("#userform").validate({
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
    },
}); 
    	
</script>

@stop
