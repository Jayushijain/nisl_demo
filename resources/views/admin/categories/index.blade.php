@extends('layouts.admin')

@section('content')

<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <span class="text-semibold">{{ __('messages.categories') }}</span>
            </h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}"><i class="icon-home2 position-left"></i>{{ __('messages.dashboard') }}</a>
            </li>
            <li class="active">{{ __('messages.categories') }}</li>
        </ul>
    </div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
    <!-- Panel -->
    <div class="panel panel-flat">
        @if (has_permissions('categories','create')||has_permissions('categories','delete'))
        <!-- Panel heading -->
        <div class="panel-heading">

            @if (has_permissions('categories','create'))
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add_category_modal">{{ __('messages.add_new') }}<i class="icon-plus-circle2 position-right"></i></button>
            @endif
            @if (has_permissions('categories','delete'))
                <a href="javascript:delete_selected();" class="btn btn-danger" id="delete_selected">{{ __('messages.delete_selected') }}<i class=" icon-trash position-right"></i></a>
            @endif
        </div>
        <!-- /Panel heading -->
        @endif

        <!-- Listing table -->
        <form>
            <input type="hidden" name="_token" value={{ csrf_token() }}>
        <div class="panel-body table-responsive">
            <input type="hidden" id="category_url" value="{{ route('admin.categoriesList') }}">
            <input type="hidden" id="has_delete" value="{{ has_permissions('categories','delete') }}">
            <input type="hidden" id="has_edit" value="{{ has_permissions('categories','edit') }}">
            <table id="categories_table" class="table  table-bordered table-striped data_table">
                <thead>
                    <tr>
                        @if (has_permissions('categories','delete'))
                        <th width="2%">
                        <input type="checkbox" name="select_all" id="select_all" class="styled" onclick="select_all(this);" >
                        </th>
                        @endif
                        <th width="82%">{{ __('messages.name') }}</th>
                        <th width="8%" class="text-center">{{ __('messages.status') }}</th>
                        @if (has_permissions('categories','edit') || has_permissions('categories','delete'))
                        <th width="8%" class="text-center">{{ __('messages.actions') }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $key => $category)
                    <tr>
                        @if (has_permissions('categories','delete'))
                        <td>
                            <input type="checkbox" class="checkbox styled"  name="delete"  id="{{ $category->id }}">
                        </td>
                        @endif
                        <td>{{ ucfirst($category->name) }}</td>
                        @php
                        $readonly_status = '';
                        @endphp
                        @if (!has_permissions('categories','edit'))
                            @php
                            $readonly_status = "readonly";
                            @endphp
                        @endif
                        <td class="text-center switchery-sm">
                            <input type="checkbox" onchange="change_status(this);" class="switchery"  id="{{ $category->id }}" @if ($category->is_active==1) {{ "checked" }} @endif> {{ $readonly_status }}
                        </td>
                        @if (has_permissions('categories','edit') || has_permissions('categories','delete'))
                        <td class="text-center">
                        @if (has_permissions('categories','edit'))
                            <a data-popup="tooltip" data-placement="top"  title="{{ __('messages.edit') }}" href="{{ route('categories.edit',$category->id) }}" id="{{ $category->id }}" class="text-info">
                                <i class="icon-pencil7"></i>
                            </a>
                        @endif
                        @if (has_permissions('categories','delete'))
                            <a data-popup="tooltip" data-placement="top"  title="{{ __('messages.delete') }}" href="javascript:delete_record({{ $category->id }});" class="text-danger delete" id="{{ $category->id }}">
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
        </form>
        <!-- /Listing table -->
    </div>
    <!-- /Panel -->
</div>
<!-- /Content area -->

<!-- Add form modal -->
<div id="add_category_modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">{{ __('messages.add_category') }}</h5>
            </div>

            <form action="{{ route('categories.store') }}" id="categoryform" method="POST">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <small class="req text-danger">*</small>
                                <label>{{ __('messages.name') }}:</label>
                                <input type="text" class="form-control" placeholder="{{ __('messages.category_name') }}" id="name" name="name">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Add form modal -->

@endsection
@section('scripts')
{{-- <script src="{{ asset('admin/js/scripts') }}/categories/index.js"></script> --}}
<script type="text/javascript">
$(function() {

    $('#categories_table').DataTable({
        'columnDefs': [ {
        'targets': [0,2,3],  /*column index*/ 
        'orderable': false, /* disable sorting */
        }],
         
    });

    //add class to style style datatable select box
    $('div.dataTables_length select').addClass('datatable-select');
 });

$("#categoryform").validate({
    rules: {
        name:{
            required: true,
        },
    },
    messages: {
        name: {
            required:"{{ __('messages.please_enter_',['Name' => __('messages.category_name')]) }}",
        },
    }
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
        url: "{{ route('categories.update_status') }}",
        type: 'POST',
        data: {
            category_id: obj.id,
            is_active:checked
        },
        success: function(msg)
        {
            if (msg=='true')
            {
                jGrowlAlert("{{ __('messages._activated',['Name' => __('messages.category')]) }}", 'success');
            }
            else
            {
                jGrowlAlert("{{ __('messages._deactivated', ['Name' => __('messages.category')]) }}", 'success');
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
            url:'/admin/categories/delete/'+id,
            success: function(msg)
            {
                if (msg=="true")
                {
                    swal({
                        title: "{{ __('messages._deleted_successfully', ['Name' => __('messages.category')]) }}",
                        type: "success",
                    });
                    $("#"+id).closest("tr").remove();
                }
                else
                {
                    swal({
                        title: "{{ __('messages.access_denied', ['Name' => __('messages.category')]) }}",
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
    var category_ids = [];

    $(".checkbox:checked").each(function()
    {
        var id = $(this).attr('id');
        category_ids.push(id);
    });
    if (category_ids == '')
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
            url: "{{ route('categories.delete_selected') }}",
            type: 'POST',
            data: {
              ids:category_ids
            },
            success: function(msg)
            {
                if (msg=="true")
                {
                  swal({
                        title: "{{ __('messages._deleted_successfully', ['Name' => __('messages.category')]) }}",
                        type: "success",
                    });
                  $(category_ids).each(function(index, element)
                  {
                      $("#"+element).closest("tr").remove();
                  });
                }
                else
                {
                  swal({
                       title: "{{ __('messages.access_denied', ['Name' => __('messages.category')]) }}",
                        type: "error",
                    });
                }
            }
        });
    });
}

</script>
@stop
