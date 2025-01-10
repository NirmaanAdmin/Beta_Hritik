<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <div class="row">
                     <div class="col-md-12">
                      <h4 class="no-margin font-bold"><i class="fa fa-clipboard" aria-hidden="true"></i> <?php echo _l($title); ?></h4>
                      <hr />
                     </div>
                    </div>
                    <div class="row">    
                          <div class="_buttons col-md-12">
                            <?php if (has_permission('purchase_invoices', '', 'create') || is_admin()) { ?>
                            <a href="<?php echo admin_url('purchase/pur_invoice'); ?>"class="btn btn-info pull-left mright10 display-block">
                                <?php echo _l('new'); ?>
                            </a>
                            <?php } ?>
                            <div class="col-md-2">
                                <?php echo render_date_input('from_date','','',array('placeholder' => _l('from_date') )); ?>
                            </div>
                            <div class="col-md-2">
                                <?php echo render_date_input('to_date','','',array('placeholder' => _l('to_date') )); ?>
                            </div>
                            <div class="col-md-2 form-group">
                             
                              <select name="contract[]" id="contract" class="selectpicker" multiple="true"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('contract'); ?>">
                                 <?php foreach($contracts as $ct){ ?>
                                  <option value="<?php echo pur_html_entity_decode($ct['id']); ?>" ><?php echo pur_html_entity_decode($ct['contract_number']); ?></option>
                                 <?php } ?>
                              </select>


                             </div>
                             <div class="col-md-2 form-group">
                               <select name="pur_orders[]" id="pur_orders" class="selectpicker" multiple="true"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('purchase_order'); ?>">
                                 <?php foreach($pur_orders as $ct){ ?>
                                  <option value="<?php echo pur_html_entity_decode($ct['id']); ?>" <?php if($this->input->get('po') != null && $this->input->get('po') == $ct['id']){ echo 'selected'; } ?> ><?php echo pur_html_entity_decode($ct['pur_order_number']); ?></option>
                                 <?php } ?>
                              </select>
                             </div>
                             <div class="col-md-2 form-group">
                               <select name="wo_orders[]" id="wo_orders" class="selectpicker" multiple="true"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('work_order'); ?>">
                                 <?php foreach($wo_orders as $ct){ ?>
                                  <option value="<?php echo pur_html_entity_decode($ct['id']); ?>" <?php if($this->input->get('po') != null && $this->input->get('po') == $ct['id']){ echo 'selected'; } ?> ><?php echo pur_html_entity_decode($ct['wo_order_number']); ?></option>
                                 <?php } ?>
                              </select>
                             </div>
                             <div class="col-md-2 form-group">
                                <?php echo render_select('vendor_ft[]',$vendors,array('userid','company'),'','',array('data-width'=>'100%','data-none-selected-text'=>_l('vendors'),'multiple'=>true,'data-actions-box'=>true),array(),'no-mbot','',false); ?>
                            </div>
                        </div>
                      </div>
                    
                    <?php 

                    $table_data = array(
                        _l('invoice_code'),
                        _l('invoice_number'),
                        _l('vendor'), 
                        _l('group_pur'),                       
                        _l('project'),
                        _l('pur_order'),
                        _l('wo_order'),
                        _l('invoice_date'),
                        _l('payment_request_status'),
                        _l('billing_status'),
                        _l('convert_expense'),
                        _l('amount_without_tax'),
                        _l('tax_value'),
                        _l('total_included_tax'),
                        _l('certified_amount'),
                        _l('transaction_id'),
                        _l('tag'),
                        );

                    $custom_fields = get_custom_fields('pur_invoice',array('show_on_table'=>1));
                    foreach($custom_fields as $field){
                     array_push($table_data,$field['name']);
                    }
                    
                    render_datatable($table_data,'table_pur_invoices'); ?>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="pur_invoice_expense" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <?php echo form_open(admin_url('purchase/add_invoice_expense'),array('id'=>'pur_invoice-expense-form','class'=>'dropzone dropzone-manual')); ?>
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo _l('add_new', _l('expense_lowercase')); ?></h4>
         </div>
         <div class="modal-body">
            <div id="dropzoneDragArea" class="dz-default dz-message">
               <span><?php echo _l('expense_add_edit_attach_receipt'); ?></span>
            </div>
            <div class="dropzone-previews"></div>
            <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('expense_name_help'); ?>"></i>
            <?php echo form_hidden('vendor'); ?>
            <?php echo render_input('expense_name','expense_name'); ?>
            <?php echo render_textarea('note','expense_add_edit_note','',array('rows'=>4),array()); ?>
            <?php echo render_select('clientid',$customers,array('userid','company'),'customer'); ?>

            <?php echo render_select('project_id',$projects,array('id','name'),'project'); ?>

            <?php echo render_select('category',$expense_categories,array('id','name'),'expense_category'); ?>
            <?php echo render_date_input('date','expense_add_edit_date',_d(date('Y-m-d'))); ?>
            <?php echo render_input('amount','expense_add_edit_amount','','number'); ?>
            <div class="row mbot15">
               <div class="col-md-6">
                  <div class="form-group">
                     <label class="control-label" for="tax"><?php echo _l('tax_1'); ?></label>
                     <select class="selectpicker display-block" data-width="100%" name="tax" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                        <option value=""><?php echo _l('no_tax'); ?></option>
                        <?php foreach($taxes as $tax){ ?>
                        <option value="<?php echo pur_html_entity_decode($tax['id']); ?>" data-subtext="<?php echo pur_html_entity_decode($tax['name']); ?>"><?php echo pur_html_entity_decode($tax['taxrate']); ?>%</option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label class="control-label" for="tax2"><?php echo _l('tax_2'); ?></label>
                     <select class="selectpicker display-block" data-width="100%" name="tax2" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" disabled>
                        <option value=""><?php echo _l('no_tax'); ?></option>
                        <?php foreach($taxes as $tax){ ?>
                        <option value="<?php echo pur_html_entity_decode($tax['id']); ?>" data-subtext="<?php echo pur_html_entity_decode($tax['name']); ?>"><?php echo pur_html_entity_decode($tax['taxrate']); ?>%</option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
            </div>
            <div class="hide">
               <?php echo render_select('currency',$currencies,array('id','name','symbol'),'expense_currency',$currency->id); ?>
            </div>
           
            <div class="checkbox checkbox-primary">
               <input type="checkbox" id="billable" name="billable" checked>
               <label for="billable"><?php echo _l('expense_add_edit_billable'); ?></label>
            </div>
            <?php echo render_input('reference_no','expense_add_edit_reference_no'); ?>
           
            <?php
               // Fix becuase payment modes are used for invoice filtering and there needs to be shown all
               // in case there is payment made with payment mode that was active and now is inactive
               $expenses_modes = array();
               foreach($payment_modes as $m){
               if(isset($m['invoices_only']) && $m['invoices_only'] == 1) {continue;}
               if($m['active'] == 1){
               $expenses_modes[] = $m;
               }
               }
               ?>
            <?php echo render_select('paymentmode',$expenses_modes,array('id','name'),'payment_mode'); ?>
            <div class="clearfix mbot15"></div>
            <?php echo render_custom_fields('expenses'); ?>
          <div id="pur_invoice_additional"></div>
            <div class="clearfix"></div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
         </div>
         <?php echo form_close(); ?>
      </div>
      <!-- /.modal-content -->
   </div>
   <!-- /.modal-dialog -->
</div>

<?php init_tail(); ?>
</body>
</html>