@extends('layouts.admin')

@section('content')

<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h4>
				<span class="text-semibold">Edit Email Template</span>
			</h4>
		</div>
	</div>
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li>
				<a href="{{ route('dashboard') }}"><i class="icon-home2 position-left"></i>{{ __('messages.dashboard') }}</a>	
			</li>
			<li>
				<a href="{{ route('email_templates.index') }}">Email Templates</a>
			</li>
			<li class="active">{{ __('messages.edit') }}</li>
		</ul>
	</div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
	<form action="{{ route('email_templates.update',$template->id) }}" id="templateform" method="POST">
		
		{{ csrf_field() }}
		<div class="row">
			<div class="col-md-6">
				<!-- Panel -->
				<div class="panel panel-flat">
					<!-- Panel heading -->
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-12">
								<h5 class="panel-title">
									<strong>Edit Temaplate: {{ $template->name }}</strong>
								</h5>
								<hr/>
							</div>
						</div>
					</div>
					<!-- /Panel heading -->
					<!-- Panel body -->
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">									
									<label>{{ __('messages.name') }}:</label>
									<input type="text" class="form-control" readonly="readonly" data-popup="tooltip" title="Can not change template name" data-placement="top" value="{{ $template->name }}">
								</div>
								<div class="form-group">					
									<label>Slug:</label>
									<input type="text" class="form-control" readonly="readonly" data-popup="tooltip" title="Can not change template slug" data-placement="top" value="{{ $template->slug }}">
								</div>
								<div class="form-group">
									<small class="req text-danger">*</small>						
									<label>Subject:</label>
									<input type="text" class="form-control" placeholder="Subject" id="subject" name="subject" value="{{ $template->subject }}">
								</div>
								<div class="form-group">
									<small class="req text-danger">*</small>						
									<label>Message Body:</label>
									<textarea name="message" id="message" class="form-control summernote" rows="10">{{ $template->message }}</textarea>
									<div id="validation_msg"></div>
								</div>
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
							<strong>Available Placeholders for this template</strong>
						</h5>
						<hr/>
					</div>
					<!-- /Panel heading -->
					<!-- Panel body -->
					<div class="panel-body">
						<div class="well">
				                    <dl class="dl-horizontal">
					                    @php 
					                    $placeholders = unserialize($template->placeholders);
					                    asort($placeholders);
					                    @endphp
					                    @foreach ($placeholders as $key => $value)
											<dt>{{ $value }}</dt>
											<dd><a data-popup="tooltip" title="Click to add" data-placement="top" href="javascript:void(0);" class="copy">{{ $key }}</a></dd>
										@endforeach
									</dl>
								</div>
					</div>
					<!-- /Panel body -->
				</div>
				<!-- /Panel -->
			</div>
		</div>
		<div class="btn-bottom-toolbar text-right btn-toolbar-container-out">
		<button type="submit" class="btn btn-success">{{ __('messages.save') }}</button>
		<a class="btn btn-default" onclick="window.history.back();">{{ __('messages.back') }}</a>
		</div>
	</form>
</div>
<!-- /Content area -->

<script type="text/javascript">

$('.summernote').summernote({
        height: 350
});

$('.copy').on('click', function(e){
	e.preventDefault();	
	$('.summernote').summernote('editor.insertText', $(this).text());

});

$('#templateform').on('submit', function() {
    if($('.summernote').summernote('isEmpty'))
    {
    	$("#validation_msg").html("<p style='color:red'>Please Enter Template Message.</p>");
    	return false;
    }
    return true;
});

$("#templateform").validate({
	rules: {
		subject:{
			required: true,
		},
	},
	messages: {
		subject:{
			required: 'Please Enter Template Subject',
		},
	},	
});  
</script>

@stop