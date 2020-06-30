<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Auto Logout after 15 mins (15*60=900 seconds) of inactivity -->
<meta http-equiv="refresh" content="900;url={{-- url('authentication/autologout') --}}" />
<meta name="csrf_token" content="{{ csrf_token() }}">

<title>{{-- $data['page_title'] --}}</title>

<!-- Global stylesheets -->
<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
<link href="{{ asset('admin/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin/css/core.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin/css/components.css') }}" rel="stylesheet" type="text/css">
<!-- /global stylesheets -->

<style type="text/css">

.datatable-select{
border: 1px solid #ccc;
padding: 5px;
height: 35px;
border-radius: 3px;
}	

.btn-bottom-toolbar 
{
	position: fixed;
	bottom: 0;
	padding: 15px;
	padding-right: 41px;
	margin: 0 0 0 -46px;
	-webkit-box-shadow: 0 -4px 1px -4px rgba(0,0,0,.1);
	box-shadow: 0 -4px 1px -4px rgba(0,0,0,.1);
	background: #fff;
	width: calc(100% - 211px);
	z-index: 5;
	border-top: 1px solid #ededed;
}
</style>

<!-- Core JS files -->
<script type="text/javascript" src="{{ asset('admin/js/plugins/loaders/pace.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/core/libraries/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/core/libraries/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/plugins/loaders/blockui.min.js') }}"></script>
<!-- /core JS files -->

<script type="text/javascript" src="{{ asset('admin/js/plugins/forms/styling/switchery.min.js') }}"></script>

<!-- Theme JS files -->
<script type="text/javascript" src="{{ asset('admin/js/plugins/forms/validation/validate.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('admin/js/plugins/notifications/sweet_alert.min.js') }}"></script>

<!-- date picker -->
<script type="text/javascript" src="{{ asset('admin/js/plugins/ui/moment/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/plugins/pickers/daterangepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/plugins/pickers/anytime.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/plugins/pickers/pickadate/picker.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/plugins/pickers/pickadate/picker.date.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/plugins/pickers/pickadate/picker.time.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/plugins/pickers/pickadate/legacy.js') }}"></script>
<!-- /date picker -->

<!-- select box -->
<script type="text/javascript" src="{{ asset('admin/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/plugins/forms/selects/select2.min.js') }}"></script>

<!-- select box -->

<script type="text/javascript" src="{{ asset('admin/js/plugins/notifications/jgrowl.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/plugins/editors/summernote/summernote.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/plugins/forms/styling/uniform.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('admin/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/plugins/tables/datatables/extensions/jszip/jszip.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('admin/js/core/app.js') }}"></script>

<script type="text/javascript" src="{{ asset('admin/js/common.js') }}"></script>

<script type="text/javascript">

// Default Settings for jQuery Validator
$.validator.setDefaults({
  ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
        errorClass: 'validation-error-label',
        successClass: 'validation-valid-label',
        highlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },

        // Different components require proper error label placement
        errorPlacement: function(error, element) {

            // Styled checkboxes, radios, bootstrap switch
            if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container') ) {
                if(element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                    error.appendTo( element.parent().parent().parent().parent() );
                }
                 else {
                    error.appendTo( element.parent().parent().parent().parent().parent() );
                }
            }

            // Unstyled checkboxes, radios
            else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
                error.appendTo( element.parent().parent().parent() );
            }

            // Input with icons and Select2
            else if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
                error.appendTo( element.parent() );
            }

            // Inline checkboxes, radios
            else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                error.appendTo( element.parent().parent() );
            }

            // Input group, styled file input
            else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
                error.appendTo( element.parent().parent() );
            }

            else {
                error.insertAfter(element);
            }
        },
        validClass: "validation-valid-label",
        success: function(label) {
            label.addClass("validation-valid-label").text("")
        },
});


$(function() {

// Default initialization for dropdown select
$('.select').select2({
    minimumResultsForSearch: Infinity
}); 

//datatables default settings
$.extend($.fn.dataTable.defaults, {
        autoWidth: false,
        order: [],
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': '&lt;%lt;', 'last': '&gt;%gt;', 'next': '&gt;', 'previous': '&lt;' }
        },
        buttons: {
            dom: {
	            button: {
	                className: 'btn btn-default'
	            }
            },
            buttons: [
            'copyHtml5',                
            'csvHtml5',
            'pdfHtml5'
            ]
        },
        "pageLength": 25,
        "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ]
    });    


//styled radio & checkboxes
$(".styled").uniform({
    radioClass: 'choice'
});

/* Set Default options to all Sweet Alerts */
swal.setDefaults({ 
		confirmButtonColor: "#2196F3",				         
		closeOnConfirm: false,                    		
});

/* jQuery switch */
var switches = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
switches.forEach(function(html) {
    var switchery = new Switchery(html);
});
/*End of Jquery for checkbox switch */

<?php 	
	
	$alert_class = '';

	if (Session::has('success'))
	{
		$alert_class = 'success';
	}
	elseif (Session::has('warning'))
	{
		$alert_class = 'warning';
	}
	elseif (Session::has('danger'))
	{
		$alert_class = 'danger';
	}
	elseif (Session::has('info'))
	{
		$alert_class = 'info';
	}

	if(Session::has($alert_class))
	{
?>
		jGrowlAlert("{{ session($alert_class)  }}",'{{ $alert_class }}');
<?php  
	}
?>

});

