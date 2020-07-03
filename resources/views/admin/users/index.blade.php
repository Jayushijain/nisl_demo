@extends('layouts.admin')

@section('content')

<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <span class="text-semibold">{{ __('messages.users') }}</span>
            </h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}"><i class="icon-home2 position-left"></i>{{ __('messages.dashboard') }}</a>
            </li>
            <li class="active">{{ __('messages.users') }}</li>
        </ul>
    </div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
    <!-- Panel -->
    <div class="panel panel-flat">
        @if (has_permissions('users','create'))
        <!-- Panel heading -->
        <div class="panel-heading">
            @if ( has_permissions('users','create') || has_permissions('users','Delete') ) 
            <a href="{{ route('users.create') }}" class="btn btn-primary">{{ __('messages.add_new') }}<i class="icon-plus-circle2 position-right"></i></a>  
            @endif
            @if (has_permissions('users','Delete')) 
            <a href="javascript:delete_selected();" class="btn btn-danger" id="delete_selected">{{ __('messages.delete_selected') }}<i class=" icon-trash position-right"></i></a>
            @endif
        </div>
        <!-- /Panel heading -->
        @endif
        
        <!-- Listing table -->
        <div class="panel-body table-responsive">
            <table id="users_table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        @if (has_permissions('users','delete')) 
                        <th width="2%">
                            <input type="checkbox" name="select_all" id="select_all" class="styled" onclick="select_all(this);" >
                        </th>
                        @endif
                        <th width="30%">{{ __('messages.firstname') }}</a> {{ __('messages.lastname') }}</th>
                        <th width="30%">{{ __('messages.email') }}</th>
                        <th width="10%">{{ __('messages.role') }}</th>
                        <th width="12%">{{ __('messages.last_login') }}</th>
                        <th width="8%" class="text-center">{{ __('messages.status') }}</th>
                        @if (has_permissions('users','edit') || has_permissions('users','delete'))
                        <th width="8%" class="text-center">{{ __('messages.actions') }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $user) 
                    <tr>
                        @if (has_permissions('users','delete'))
                            @php
                                $disabled = '';                            
                            @endphp
                            @if ($user->id == get_loggedin_info('user_id'))
                                @php
                                $disabled = 'disabled';
                                @endphp
                            @endif
                        <td>
                            <input type="checkbox" class="checkbox styled"  name="delete"  id="@if ($user->id != get_loggedin_info('user_id')) {{ $user->id }} @endif" {{ $disabled }}>
                        </td>
                        @endif

                        <td>
                            {!! ucfirst($user->firstname).'&nbsp;'.ucfirst($user->lastname) !!}
                        </td>
                        <td>
                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        </td>
                        <td>
                            {{ get_role_by_id($user->role) }}
                        </td>

                        @php 
                        $login_datetime = $user->last_login != null ? display_date_time($user->last_login) : __('messages.never');
                        @endphp
                        <td>
                            <abbr data-popup="tooltip" data-placement="top"  title="{{ $login_datetime }}">
                            @if ($user['last_login'] != 'Never')
                                {{ time_to_words($user->last_login) }}
                            @else
                                {{ __('messages.never') }}
                            @endif
                            </abbr>
                        </td>

                        @php            
                        $readonly = '';
                        @endphp
                        @if ($user['id'] == get_loggedin_info('user_id') || !has_permissions('users','edit'))
                            @php
                            $readonly = "readonly";
                            @endphp
                        @endif
                        <td class="text-center switchery-sm">
                            <input type="checkbox" onchange="change_status(this);" class="switchery"  id="{{ $user->id }}" @if ($user['is_active'] == 1)  {{ "checked" }} @endif {{ $readonly }}>
                        </td>

                        @if (has_permissions('users','edit') || has_permissions('users','delete'))
                        <td class="text-center">
                            @if (has_permissions('users', 'edit'))
                            <a data-popup="tooltip" data-placement="top"  title="{{ __('messages.edit') }}" href="{{ route('users.edit',$user->id) }}" id="{{ $user->id }}" class="text-info"><i class="icon-pencil7"></i></a>
                            @endif
                            @if (has_permissions('users', 'delete')) 
                            <a data-popup="tooltip" data-placement="top"  title="{{ __('messages.delete') }}" href="javascript:delete_record({{ $user->id }});" class="text-danger delete" id="{{ $user->id }}"><i class=" icon-trash"></i></a>
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

    $('#users_table').DataTable({
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
        'columnDefs': [ {
        'targets': [0,4,5,6], /* column index */
        'orderable': false, /* disable sorting */
        }],
         
    });

    //add class to style style datatable select box
    $('div.dataTables_length select').addClass('datatable-select');
 });


var BASE_URL = "<?php echo url('/'); ?>";

/**
 * Change status when clicked on the status switch
 *
 * @param {obj}  obj  The object
 */
function change_status(obj)
{
    var checked = 0;

    if(obj.checked) 
    { 
        checked = 1;
    }  

    $.ajax({
        url: "{{ route('users.update_status') }}",
        type: 'POST',
        data: {
            user_id: obj.id,
            is_active:checked
        },
        success: function(msg) 
        {
            if (msg=='true')
            {
                jGrowlAlert("{{ __('messages._activated',['Name' => __('messages.user')]) }}", 'success');
            }
            else
            {
                jGrowlAlert("{{ __('messages._deactivated', ['Name' => __('messages.user')]) }}", 'success');
            }
        }
    }); 
}

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
        $.ajax({
           type: 'GET',
            url:'/admin/users/delete/'+id,
            success: function(msg)
            {
                if (msg=="true")
                {
                    swal({
                        title: "{{ __('messages._deleted_successfully', ['Name' => __('messages.user')]) }}",
                        type: "success",
                    });
                    $("#"+id).closest("tr").remove();
                }
                else
                {
                    swal({
                        title: "{{ __('messages.access_denied', ['Name' => __('messages.user')]) }}",
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
    var user_ids = [];

    $(".checkbox:checked").each(function()
    {
        var id = $(this).attr('id');
        user_ids.push(id);
    });
    if (user_ids == '')
    {
        jGrowlAlert("{{ __('messages.select_before_delete_alert') }}", 'danger');
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
            url: "{{ route('users.delete_selected') }}",
            type: 'POST',
            data: {
              ids:user_ids
            },
            success: function(msg)
            {
                if (msg=="true")
                {
                    swal({
                        title: "{{ __('messages._deleted_successfully', ['Name' => __('messages.user')]) }}",
                        type: "success",
                    });
                    $(user_ids).each(function(index, element) 
                    {
                        $("#"+element).closest("tr").remove();
                    });
                }
                else
                {
                    swal({
                        title: "{{ __('messages.access_denied', ['Name' => __('messages.user')]) }}",
                        type: "error",                             
                    });
                }
            }
        });
    });
}

</script>

@stop
