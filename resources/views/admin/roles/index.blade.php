@extends('layouts.admin')

@section('content')

<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <span class="text-semibold">{{ __('messages.roles') }}</span>
            </h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">      
            <li>
                <a href="{{ route('dashboard') }}"><i class="icon-home2 position-left"></i>{{ __('messages.dashboard') }}</a>
            </li>
            <li class="active">{{ __('messages.roles') }}</li>
        </ul>
    </div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
    <!-- Panel -->
    <div class="panel panel-flat">
        @if (has_permissions('roles','create')||has_permissions('roles','delete')) 
        <!-- Panel heading -->
        <div class="panel-heading">
            @if (has_permissions('roles','create')) 
                <a href="{{ route('roles.create') }}" class="btn btn-primary">{{ __('messages.add_new') }}<i class="icon-plus-circle2 position-right"></i></a>
            @endif
            @if (has_permissions('roles','delete')) 
                <a href="javascript:delete_selected();" class="btn btn-danger" id="delete_all">{{ __('messages.delete_selected') }}<i class=" icon-trash position-right"></i></a>
            @endif
        </div>
        <!-- /Panel heading -->
        @endif
        
        <!-- Listing table -->
        <div class="panel-body table-responsive">
            <table id="categories_table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        @if (has_permissions('roles','delete'))
                        <th width="2%">
                            <input type="checkbox" name="select_all" id="select_all" class="styled" onclick="select_all(this);" >
                        </th>
                        @endif
                        <th width="90%">{{ __('messages.role_name') }}</th>
                        @if (has_permissions('roles','edit') || has_permissions('roles','delete'))
                        <th width="8%" class="text-center">{{ __('messages.actions') }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $key => $role) 
                    <tr>
                        @if (has_permissions('roles','delete')) 
                        <td>
                            <input type="checkbox" class="checkbox styled"  name="delete"  id="{{ $role->id }}">
                        </td>
                        @endif
                        <td>{{ ucfirst($role->name) }}</td>
                        @if (has_permissions('roles','edit') || has_permissions('roles','delete'))
                        <td class="text-center">
                            @if (has_permissions('roles','edit')) 
                            <a data-popup="tooltip" data-placement="top"  title="{{ __('messages.edit') }}" href="{{ route('roles.edit',$role->id) }}" id="{{ $role->id }}" class="text-info">
                                <i class="icon-pencil7"></i>
                            </a>
                            @endif
                            @if (has_permissions('roles','delete')) 
                            <a data-popup="tooltip" data-placement="top"  title="{{ __('messages.delete') }}" href="javascript:delete_record({{ $role->id }});" class="text-danger delete" id="{{ $role->id }}">
                                <i class=" icon-trash"></i>
                            </a>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
           
        </div>
        <!-- /Listing table -->
    </div>
    <!-- /Panel -->       
</div>
<!-- /Content area -->
@stop

@section('scripts')

<script type="text/javascript">
$(function() {

    $('#categories_table').DataTable({
        'columnDefs': [{
        'targets': [0,2], /* column index */
        'orderable': false, /* disable sorting */
        }],
         
    });

    //add class to style style datatable select box
    $('div.dataTables_length select').addClass('datatable-select');
 });

/**
 * Deletes a single record when clicked on delete icon
 *
 * @param {int}  id  The identifier
 */
function delete_record(id) 
{         
    swal({
        title: "{{ __('messages.multiple_deletion_alert',['Name' => __('messages.roles')]) }}",
        text: "{{ __('messages.multiple_recovery_alert', ['Name' => __('messages.roles')]) }}",
        type: "warning",    
        showCancelButton: true, 
        cancelButtonText:"{{ __('messages.no_cancel_it') }}",
        confirmButtonText: "{{ __('messages.yes_i_am_sure') }}",     
    },
    function()
    {
        $.ajax({
            url: '/admin/roles/delete/'+id,
            type: 'GET',
            success: function(msg)
            {
                if (msg=="true")
                {                        
                    swal({                            
                        title: "{{ __('messages._deleted_successfully', ['Name' => __('messages.role')]) }}",
                        type: "success",                            
                    });
                    $("#"+id).closest("tr").remove();
                }
                else
                {                        
                    swal({
                        title: "{{ __('messages.role_in_use_deletion_alert') }}", 
                        type: "error",                            
                    });
                }  
            }
        });
    });
}

/**
 * Deletes all the selected records when clicked on DELETE SELECTED button
 */
function delete_selected() 
{ 
    var role_ids = [];

    $(".checkbox:checked").each(function()
    {
        var id = $(this).attr('id');
        role_ids.push(id);
    });
    if (role_ids == '')
    {
        jGrowlAlert("{{ __('messages.select_before_delete_alert',[ 'Name' => __('messages.roles')]) }}", 'danger');
        preventDefault();
    }
    swal({
        title: "{{ __('messages.multiple_deletion_alert', [ 'Name' => __('messages.roles')]) }}",
        text: "{{ __('messages.multiple_recovery_alert', [ 'Name' => __('messages.roles')]) }}",
        type: "warning",  
        showCancelButton: true, 
        cancelButtonText:"{{ __('messages.no_cancel_it') }}",
        confirmButtonText: "{{ __('messages.yes_i_am_sure') }}",      
    },
    function()
    {
        $.ajax({
            url: "{{ route('roles.delete_selected') }}",
            type: 'POST',
            data: {
              ids:role_ids
            },
            success: function(msg)
            {
               var result = JSON.parse(msg);

               swal({                        
                    title: result.output,
                    html: true,                        
                    type: result.type,                        
                });

                $(result.deleted_role_ids).each(function(index, element) 
                  {
                      $("#"+element).closest("tr").remove();
                  });
            }
        });
    });
}

</script>

@stop
