<!-- Page header -->
<div class="page-header page-header-default">
  <div class="page-header-content">
    <div class="page-title">
      <h4>
        <span class="text-semibold"><?php _el('projects'); ?></span>
      </h4>
    </div>
  </div>
  <div class="breadcrumb-line">
    <ul class="breadcrumb">
      <li>
        <a href="<?php echo base_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i><?php _el('dashboard'); ?></a>
      </li>
      <li class="active">
        <?php _el('projects'); ?>
      </li>
    </ul>
  </div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
  <!-- Panel -->
  <div class="panel panel-flat">
    <?php if (has_permissions('projects','create') || has_permissions('projects','delete')) { ?>
      <!-- Panel heading -->
      <div class="panel-heading">
        <?php if (has_permissions('projects','create')) { ?>
          <a href="<?php echo base_url('admin/projects/add'); ?>" class="btn btn-primary"><?php _el('add_new'); ?><i class="icon-plus-circle2 position-right"></i></a>
        <?php } ?>
        <?php if (has_permissions('projects','delete')) { ?>
        <a href="javascript:delete_selected();" class="btn btn-danger" id="delete_selected"><?php _el('delete_selected'); ?><i class=" icon-trash position-right"></i></a>
        <?php } ?>
      </div>
      <!-- /Panel heading -->
    <?php } ?>
    
    <!-- Listing table -->
    <div class="panel-body table-responsive">
      <table id="projects_table" class="table table-bordered table-striped">
        <thead>
          <tr>
            <?php if (has_permissions('projects','delete')) { ?>
            <th width="2%">
              <input type="checkbox" name="select_all" id="select_all" class="styled" onclick="select_all(this);" >
            </th>
            <?php } ?>
            <th width="10%"><?php _el('id'); ?></th>
            <th width="20%"><?php _el('name'); ?></th>
            <th width="50%"><?php _el('description'); ?></th>
            <th width="10%"><?php _el('created_at'); ?></th>
            <?php if (has_permissions('projects','edit') || has_permissions('projects','delete')) { ?>
            <th width="8%" class="text-center"><?php _el('actions'); ?></th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($projects as $key => $project) { ?>
          <tr>
            <?php if (has_permissions('projects','delete')) { ?>
            <td>
              <input type="checkbox" class="checkbox styled"  name="delete"  id="<?php  echo $project['id']; ?>">
            </td>
            <?php } ?>
            <td><?php echo $project['project_id'];?></td>
            <td><?php echo ucfirst($project['name']);?></td>
            <td><?php echo ucfirst($project['details']);?></td>
            <td><?php echo display_date_time($project['created']); ?></td>
            <?php if (has_permissions('projects','edit') || has_permissions('projects','delete')) { ?>
            <td class="text-center">
              <?php if (has_permissions('projects','edit')) { ?>
                <a data-popup="tooltip" data-placement="top"  title="<?php _el('edit') ?>" href="<?php echo site_url('admin/projects/edit/').$project['id']; ?>" id="<?php echo $project['id']; ?>" class="text-info">
                  <i class="icon-pencil7"></i>
                </a>
              <?php } ?>
              <?php if (has_permissions('projects','delete')) { ?>
                <a data-popup="tooltip" data-placement="top"  title="<?php _el('delete') ?>" href="javascript:delete_record(<?php echo $project['id']; ?>);" class="text-danger delete" id="<?php echo $project['id']; ?>"><i class=" icon-trash"></i></a>
              <?php } ?>
            </td>
            <?php } ?>
          </tr>
          <?php } ?>
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

var BASE_URL = "<?php echo base_url(); ?>";

/**
 * Deletes a single record when clicked on delete icon
 *
 * @param {int}  id  The identifier
 */
function delete_record(id) 
{ 
    swal({
        title: "<?php _el('single_deletion_alert'); ?>",
        text: "<?php _el('single_recovery_alert'); ?>",
        type: "warning",  
        showCancelButton: true, 
        cancelButtonText:"<?php _el('no_cancel_it'); ?>",
        confirmButtonText: "<?php _el('yes_i_am_sure'); ?>",       
    },
    function()
    {
            $.ajax({
                url:BASE_URL+'admin/projects/delete',
                type: 'POST',
                data: {
                    project_id:id
                },
                success: function(msg)
                {
                    if (msg=="true")
                    {                        
                        swal({
                            title: "<?php _el('_deleted_successfully', _l('project')); ?>",
                            type: "success",
                        });
                        $("#"+id).closest("tr").remove();
                    }
                    else
                    {
                        swal({      
                            title: "<?php _el('access_denied', _l('project')); ?>",           
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
    var projects_ids = [];

    $(".checkbox:checked").each(function()
    {
        var id = $(this).attr('id');
        projects_ids.push(id);
    });
    if (projects_ids == '')
    {
        jGrowlAlert("<?php _el('select_before_delete_alert', _l('projects')) ?>", 'danger');
        preventDefault();
    }
    swal({
        title: "<?php _el('multiple_deletion_alert'); ?>",
        text: "<?php _el('multiple_recovery_alert'); ?>",
        type: "warning", 
        showCancelButton: true, 
        cancelButtonText:"<?php _el('no_cancel_it'); ?>",
        confirmButtonText: "<?php _el('yes_i_am_sure'); ?>",        
    },
    function()
    {
        $.ajax({
            url:BASE_URL+'admin/projects/delete_selected',
            type: 'POST',
            data: {
              ids:projects_ids
            },
            success: function(msg)
            {
                if (msg=="true")
                {
                    swal({
                        title: "<?php _el('_deleted_successfully', _l('projects')); ?>",
                        type: "success",
                    });
                    $(projects_ids).each(function(index, element) 
                    {
                        $("#"+element).closest("tr").remove();
                    });
                }
                else
                {
                  swal({
                        title: "<?php _el('access_denied', _l('projects')); ?>",            
                        type: "error",                            
                    });
                }
            }
        });
    });
}
</script>
