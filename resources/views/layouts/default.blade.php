<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{-- $page_title --}}</title>
    <link rel="stylesheet" href="{{ asset('themes/default/css/bootstrap.min.css') }}" >
    <link rel="stylesheet" href="{{ asset('themes/default/css/style.css') }}" >
    <script type="text/javascript" src="{{ asset('admin/js/core/libraries/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/js/plugins/forms/validation/validate.min.js') }}"></script>
</head>

<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{ url('/') }}">{{ get_settings('company_name') }}</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right"> 
            @if (is_user_logged_in())  
                <li><a href="#">Welcome {{ get_loggedin_info('username') }}</a></li>
                <li><a href="{{ url('/authentication/logout') }}">{{ __('messages.logout') }}</a></li>
            @else           
                <li><a href="{{ url('/authentication/index') }}">Login</a></li>
                <li><a href="{{ url('/authentication/signup') }}">Sign Up</a></li>
            @endif    
            </ul>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>
	@yield('content')
	  <hr>
      <footer>
        <p>&copy; {{ date('Y') }} {{ get_settings('company_name') }}</a></p>
      </footer>
    </div>
    @yield('scripts')
</body>
</html>