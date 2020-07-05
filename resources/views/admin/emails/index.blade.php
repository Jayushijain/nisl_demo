@extends('layouts.admin')

@section('content')

<!-- Page header -->
<div class="page-header page-header-default">
  <div class="page-header-content">
    <div class="page-title">
      <h4>
        <span class="text-semibold">Email Templates</span>
      </h4>
    </div>
  </div>
  <div class="breadcrumb-line">
    <ul class="breadcrumb">
      <li>
        <a href="{{ route('dashboard') }}"><i class="icon-home2 position-left"></i>{{ __('messages.dashboard') }}</a>
      </li>
      <li class="active">
        Email Templates
      </li>
    </ul>
  </div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
  <!-- Panel -->
  <div class="panel panel-flat">    
    <!-- Listing table -->
    <div class="panel-body table-responsive">
      <table id="templates_table" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th width="40%">Name</th>            
            <th width="52%">Subject</th>            
            @if (has_permissions('email_templates','edit')) 
            <th width="8%" class="text-center">
              {{ __('messages.actions') }}
            </th>
            @endif
          </tr>
        </thead>
        <tbody>
          @foreach ($templates as $key => $template) 
          <tr>
           <td>{{ ucfirst($template->name) }}</td>            
           <td>{{ ucfirst($template->subject) }}</td>
            <td class="text-center">
              @if (has_permissions('email_templates','edit')) 
                <a data-popup="tooltip" data-placement="top"  title="{{ __('messages.edit') }}" href="{{ route('email_templates.edit',$template->id) }}" id="{{ $template->id }}" class="text-info">
                  <i class="icon-pencil7"></i>
                </a>
              @endif
            </td>
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

    $('#templates_table').DataTable({
        'columnDefs': [ {
        'targets': [2], /* column index */
        'orderable': false, /* disable sorting */
        }],
    });

    //add class to style style datatable select box
    $('div.dataTables_length select').addClass('datatable-select');
 });    
</script>

@stop

