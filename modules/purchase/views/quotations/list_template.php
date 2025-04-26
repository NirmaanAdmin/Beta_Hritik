<?php defined('BASEPATH') or exit('No direct script access allowed');
$module_name = 'quotations';
?>
<div class="col-md-12">
  <div class="panel_s mbot10">
    <div class="panel-body _buttons">

      <?php if (has_permission('purchase_quotations', '', 'create')) { ?>
        <a href="<?php echo admin_url('purchase/estimate'); ?>" class="btn btn-info pull-left new"><?php echo _l('create_new_estimate'); ?></a>
      <?php } ?>
      <div class="row all_ot_filters">
        <div class="col-md-3">

          <?php
          $pur_request_type_filter = get_module_filter($module_name, 'pur_request');
          $pur_request_type_filter_val = !empty($pur_request_type_filter) ? explode(",", $pur_request_type_filter->filter_value) : [];
          ?>
          <select name="pur_request[]" id="pur_request" class="selectpicker pull-right mright10" multiple data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('purchase_request'); ?>">
            <?php foreach ($pur_request as $s) {
              $selected = in_array($s['id'], $pur_request_type_filter_val) ? 'selected' : '';
            ?>
              <option value="<?php echo pur_html_entity_decode($s['id']); ?>" <?php echo $selected; ?>>
                <?php echo pur_html_entity_decode($s['pur_rq_code'] . ' - ' . $s['pur_rq_name']); ?>
              </option>
            <?php } ?>
          </select>
        </div>

        <div class="col-md-3">
          <?php
          $vendor_type_filter = get_module_filter($module_name, 'vendor');
          $vendor_type_filter_val = !empty($vendor_type_filter) ? explode(",", $vendor_type_filter->filter_value) : [];
          ?>
          <select name="vendor[]" id="vendor" class="selectpicker pull-right mright10" multiple data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('vendor'); ?>">
            <?php foreach ($vendors as $s) {
              $selected = in_array($s['userid'], $vendor_type_filter_val) ? 'selected' : '';
            ?>
              <option value="<?php echo pur_html_entity_decode($s['userid']); ?>" <?php echo $selected; ?>>
                <?php echo pur_html_entity_decode($s['company']); ?>
              </option>
            <?php } ?>
          </select>
        </div>

        <div class="col-md-3">
          <?php
          $project_type_filter = get_module_filter($module_name, 'project');
          $project_type_filter_val = !empty($project_type_filter) ? explode(",", $project_type_filter->filter_value) : [];
          ?>
          <select name="project[]" id="project" class="selectpicker" multiple="true" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('project'); ?>">
            <?php foreach ($projects as $pj) {
              $selected = in_array($pj['id'], $project_type_filter_val) ? 'selected' : '';
            ?>
              <option value="<?php echo pur_html_entity_decode($pj['id']); ?>" <?php echo $selected; ?>>
                <?php echo pur_html_entity_decode($pj['name']); ?>
              </option>
            <?php } ?>
          </select>
        </div>

        <div class="display-block text-right">
          <a href="#" class="btn btn-default btn-with-tooltip toggle-small-view hidden-xs" onclick="toggle_small_estimate_view('.table-pur_estimates','#estimate'); return false;" data-toggle="tooltip" title="<?php echo _l('estimates_toggle_table_tooltip'); ?>"><i class="fa fa-angle-double-left"></i></a>
        </div>

        <div class="col-md-3 form-group" style="margin-top: 10px;">
          <?php
          $group_pur_type_filter = get_module_filter($module_name, 'group_pur');
          $group_pur_type_filter_val = !empty($group_pur_type_filter) ? explode(",", $group_pur_type_filter->filter_value) : [];
          echo render_select('group_pur[]', $item_group, array('id', 'name'), '', $group_pur_type_filter_val, array('data-width' => '100%', 'data-none-selected-text' => _l('group_pur'), 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false); ?>
        </div>

        <div class="col-md-3 form-group" style="margin-top: 10px;">
          <?php
          $sub_groups_pur_type_filter = get_module_filter($module_name, 'sub_groups_pur');
          $sub_groups_pur_type_filter_val = !empty($sub_groups_pur_type_filter) ? explode(",", $sub_groups_pur_type_filter->filter_value) : [];
          echo render_select('sub_groups_pur[]', $item_sub_group, array('id', 'sub_group_name'), '', $sub_groups_pur_type_filter_val, array('data-width' => '100%', 'data-none-selected-text' => _l('sub_groups_pur'), 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
          ?>
        </div>

        <div class="col-md-3 form-group" style="margin-top: 10px;">

          <?php
          $approval_status_type_filter = get_module_filter($module_name, 'status');
          $approval_status_type_filter_val = !empty($approval_status_type_filter) ? explode(",", $approval_status_type_filter->filter_value) : [];
          $statuses = [
            1 => ['id' => '1', 'name' => _l('draft')],
            2 => ['id' => '2', 'name' => _l('purchase_approved')],
            3 => ['id' => '3', 'name' => _l('purchase_reject')],
          ];

          echo render_select('status[]', $statuses, array('id', 'name'), '', $approval_status_type_filter_val, array('data-width' => '100%', 'data-none-selected-text' => _l('approval_status'), 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false); ?>
        </div>

        <div class="col-md-1 form-group " style="margin-top: 10px;">
          <a href="javascript:void(0)" class="btn btn-info btn-icon reset_all_ot_filters">
            <?php echo _l('reset_filter'); ?>
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12" id="small-table">
      <div class="panel_s">
        <div class="panel-body">
          <!-- if estimateid found in url -->
          <?php echo form_hidden('estimateid', $estimateid); ?>
          <?php $this->load->view('quotations/table_html'); ?>
        </div>
      </div>
    </div>
    <div class="col-md-7 small-table-right-col">
      <div id="estimate" class="hide">
      </div>
    </div>
  </div>
</div>