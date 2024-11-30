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
<div class="clearfix"></div>
<table class="table dt-table border table-striped">
 <thead>
    <th><?php echo _l('id'); ?></th>
    <th><?php echo _l('area_name'); ?></th>
    <th><?php echo _l('order'); ?></th>
    <th><?php echo _l('display'); ?></th>
    <th><?php echo _l('note'); ?></th>
    <th><?php echo _l('options'); ?></th>
 </thead>
  <tbody>
    <?php foreach($area as $areas){ ?>

    <tr>
        <td><?php echo _l($areas['id']); ?></td>
        <td><?php echo _l($areas['area_name']); ?></td>
        <td><?php echo _l($areas['order']); ?></td>
        <td><?php if($areas['display'] == 0){ echo _l('not_display'); }else{echo _l('display');} ?></td>
        <td><?php echo _l($areas['note']); ?></td>

        <td>
            <?php if (has_permission('purchase_settings', '', 'edit') || is_admin()) { ?>
              <a href="#" onclick="edit_area(this,<?php echo pur_html_entity_decode($areas['id']); ?>); return false;" data-name="<?php echo pur_html_entity_decode($areas['area_name']); ?>" data-order="<?php echo pur_html_entity_decode($areas['order']); ?>" data-display="<?php echo pur_html_entity_decode($areas['display']); ?>" data-note="<?php echo pur_html_entity_decode($areas['note']); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square"></i>
            </a>
            <?php } ?>

            <?php if (has_permission('purchase_settings', '', 'edit') || is_admin()) { ?> 
            <a href="<?php echo admin_url('purchase/delete_area/'.$areas['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
             <?php } ?>
        </td>
    </tr>
    <?php } ?>
 </tbody>
</table>   

<div class="modal1 fade" id="area" tabindex="-1" role="dialog">
        <div class="modal-dialog setting-handsome-table">
          <?php echo form_open_multipart(admin_url('purchase/area'), array('id'=>'add_area')); ?>

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
                             <div id="area_id">
                             </div>   
                         <div class="form"> 
                            <div class="col-md-12" id="add_handsontable">

                            </div>
                              <?php echo form_hidden('hot_area'); ?>
                        </div>
                        </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                        
                         <button id="latch_assessor" type="button" class="btn btn-info intext-btn" onclick="add_commodity_group_type(this); return false;" ><?php echo _l('submit'); ?></button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
</div>


</body>
</html>
