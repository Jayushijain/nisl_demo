@php 
	
	$alert_class = $alert_type = '';
	Session::flash('success_message', 'Password Changed');

	if (Session::has('success'))
	{
		$alert_class = $alert_type = 'success';
	}
	elseif (Session::has('warning'))
	{
		$alert_class = $alert_type =  'warning';
	}
	elseif (Session::has('error'))
	{
		$alert_class = 'danger';
		$alert_type = 'error';
	}
	elseif (Session::has('info'))
	{
		$alert_class = $alert_type =  'info';
	}

	if (session::has($alert_type))
	{
	@endphp
<div class="row">
	<div class="col-lg-12">
		<div class="alert alert-{{ $alert_class }}">
			<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
			{{ session($alert_type) }}
		</div>
	</div>
</div>
@php } @endphp