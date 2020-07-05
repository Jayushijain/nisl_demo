<table class="table table-bordered">
	<tr>
		<th width="40%">{{ __('messages.features') }}</th>
		<th width="60%">{{ __('messages.capabilities') }}</th>
	</tr>
	@php 
	$checked = '';
	@endphp
	@foreach ($permissions_data as $key=>$permission ) 
	<tr>
		<th>{{ $permission['name'] }}</th>
		<td>
			@foreach ($permission['capabilities'] as $index=>$capability) 
				@php
					$id = $key."_".$index;
				@endphp

				{{-- for edit view  --}}
				@if(isset($role))
					@php
					$role_permissions = unserialize($role['permissions']);
					@endphp
					@if (isset($role_permissions[$key]) && in_array($index, $role_permissions[$key]))
						@php
						$checked = 'checked';  
						@endphp
					@else 
						@php
						$checked = '';
						@endphp
					@endif
				@endif
				{{-- for edit view  --}}
			<div class="checkbox">
				<label for="{{ $id }}">
					<input type="checkbox" name="permissions[{{ $key }}][]" value="{{ $index }}" id="{{ $id }}" class="permission styled" {{ $checked }}>
					{{ $capability }} 
				</label>
			</div>
			@endforeach
		</td>
	</tr>
	@endforeach
</table>
