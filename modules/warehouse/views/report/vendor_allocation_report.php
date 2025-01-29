<div class="col-md-12">
  <?php echo form_open_multipart(admin_url('warehouse/vendor_allocation_report_pdf'), array('id'=>'print_report')); ?>
  <div class="row">
    <div class=" col-md-3">
      <div class="form-group">
      <label><?php echo _l('warehouse_name') ?></label>
      <select name="warehouse_filter[]" id="warehouse_filter" class="selectpicker" multiple="true" data-live-search="true" data-width="100%" data-none-selected-text="" data-actions-box="true">

          <?php foreach($warehouse_filter as $warehouse) { ?>
            <option value="<?php echo html_entity_decode($warehouse['warehouse_id']); ?>"><?php echo html_entity_decode($warehouse['warehouse_name']); ?></option>
            <?php } ?>
        </select>
        </div>
    </div>
    <div class=" col-md-3 ">
      <?php $this->load->view('warehouse/item_include/item_select', ['select_name' => 'commodity_filter[]', 'id_name' => 'commodity_filter', 'multiple' => true,  'label_name' => 'commodity']); ?>
    </div>
    <div class="col-md-2">
      <?php echo render_date_input('from_date','from_date',date('Y-m-d',strtotime('-7 day',strtotime(date('Y-m-d'))))); ?>
    </div>
    <div class="col-md-2">
      <?php echo render_date_input('to_date','to_date',date('Y-m-d')); ?>
    </div>
    <?php /*
    <div class="col-md-1 button-pdf-margin-top">
      <div class="form-group">
        <div class="btn-group">
         <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file-pdf-o"></i><?php if(is_mobile()){echo ' PDF';} ?> <span class="caret"></span></a>
         <ul class="dropdown-menu dropdown-menu-right">
            <li class="hidden-xs"><a href="?output_type=I" target="_blank" onclick="vendor_allocation_submit(this); return false;"><?php echo _l('download_pdf'); ?></a>
            </li>
         </ul>
         </div>
       </div>
    </div>
    */ ?>
    <div class="col-md-1" >
      <a href="#" onclick="get_data_vendor_allocation_report(); return false;" class="btn btn-info button-pdf-margin-top" ><?php echo _l('_filter'); ?></a>
    </div>
  </div>
  <?php echo form_close(); ?>
</div>

<hr class="hr-panel-heading" />
<div class="col-md-12" id="report">
    <div class="panel panel-info col-md-12 panel-padding">
      <div class="panel-body" id="vendor_a_report">
      </div>
    </div>
</div>
