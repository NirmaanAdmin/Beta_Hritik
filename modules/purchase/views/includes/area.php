<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div>
<div class="_buttons">
    <?php if (has_permission('purchase_settings', '', 'edit') || is_admin() ) { ?>

    <a href="#" onclick="new_area(); return false;" class="btn btn-info pull-left display-block">
        <?php echo _l('add_area'); ?>
    </a>
<?php } ?>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<?php $module_name = 'purchase_area'; ?>
<div class="row">
    <div class="col-md-3">
        <?php
        $project_filter = get_module_filter($module_name, 'project');
        $project_filter_val = !empty($project_filter) ? $project_filter->filter_value : ''; 
        echo render_select('select_project', $projects, array('id', 'name'), 'project', $project_filter_val); 
        ?>
    </div>
</div>
<hr class="hr-panel-heading" />
<div class="clearfix"></div>
<table class="table border area-table">
 <thead>
    <th><?php echo _l('id'); ?></th>
    <th><?php echo _l('area_name'); ?></th>
    <th><?php echo _l('project'); ?></th>
    <?php /* 
    <th><?php echo _l('order'); ?></th>
    <th><?php echo _l('display'); ?></th>
    <th><?php echo _l('note'); ?></th>
    */ ?>
    <th><?php echo _l('options'); ?></th>
 </thead>
<tbody>
</tbody>
</table>   

<div class="modal1 fade" id="area_model" tabindex="-1" role="dialog">
    <div class="modal-dialog setting-handsome-table">
      <?php echo form_open_multipart(admin_url('purchase/area'), array('id'=>'add_area')); ?>
      <?php echo form_hidden('area_id'); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="add-title"><?php echo _l('add_area'); ?></span>
                    <span class="edit-title"><?php echo _l('edit_area'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form">
                            <?php echo render_input('area_name', 'area_name', ''); ?>
                            <?php echo render_select('project', $projects, array('id', 'name'), 'project', ''); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
        </div>
    </div>
</div>


</body>
</html>