</script>
</head>

<body>
	<!--Main navbar-->
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="navbar-brand" href="{{ url('admin/dashboard') }}">{{ get_settings('company_name') }}</a>
			<ul class="nav navbar-nav visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
				<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>
		</div>
		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav">
				<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown dropdown-user">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<span>{{ get_loggedin_info('username') }}</span>
						<i class="caret"></i>
					</a>
					<ul class="dropdown-menu dropdown-menu-right">						
						<li><a href="{{-- url('admin/profile/edit') --}}" >{{ __('messages.edit_profile') }}</a></li>
						<li><a href="{{ route('logout') }}" >{{ __('messages.logout') }}</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<!-- /Main navbar -->
	<!-- Page container -->
	<div class="page-container">
		<!-- Page content -->
		<div class="page-content">
			<!-- Main sidebar -->
			<div class="sidebar sidebar-main">
				<div class="sidebar-content">					
					<!-- User menu -->
					<div class="sidebar-user">
						<div class="category-content">
							<div class="media">
								<div class="media-body">
									<span class="media-heading text-semibold">
										{{ __('messages.welcome') }}&nbsp{{ get_loggedin_info('username')}}&nbsp 
										<a style="color: white;" href="{{ route('logout') }}" align="padding-right"><i class="icon-switch2" data-popup="tooltip" data-placement="top"  title="{{ __('messages.logout') }}"></i></a>
									</span>
								</div>
							</div>
						</div>
					</div>
					<!-- /User menu -->
					<!-- Main navigation -->
					<div class="sidebar-category sidebar-category-visible">
						<div class="category-content no-padding">
							<ul class="navigation navigation-main navigation-accordion">
								<li @if (is_active_controller('dashboard')) {{'class="active"'}} @endif>
									<a href="{{ url('admin/dashboard') }}"><i class="icon-home4"></i> <span>{{ __('messages.dashboard') }}</span></a>
								</li>
									
								@if (has_permissions('categories', 'view'))
								<li @if (is_active_controller('categories')) {{'class="active"' }} @endif >
									<a href="{{ route('categories.index') }}">
										<i class="icon-menu6"></i>
										<span>{{ __('messages.categories') }}</span>
									</a>
								</li>
								@endif
								@if (has_permissions('projects', 'view')) 
								<li @if (is_active_controller('projects')) {{ 'class="active"' }}@endif >
									<a href="{{ route('projects.index') }}">
										<i class="icon-menu3"></i>
										<span>{{ __('messages.projects') }}</span>
									</a>
								</li>
								@endif
								@if (has_permissions('users', 'view'))	
								<li @if (is_active_controller('users')){{  'class="active"' }} @endif>
									<a href="{{ route('users.index') }}">
										<i class="icon-users4"></i>
										<span>{{ __('messages.users') }}</span>
									</a>
								</li>
								@endif
								<li>
									<a href="#"><i class="icon-cog3"></i><span>Setup</span></a>
									<ul>
										@if (has_permissions('roles', 'view'))
										<li @if (is_active_controller('roles')) {{ 'class="active"' }} @endif >
											<a href="{{-- url('admin/roles') --}}">
												<span>{{ __('messages.roles') }}</span>
											</a>
										</li>
										@endif

										<li @if (is_active_controller('emails')) {{ 'class="active"' }} @endif ><a href="{{ route('emails.index') }}">Email Templates</a></li>
										@if (has_permissions('settings', 'view'))
										<li @if (is_active_controller('settings')) {{ 'class="active"' }} @endif>
											<a href="{{ route('settings.index') }}">		
												<span>{{ __('messages.settings') }}</span>
											</a>
										</li>
										@endif
									</ul>
								</li>
							</ul>
						</div>
					</div>
					<!-- /Main navigation -->
				</div>
			</div>
			<!-- /Main sidebar -->
			<!-- Main content -->
			<div class="content-wrapper">			
				@yield('content')
				<!-- Footer -->
				<div class="footer text-muted text-center pl-20">
					&copy; {{ date('Y') }}. <a href="#">Admin Panel</a> by <a target="_blank">{{ get_settings('company_name') }}</a>
				</div>
				<!-- /Footer -->				
			</div>
			<!-- /Main content -->
		</div>
		<!-- /Page content -->
	</div>
	<!-- /Page container -->
	<script type="text/javascript">
		$.ajaxSetup({
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }); 
	</script>
	@yield('scripts')
</body>
</html>
