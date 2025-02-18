<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
   .form-inline textarea.form-control {

      width: auto !important;
   }

   .label-purple {
      background-color: rgb(205, 180, 252) !important;
      color: rgb(109, 0, 159);
   }

   .label-teal {
      background-color: #baf8ff;
      color: #0097A7;
   }

   .label-green {
      background-color: #d0fdd2;
      color: #0f8c14;
   }

   .label-secondary {
      background-color: #e1eef9;
      color: #6c757d;
   }

   .label-orange {
      background-color: #f8eedb;
      color: #FFA500;
   }

   .show_hide_columns {
      position: absolute;
      z-index: 999;
      left: 204px
   }

   .loader-container {
      display: flex;
      justify-content: center;
      align-items: center;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.8);
      z-index: 9999;
   }

   .loader-gif {
      width: 100px;
      /* Adjust the size as needed */
      height: 100px;
   }
</style>

<div id="wrapper">
   <div class="content">
      <div class="loader-container hide" id="loader-container">
         <img src="<?php echo site_url('modules/purchase/uploads/lodder/lodder.gif') ?>" alt="Loading..." class="loader-gif">
      </div>
      <div class="row">
         <div class="panel_s mbot10">
            <div class="panel-body">
               <div class="row">
                  <div class="_buttons col-md-3">
                     <strong>
                        <h3><?php echo _l('order_tracker'); ?></h3>
                     </strong>

                  </div>
               </div>
               <div class="row">
                  <hr>
                  <div class="col-md-3 form-group">
                     <label for="type"><?php echo _l('type'); ?></label>
                     <select name="type[]" id="type" class="selectpicker" multiple="true" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('leads_all'); ?>">
                        <option value="pur_orders"><?php echo _l('pur_order'); ?></option>
                        <option value="wo_orders"><?php echo _l('wo_order'); ?></option>
                     </select>
                  </div>
                  <button class="btn btn-info pull-right" style="margin-right: 10px;" data-toggle="modal" data-target="#addNewRowModal">
                     <i class="fa fa-plus"></i> <?php echo _l('New'); ?>
                  </button>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12" id="small-table">
               <div class="panel_s">
                  <div class="panel-body">
                     <div class="btn-group show_hide_columns" id="show_hide_columns">
                        <!-- Settings Icon -->
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 4px 7px;">
                           <i class="fa fa-cog"></i> <?php  ?> <span class="caret"></span>
                        </button>
                        <!-- Dropdown Menu with Checkboxes -->
                        <div class="dropdown-menu" style="padding: 10px; min-width: 250px;">
                           <!-- Select All / Deselect All -->
                           <div>
                              <input type="checkbox" id="select-all-columns"> <strong><?php echo _l('select_all'); ?></strong>
                           </div>
                           <hr>
                           <!-- Column Checkboxes -->
                           <?php
                           $columns = [
                              _l('order_scope'),
                              _l('rli_filter'),
                              _l('contractor'),
                              _l('order_date'),
                              _l('completion_date'),
                              _l('budget_ro_projection'),
                              _l('order_value'),
                              _l('committed_contract_amount'),
                              _l('change_order_amount'),
                              _l('total_rev_contract_value'),
                              _l('anticipate_variation'),
                              _l('cost_to_complete'),
                              _l('final_certified_amount'),
                              _l('category'),
                              _l('group_pur'),
                              _l('remarks')
                           ];
                           ?>
                           <div>
                              <?php foreach ($columns as $key => $label): ?>
                                 <input type="checkbox" class="toggle-column" value="<?php echo $key; ?>" checked>
                                 <?php echo $label; ?><br>
                              <?php endforeach; ?>
                           </div>

                        </div>
                     </div>
                     <?php
                     // Updated table headers to include "Completion Date"
                     $table_data = array(
                        _l('order_scope'),
                        _l('rli_filter'),
                        _l('contractor'),
                        _l('order_date'),
                        _l('completion_date'),
                        _l('budget_ro_projection'),
                        _l('order_value'),
                        _l('committed_contract_amount'),
                        _l('change_order_amount'),
                        _l('total_rev_contract_value'),
                        _l('anticipate_variation'),
                        _l('cost_to_complete'),
                        _l('final_certified_amount'),
                        _l('category'),
                        _l('group_pur'),
                        _l('remarks')
                     );
                     render_datatable($table_data, 'table_order_tracker');
                     ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="addNewRowModal" tabindex="-1" role="dialog">
   <div class="modal-dialog" role="document" style="width: 98%;">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo _l('Add New Order'); ?></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <div class="col-md-8 pull-right">
               <div class="col-md-2 pull-right">
                  <div id="dowload_file_sample" style="margin-top: 22px;">
                     <label for="file_csv" class="control-label"> </label>
                     <a href="<?php echo site_url('modules/purchase/uploads/file_sample/Sample_import_order_tracker_en.xlsx') ?>" class="btn btn-primary">Template</a>
                  </div>
               </div>
               <div class="col-md-4 pull-right" style="display: flex;align-items: end;padding: 0px;">
                  <?php echo form_open_multipart(admin_url('purchase/import_file_xlsx_order_tracker_items'), array('id' => 'import_form')); ?>
                  <?php echo form_hidden('leads_import', 'true'); ?>
                  <?php echo render_input('file_csv', 'choose_excel_file', '', 'file'); ?>

                  <div class="form-group" style="margin-left: 10px;">
                     <button id="uploadfile" type="button" class="btn btn-info import" onclick="return uploadfilecsv(this);"><?php echo _l('import'); ?></button>
                  </div>
                  <?php echo form_close(); ?>
               </div>

            </div>
            <div class="col-md-12 ">
               <div class="form-group pull-right" id="file_upload_response">

               </div>

            </div>
            <div id="box-loading" class="pull-right">

            </div>
         </div>
         <div class="modal-body invoice-item">
            <div class="row">
               <div class="col-md-12">
                  <div class="table-responsive" style="overflow-x: unset !important;">
                     <?php
                     echo form_open_multipart('', array('id' => 'order_tracker-form'));
                     ?>
                     <table class="table order-tracker-items-table items table-main-invoice-edit has-calculations no-mtop">
                        <thead>
                           <tr>
                              <!-- <th align="left"><?php echo _l('serial_no'); ?></th> -->
                              <th align="left"><?php echo _l('order_scope'); ?></th>
                              <th align="left"><?php echo _l('contractor'); ?></th>
                              <th align="left"><?php echo _l('order_date'); ?></th>
                              <th align="left"><?php echo _l('completion_date'); ?></th>
                              <th align="left"><?php echo _l('budget_ro_projection'); ?></th>
                              <th align="left"><?php echo _l('order_value'); ?></th>
                              <th align="left"><?php echo _l('committed_contract_amount'); ?></th>
                              <th align="left"><?php echo _l('change_order_amount'); ?></th>
                              <th align="left"><?php echo _l('anticipate_variation'); ?></th>
                              <th align="left"><?php echo _l('final_certified_amount'); ?></th>
                              <th align="left"><?php echo _l('category'); ?></th>
                              <th align="left"><?php echo _l('group_pur'); ?></th>
                              <th align="left"><?php echo _l('remarks'); ?></th>
                              <th align="center"><i class="fa fa-cog"></i></th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php echo  $order_tracker_row_template; ?>
                        </tbody>
                     </table>
                     <button type="submit" class="btn btn-info pull-right"><?php echo _l('Save'); ?></button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <?php init_tail(); ?>
   <?php require 'modules/purchase/assets/js/import_excel_items_order_tracker_js.php'; ?>
   <?php require 'modules/purchase/assets/js/order_tracker_js.php'; ?>
   </body>

   </html>