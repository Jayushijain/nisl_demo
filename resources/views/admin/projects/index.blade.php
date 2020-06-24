@extends('layouts.admin')

@section('content')

<!-- Page header -->
<div class="page-header page-header-default">
  <div class="page-header-content">
    <div class="page-title">
      <h4>
        <span class="text-semibold">{{ __('messages.projects') }}</span>
      </h4>
    </div>
  </div>
  <div class="breadcrumb-line">
    <ul class="breadcrumb">
      <li>
        <a href="{{ route('dashboard') }}"><i class="icon-home2 position-left"></i>{{ __('messages.dashboard') }}</a>
      </li>
      <li class="active">
        {{ __('messages.projects') }}
      </li>
    </ul>
  </div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
  <!-- Panel -->
  <div class="panel panel-flat">
    @if (has_permissions('projects','create') || has_permissions('projects','delete')) 
      <!-- Panel heading -->
      <div class="panel-heading">
        @if (has_permissions('projects','create')) 
          <a href="{{ route('projects.create') }}" class="btn btn-primary">{{ __('messages.add_new') }}<i class="icon-plus-circle2 position-right"></i></a>
        @endif
        @if (has_permissions('projects','delete')) 
        <a href="javascript:delete_selected();" class="btn btn-danger" id="delete_selected">{{ __('messages.delete_selected') }}<i class=" icon-trash position-right"></i></a>
        @endif
      </div>
      <!-- /Panel heading -->
    @endif
    
    <!-- Listing table -->
    <div class="panel-body table-responsive">
      <table id="projects_table" class="table table-bordered table-striped">
        <thead>
          <tr>
            @if (has_permissions('projects','delete')) 
            <th width="2%">
              <input type="checkbox" name="select_all" id="select_all" class="styled" onclick="select_all(this);" >
            </th>
            @endif
            <th width="10%">{{ __('messages.id') }}</th>
            <th width="20%">{{ __('messages.name') }}</th>
            <th width="50%">{{ __('messages.description') }}</th>
            <th width="10%">{{ __('messages.created_at') }}</th>
            @if (has_permissions('projects','edit') || has_permissions('projects','delete'))
            <th width="8%" class="text-center">{{ __('messages.actions') }}</th>
            @endif
          </tr>
        </thead>
        <tbody>
          @foreach ($projects as $key => $project) 
          <tr>
            @if (has_permissions('projects','delete')) 
            <td>
              <input type="checkbox" class="checkbox styled"  name="delete"  id="{{ $project->id }}">
            </td>
            @endif
            <td>{{ $project->project_id }}</td>
            <td>{{ ucwords($project->name) }}</td>
            <td>{{ ucfirst($project->details) }}</td>
            <td>{{ display_date_time($project->created_at) }}</td>
            @if (has_permissions('projects','edit') || has_permissions('projects','delete'))
            <td class="text-center">
              @if (has_permissions('projects','edit'))
                <a data-popup="tooltip" data-placement="top"  title="{{ __('messages.edit') }}" href="{{ route('projects.edit',$project->id) }}" id="{{ $project->id }}" class="text-info">
                  <i class="icon-pencil7"></i>
                </a>
              @endif
              @if (has_permissions('projects','delete')) 
                <a data-popup="tooltip" data-placement="top"  title="{{ __('messages.delete') }}" href="javascript:delete_record({{ $project->id }});" class="text-danger delete" id="{{ $project->id }}"><i class=" icon-trash"></i></a>
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

<script type="text/javascript">

$(function() {

    $('#projects_table').DataTable({
        'columnDefs': [ {
        'targets': [0,3,4,5], /* column index */
        'orderable': false, /* disable sorting */
        }],
         
    });

    //add class to style style datatable select box
    $('div.dataTables_length select').addClass('datatable-select');
 });  

var BASE_URL = "<?php echo url('/'); ?>";

/**
 * Deletes a single record when clicked on delete icon
 *
 * @param {int}  id  The identifier
 */
function delete_record(id) 
{ 
    swal({
        title: "{{ __('messages.single_deletion_alert') }}",
        text: "{{ __('messages.single_recovery_alert') }}",
        type: "warning",  
        showCancelButton: true, 
        cancelButtonText:"{{ __('messages.no_cancel_it') }}",
        confirmButtonText: "{{ __('messages.yes_i_am_sure') }}",      
    },
    function()
    {   
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });    
        $.ajax({
            type: 'GET',
            url:'/admin/projects/delete/'+id,            
            success: function(msg)
            {
                alert(msg);
                if (msg=="true")
                {                    
                    swal({                        
                        title: "{{ __('messages._deleted_successfully', ['Name' => __('messages.project')]) }}",       
                        type: "success",                            
                    });
                    $("#"+id).closest("tr").remove();
                }
                else
                {                        
                    swal({                           
                        title: "{{ __('messages.access_denied', ['Name' => __('messages.project')]) }}",
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
    var project_ids = [];

    $(".checkbox:checked").each(function()
    {
        var id = $(this).attr('id');
        project_ids.push(id);
    });
    if (project_ids == '')
    {
         jGrowlAlert("{{ __('messages.select_before_delete_alert',['Name' => __('messages.projects')] ) }}", 'danger');
        preventDefault();
    }
    swal({
        title: "{{ __('messages.multiple_deletion_alert') }}",
        text: "{{ __('messages.multiple_recovery_alert') }}",
        type: "warning",
        showCancelButton: true, 
        cancelButtonText:"{{ __('messages.no_cancel_it') }}",
        confirmButtonText: "{{ __('messages.yes_i_am_sure') }}", 
    },
    function()
    {
        $.ajax({
            url:BASE_URL+'admin/projects/delete_selected',
            type: 'POST',
            data: {
              ids:project_ids
            },
            success: function(msg)
            {
                if (msg=="true")
                {                     
                  swal({                           
                        title: "{{ __('messages._deleted_successfully', ['Name' => __('messages.project')]) }}",                    
                        type: "success",                            
                    });
                  $(project_ids).each(function(index, element) 
                  {
                      $("#"+element).closest("tr").remove();
                  });
                }
                else
                {
                  swal({                            
                       title: "{{ __('messages.access_denied', ['Name' => __('messages.project')]) }}",
                        type: "error",   
                    });
                }
            }
        });
    });
}
</script>

@stop
