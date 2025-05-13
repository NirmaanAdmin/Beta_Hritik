<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="list_po_report" class="hide">
   <div class="col-md-3 form-group" style="padding-left: 0px;">
      <select name="pur_vendor[]" id="pur_vendor" class="selectpicker" multiple="true" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('vendor'); ?>">
         <option value=""></option>
         <?php
         $vendor = get_pur_vendor_list();
         foreach ($vendor as $vendors) { ?>
            <option value="<?php echo $vendors['userid']; ?>"><?php echo  $vendors['company']; ?></option>
         <?php  } ?>
      </select>
   </div>

   <div class="col-md-3 form-group">
      <?php
      $statuses = [
         0 => ['id' => '1', 'name' => _l('purchase_not_yet_approve')],
         1 => ['id' => '2', 'name' => _l('purchase_approved')],
         2 => ['id' => '3', 'name' => _l('purchase_reject')],
         3 => ['id' => '4', 'name' => _l('cancelled')],
      ];
      echo render_select('pur_status[]', $statuses, array('id', 'name'), '', [], array('data-width' => '100%', 'data-none-selected-text' => _l('approval_status'), 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
      ?>
   </div>
   
   <div class="col-md-3 form-group">
      <?php
      echo render_select('department[]', $departments, array('departmentid', 'name'), '', [], array('data-width' => '100%', 'data-none-selected-text' => _l('department'), 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
      ?>
   </div>
   <div class="row">
      <div class="col-md-4">
         <div class="form-group">

         </div>
      </div>
      <div class="clearfix"></div>
   </div>
   <table class="table table-po-report scroll-responsive">
      <thead>
         <tr>
            <th><?php echo _l('purchase_order'); ?></th>
            <th><?php echo _l('date'); ?></th>
            <th><?php echo _l('department'); ?></th>
            <th><?php echo _l('vendor'); ?></th>
            <th><?php echo _l('approval_status'); ?></th>
            <th><?php echo _l('po_value'); ?></th>
            <th><?php echo _l('tax_value'); ?></th>
            <th><?php echo _l('po_value_included_tax'); ?></th>
         </tr>
      </thead>
      <tbody></tbody>
      <tfoot>
         <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_value"></td>
            <td class="total_tax"></td>
            <td class="total"></td>
         </tr>
      </tfoot>
   </table>
</div>