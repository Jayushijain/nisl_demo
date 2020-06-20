@extends('layouts.admin')

@section('content')

<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h4>
				<span class="text-semibold">{{ __('messages.edit_category') }}</span>
			</h4>
		</div>
	</div>
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{{ route('dashboard') }}"><i class="icon-home2 position-left"></i>{{ __('messages.dashboard') }}</a></li>
			<li><a href="{{ route('categories.index') }}">{{ __('messages.categories') }}</a></li>
			<li class="active">{{ __('messages.edit') }}</li>
		</ul>
	</div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
	<form action="{{ route('categories.update',$category->id ) }}" id="categoryform" method="POST">
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
									<strong>{{ __('messages.category') }}</strong>
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
									<small class="req text-danger">*</small>
									<label>{{ __('messages.category_name') }}:</label>
									<input type="text" class="form-control" placeholder="{{ __('messages.category_name') }}" id="name" name="name" value="{{ $category->name }}">
								</div>
								<div class="form-group">
									<label>{{ __('messages.status') }}:</label>	
										<input type="checkbox" class="switchery" name="is_active" id="{{ $category->id }}" @if ($category->is_active==1) 
										{{ "checked" }}
										@endif>								
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
$("#categoryform").validate({
	rules: {
		name:
		{
			required: true,		
		},
	},
	messages: {
		name: 
		{
			required:"{{ __('messages.please_enter_',['Name' => __('messages.category_name')] ) }}",
		},        
	}
});  
</script>

@stop

