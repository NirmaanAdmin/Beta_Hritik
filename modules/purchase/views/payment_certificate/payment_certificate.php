<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style type="text/css">
  .table {
    width: 100%;
    border-collapse: collapse;
  }
  .table th {
    text-align: left;
    font-weight: bold !important;
  }
  .table th, .table td {
    border: 1px solid black !important;
    padding: 8px;
    text-align: left;
    color: black !important;
  }
  .table thead, .table_head {
    background-color: #f2f2f2;
    font-weight: bold;
  }
  .payment_certificate_body .form-group {
    margin-bottom: 0px !important;
  }
  .works_executed_on_a_class .bootstrap-select {
    width: 100px !important;
  }
  .labour_cess_class .bootstrap-select {
    width: 100px !important;
  }
  .mobilization_advance_class .bootstrap-select {
    width: 100px !important;
  }
  .mobilization_advance_class .form-group {
    width: 100px !important;
    display: inline-block;
  }
  .cgst_tax_class .bootstrap-select, .sgst_tax_class .bootstrap-select {
    width: 100px !important;
  }
</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <?php
      echo form_open_multipart($this->uri->uri_string(), array('id' => 'payment_certificate_form', 'class' => '_payment_transaction_form'));
      if (isset($payment_certificate)) {
        echo form_hidden('isedit');
      }
      ?>
      <div class="col-md-12">
        <div class="panel_s accounting-template estimate">
          <div class="panel-body">
            <div class="row">
              <?php echo form_hidden('po_id', $po_id); ?>
              <?php echo form_hidden('payment_certificate_id', $payment_certificate_id); ?>
              <div class="col-md-3">
                <?php $serial_no = (isset($payment_certificate) ? $payment_certificate->serial_no : get_payment_certificate_serial_no($po_id));
                echo render_input('serial_no', 'serial_no', $serial_no); ?>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="pay_cert_options"><?php echo _l('pay_cert_options'); ?></label>
                  <select name="pay_cert_options" id="pay_cert_options" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
                  <option value=""></option>
                  <option value="interim" <?php if (isset($payment_certificate) && $payment_certificate->pay_cert_options == 'interim') { echo 'selected';} ?>><?php echo _l('option_interim'); ?></option>
                  <option value="ad_hoc" <?php if (isset($payment_certificate) && $payment_certificate->pay_cert_options == 'ad_hoc') { echo 'selected';} ?>><?php echo _l('option_ad_hoc'); ?></option>
                  <option value="final" <?php if (isset($payment_certificate) && $payment_certificate->pay_cert_options == 'final') { echo 'selected';} ?>><?php echo _l('option_final'); ?></option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <?php $vendor_name = get_vendor_company_name($pur_order->vendor);
                echo render_input('vendor', 'vendor', $vendor_name, 'text', ['disabled' => 'disabled']); ?>
              </div>
              <div class="col-md-3">
                <?php $po_no = $pur_order->pur_order_number;
                echo render_input('po_no', 'po_no', $po_no, 'text', ['disabled' => 'disabled']); ?>
              </div>
            </div>

            <div class="row">
              <div class="col-md-3">
                <?php $po_date = _d($pur_order->order_date);
                echo render_date_input('po_date', 'po_date', $po_date, ['disabled' => 'disabled']); ?>
              </div>
              <div class="col-md-3">
                <?php $po_description = $pur_order->pur_order_name;
                echo render_input('po_description', 'po_description', $po_description, 'text', ['disabled' => 'disabled']); ?>
              </div>
              <div class="col-md-3">
                <?php $project = get_project_name_by_id($pur_order->project);
                echo render_input('project', 'project', $project, 'text', ['disabled' => 'disabled']); ?>
              </div>
              <div class="col-md-3">
                <?php $location = (isset($payment_certificate) ? $payment_certificate->location : '');
                echo render_input('location', 'Location', $location); ?>
              </div>
            </div>

            <div class="row">
              <div class="col-md-3">
                <?php $invoice_ref = (isset($payment_certificate) ? $payment_certificate->invoice_ref : '');
                echo render_input('invoice_ref', 'invoice_ref', $invoice_ref); ?>
              </div>
              <div class="col-md-3">
                <?php $bill_period_upto = (isset($payment_certificate) ? _d($payment_certificate->bill_period_upto) : '');
                echo render_date_input('bill_period_upto', 'bill_period_upto', $bill_period_upto); ?>
              </div>
              <div class="col-md-3">
                <?php $bill_received_on = (isset($payment_certificate) ? _d($payment_certificate->bill_received_on) : _d(date('Y-m-d')));
                echo render_date_input('bill_received_on', 'bill_received_on', $bill_received_on); ?>
              </div>
            </div>

            <?php if($is_view == 1 && get_staff_user_id() == 2) { ?>
              <div class="row">
                <div class="col-md-12 form-group">
                  <select name="status" id="status" class="selectpicker pull-right mright10" onchange="change_status_pay_cert(this,<?php echo ($payment_certificate_id); ?>); return false;" data-live-search="true" data-width="35%" data-none-selected-text="<?php echo _l('pur_change_status_to'); ?>">
                       <option value=""></option>
                       <option value="1"><?php echo _l('purchase_draft'); ?></option>
                       <option value="2"><?php echo _l('purchase_approved'); ?></option>
                       <option value="3"><?php echo _l('pur_rejected'); ?></option>
                       <option value="4"><?php echo _l('pur_canceled'); ?></option>
                    </select>
                </div>
              </div>
            <?php } ?>
          </div>

          <div class="panel-body mtop15">
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table items no-mtop">
                    <thead>
                      <tr>
                        <th width="5%"><?php echo _l('serial_no'); ?></th>
                        <th width="43%"><?php echo _l('decription'); ?></th>
                        <th width="13%"><?php echo _l('contract_amount'); ?></th>
                        <th width="13%"><?php echo _l('previous'); ?></th>
                        <th width="13%"><?php echo _l('this_bill'); ?></th>
                        <th width="13%"><?php echo _l('comulative'); ?></th>
                      </tr>
                    </thead>
                    <tbody class="payment_certificate_body">
                      <tr>
                        <td>A1</td>
                        <td class="po_name"></td>
                        <td class="po_contract_amount"></td>
                        <td>
                          <?php 
                          $po_previous = (isset($payment_certificate) ? $payment_certificate->po_previous : 0);
                          echo render_input('po_previous', '', $po_previous, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td>
                          <?php 
                          $po_this_bill = (isset($payment_certificate) ? $payment_certificate->po_this_bill : 0);
                          echo render_input('po_this_bill', '', $po_this_bill, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td class="po_comulative"></td>
                      </tr>
                      <tr class="table_head">
                        <td>A</td>
                        <td><?php echo _l('total_value_of_works_executed'); ?></td>
                        <td class="po_contract_amount"></td>
                        <td class="total_po_previous"></td>
                        <td class="total_po_this_bill"></td>
                        <td class="po_comulative"></td>
                      </tr>
                      <tr class="table_head">
                        <td>B</td>
                        <td><?php echo _l('pay_cert_b_title'); ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td>C1</td>
                        <td class="mobilization_advance_class">
                        Mobilization Advance payment 
                        <select name="mobilization_advance" id="mobilization_advance" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" onchange="calculate_payment_certificate()">
                        <option value="0%" <?php if (isset($payment_certificate) && $payment_certificate->mobilization_advance == '0%') { echo 'selected';} ?>>0%</option>
                        <option value="2.5%" <?php if (isset($payment_certificate) && $payment_certificate->mobilization_advance == '2.5%') { echo 'selected';} ?>>2.5%</option>
                      </select>
                        as per clause 
                        <?php
                        $payment_clause = (isset($payment_certificate) ? $payment_certificate->payment_clause  : '14.2'); 
                        echo render_input('payment_clause', '', $payment_clause, 'number'); 
                        ?>
                        </td>
                        <td>
                          <?php 
                          $pay_cert_c1_1 = (isset($payment_certificate) ? $payment_certificate->pay_cert_c1_1 : '');
                          echo render_input('pay_cert_c1_1', '', $pay_cert_c1_1, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td>
                          <?php 
                          $pay_cert_c1_2 = (isset($payment_certificate) ? $payment_certificate->pay_cert_c1_2 : '');
                          echo render_input('pay_cert_c1_2', '', $pay_cert_c1_2, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td>
                          <?php 
                          $pay_cert_c1_3 = (isset($payment_certificate) ? $payment_certificate->pay_cert_c1_3 : '');
                          echo render_input('pay_cert_c1_3', '', $pay_cert_c1_3, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td class="pay_cert_c1_4"></td>
                      </tr>
                      <tr>
                        <td>C2</td>
                        <td><?php echo _l('pay_cert_c2_title'); ?></td>
                        <td>
                          <?php 
                          $pay_cert_c2_1 = (isset($payment_certificate) ? $payment_certificate->pay_cert_c2_1 : ''); 
                          echo render_input('pay_cert_c2_1', '', $pay_cert_c2_1, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td>
                          <?php 
                          $pay_cert_c2_2 = (isset($payment_certificate) ? $payment_certificate->pay_cert_c2_2 : ''); 
                          echo render_input('pay_cert_c2_2', '', $pay_cert_c2_2, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td>
                          <?php
                          $pay_cert_c2_3 = (isset($payment_certificate) ? $payment_certificate->pay_cert_c2_3 : '');  
                          echo render_input('pay_cert_c2_3', '', $pay_cert_c2_3, 'number', ['oninput' => "calculate_payment_certificate()"]);  
                          ?>
                        </td>
                        <td class="pay_cert_c2_4"></td>
                      </tr>
                      <tr class="table_head">
                        <td>C</td>
                        <td><?php echo _l('net_advance'); ?></td>
                        <td class="net_advance_1"></td>
                        <td class="net_advance_2"></td>
                        <td class="net_advance_3"></td>
                        <td class="net_advance_4"></td>
                      </tr>
                      <tr class="table_head">
                        <td>D</td>
                        <td><?php echo _l('sub_total_ac'); ?></td>
                        <td class="sub_total_ac_1"></td>
                        <td class="sub_total_ac_2"></td>
                        <td class="sub_total_ac_3"></td>
                        <td class="sub_total_ac_4"></td>
                      </tr>
                      <tr>
                        <td>E1</td>
                        <td><?php echo _l('retention_fund'); ?></td>
                        <td>
                          <?php 
                          $ret_fund_1 = (isset($payment_certificate) ? $payment_certificate->ret_fund_1 : ''); 
                          echo render_input('ret_fund_1', '', $ret_fund_1, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td>
                          <?php
                          $ret_fund_2 = (isset($payment_certificate) ? $payment_certificate->ret_fund_2 : ''); 
                          echo render_input('ret_fund_2', '', $ret_fund_2, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td>
                          <?php
                          $ret_fund_3 = (isset($payment_certificate) ? $payment_certificate->ret_fund_3 : '');  
                          echo render_input('ret_fund_3', '', $ret_fund_3, 'number', ['oninput' => "calculate_payment_certificate()"]);  
                          ?>
                        </td>
                        <td class="ret_fund_4"></td>
                      </tr>
                      <tr>
                        <td>E2</td>
                        <td class="works_executed_on_a_class">
                          <?php echo _l('works_executed_5_of_A'); ?>
                          <select name="works_executed_on_a" id="works_executed_on_a" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" onchange="calculate_payment_certificate()">
                            <option value="0%" <?php if (isset($payment_certificate) && $payment_certificate->works_executed_on_a == '0%') { echo 'selected';} ?>>0%</option>
                            <option value="5%" <?php if (isset($payment_certificate) && $payment_certificate->works_executed_on_a == '5%') { echo 'selected';} ?>>5%</option>
                            <option value="10%" <?php if (isset($payment_certificate) && $payment_certificate->works_executed_on_a == '10%') { echo 'selected';} ?>>10%</option>
                          </select>
                        </td>
                        <td>
                          <?php 
                          $works_exe_a_1 = (isset($payment_certificate) ? $payment_certificate->works_exe_a_1 : '');
                          echo render_input('works_exe_a_1', '', $works_exe_a_1, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td>
                          <?php 
                          $works_exe_a_2 = (isset($payment_certificate) ? $payment_certificate->works_exe_a_2 : '');
                          echo render_input('works_exe_a_2', '', $works_exe_a_2, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td>
                          <?php 
                          $works_exe_a_3 = (isset($payment_certificate) ? $payment_certificate->works_exe_a_3 : '');
                          echo render_input('works_exe_a_3', '', $works_exe_a_3, 'number', ['oninput' => "calculate_payment_certificate()"]);  
                          ?>
                        </td>
                        <td class="works_exe_a_4"></td>
                      </tr>
                      <tr class="table_head">
                        <td>E</td>
                        <td><?php echo _l('less_total_retention'); ?></td>
                        <td class="less_ret_1"></td>
                        <td class="less_ret_2"></td>
                        <td class="less_ret_3"></td>
                        <td class="less_ret_4"></td>
                      </tr>
                      <tr class="table_head">
                        <td>F</td>
                        <td><?php echo _l('sub_total_de'); ?></td>
                        <td class="sub_t_de_1"></td>
                        <td class="sub_t_de_2"></td>
                        <td class="sub_t_de_3"></td>
                        <td class="sub_t_de_4"></td>
                      </tr>
                      <tr>
                        <td>G1</td>
                        <td><?php echo _l('less_title'); ?></td>
                        <td>
                          <?php
                          $less_1 = (isset($payment_certificate) ? $payment_certificate->less_1 : '');
                          echo render_input('less_1', '', $less_1, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td>
                          <?php 
                          $less_2 = (isset($payment_certificate) ? $payment_certificate->less_2 : '');
                          echo render_input('less_2', '', $less_2, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td>
                          <?php
                          $less_3 = (isset($payment_certificate) ? $payment_certificate->less_3 : '');
                          echo render_input('less_3', '', $less_3, 'number', ['oninput' => "calculate_payment_certificate()"]);  
                          ?>
                        </td>
                        <td class="less_4"></td>
                      </tr>
                      <tr>
                        <td>G2</td>
                        <td><?php echo _l('less_amount_hold_for_quality_ncr'); ?></td>
                        <td>
                          <?php 
                          $less_ah_1 = (isset($payment_certificate) ? $payment_certificate->less_ah_1 : '');
                          echo render_input('less_ah_1', '', $less_ah_1, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td>
                          <?php 
                          $less_ah_2 = (isset($payment_certificate) ? $payment_certificate->less_ah_2 : '');
                          echo render_input('less_ah_2', '', $less_ah_2, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td>
                          <?php
                          $less_ah_3 = (isset($payment_certificate) ? $payment_certificate->less_ah_3 : ''); 
                          echo render_input('less_ah_3', '', $less_ah_3, 'number', ['oninput' => "calculate_payment_certificate()"]);  
                          ?>
                        </td>
                        <td class="less_ah_4"></td>
                      </tr>
                      <tr>
                        <td>G2</td>
                        <td><?php echo _l('less_amount_hold_for_testing_and_comissioning'); ?></td>
                        <td>
                          <?php
                          $less_aht_1 = (isset($payment_certificate) ? $payment_certificate->less_aht_1 : ''); 
                          echo render_input('less_aht_1', '', $less_aht_1, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td>
                          <?php
                          $less_aht_2 = (isset($payment_certificate) ? $payment_certificate->less_aht_2 : '');  
                          echo render_input('less_aht_2', '', $less_aht_2, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td>
                          <?php
                          $less_aht_3 = (isset($payment_certificate) ? $payment_certificate->less_aht_3 : '');  
                          echo render_input('less_aht_3', '', $less_aht_3, 'number', ['oninput' => "calculate_payment_certificate()"]);  
                          ?>
                        </td>
                        <td class="less_aht_4"></td>
                      </tr>
                      <tr class="table_head">
                        <td>G</td>
                        <td><?php echo _l('less_deductions'); ?></td>
                        <td class="less_ded_1"></td>
                        <td class="less_ded_2"></td>
                        <td class="less_ded_3"></td>
                        <td class="less_ded_4"></td>
                      </tr>
                      <tr class="table_head">
                        <td>H</td>
                        <td><?php echo _l('sub_total_exclusive_of_taxes'); ?></td>
                        <td class="sub_fg_1"></td>
                        <td class="sub_fg_2"></td>
                        <td class="sub_fg_3"></td>
                        <td class="sub_fg_4"></td>
                      </tr>
                      <tr>
                        <td>I1</td>
                        <td class="cgst_tax_class">
                        CGST @
                        <select name="cgst_tax" id="cgst_tax" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" onchange="calculate_payment_certificate()">
                        <?php
                        $taxes = get_taxes_list();
                        if(!empty($taxes)) {
                          foreach ($taxes as $key => $value) { ?>
                            <option value="<?php echo $value['name']; ?>" <?php if (isset($payment_certificate) && $payment_certificate->cgst_tax == $value['name']) { echo 'selected';} ?>><?php echo $value['name']; ?></option>
                          <?php } 
                        } ?>
                        </select>
                        on A
                        </td>
                        <td class="cgst_on_a1"></td>
                        <td class="cgst_on_a2"></td>
                        <td class="cgst_on_a3"></td>
                        <td class="cgst_on_a4"></td>
                      </tr>
                      <tr>
                        <td>I2</td>
                        <td class="sgst_tax_class">
                        SGST @
                        <select name="sgst_tax" id="sgst_tax" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" onchange="calculate_payment_certificate()">
                        <?php
                        $taxes = get_taxes_list();
                        if(!empty($taxes)) {
                          foreach ($taxes as $key => $value) { ?>
                            <option value="<?php echo $value['name']; ?>" <?php if (isset($payment_certificate) && $payment_certificate->sgst_tax == $value['name']) { echo 'selected';} ?>><?php echo $value['name']; ?></option>
                          <?php } 
                        } ?>
                        </select>
                        on A
                        </td>
                        <td class="sgst_on_a1"></td>
                        <td class="sgst_on_a2"></td>
                        <td class="sgst_on_a3"></td>
                        <td class="sgst_on_a4"></td>
                      </tr>
                      <tr>
                        <td>I3</td>
                        <td class="labour_cess_class">
                          <?php echo _l('labour_cess'); ?>
                          <select name="labour_cess" id="labour_cess" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" onchange="calculate_payment_certificate()">
                            <option value="0%" <?php if (isset($payment_certificate) && $payment_certificate->labour_cess == '0%') { echo 'selected';} ?>>0%</option>
                            <option value="1%" <?php if (isset($payment_certificate) && $payment_certificate->labour_cess == '1%') { echo 'selected';} ?>>1%</option>
                          </select>
                        </td>
                        <td>
                          <?php
                          $labour_cess_1 = (isset($payment_certificate) ? $payment_certificate->labour_cess_1 : ''); 
                          echo render_input('labour_cess_1', '', $labour_cess_1, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td>
                          <?php
                          $labour_cess_2 = (isset($payment_certificate) ? $payment_certificate->labour_cess_2 : ''); 
                          echo render_input('labour_cess_2', '', $labour_cess_2, 'number', ['oninput' => "calculate_payment_certificate()"]); 
                          ?>
                        </td>
                        <td>
                          <?php 
                          $labour_cess_3 = (isset($payment_certificate) ? $payment_certificate->labour_cess_3 : '');
                          echo render_input('labour_cess_3', '', $labour_cess_3, 'number', ['oninput' => "calculate_payment_certificate()"]);  
                          ?>
                        </td>
                        <td class="labour_cess_4"></td>
                      </tr>
                      <tr class="table_head">
                        <td>I</td>
                        <td><?php echo _l('total_applicable_taxes'); ?></td>
                        <td class="tot_app_tax_1"></td>
                        <td class="tot_app_tax_2"></td>
                        <td class="tot_app_tax_3"></td>
                        <td class="tot_app_tax_4"></td>
                      </tr>
                      <tr class="table_head">
                        <td>J</td>
                        <td><?php echo _l('amount_recommended'); ?></td>
                        <td class="amount_rec_1"></td>
                        <td class="amount_rec_2"></td>
                        <td class="amount_rec_3"></td>
                        <td class="amount_rec_4"></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <?php if($is_view == 0) { ?>
            <div class="btn-bottom-toolbar text-right">
              <button type="button" class="btn-tr btn btn-info mleft10 pay-cert-submit">
                <?php echo _l('submit'); ?>
              </button>
            </div>
          <?php } ?>

        </div>
      </div>
      <?php echo form_close(); ?>
    </div>

    <?php if (count($list_approve_status) > 0 && $is_view == 1) { ?>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <div class="project-overview-right">
               <div class="row">
                  <div class="col-md-12 project-overview-expenses-finance">
                     <?php
                     $this->load->model('staff_model');
                     $enter_charge_code = 0;
                     foreach ($list_approve_status as $value) {
                        $value['staffid'] = explode(', ', $value['staffid'] ?? '');

                        if ($value['action'] == 'sign') { ?>
                          <div class="col-md-4 apr_div">
                            <p class="text-uppercase text-muted no-mtop bold">
                               <?php
                               $staff_name = '';
                               $st = _l('status_0');
                               $color = 'warning';
                               foreach ($value['staffid'] as $key => $val) {
                                  if ($staff_name != '') {
                                     $staff_name .= ' or ';
                                  }
                                  $staff_name .= $this->staff_model->get($val)->firstname;
                               }
                               echo pur_html_entity_decode($staff_name);
                               ?>
                             </p>
                            <?php if ($value['approve'] == 2) {
                            ?>
                               <img src="<?php echo site_url(PURCHASE_PATH . 'pur_order/signature/' . $estimate->id . '/signature_' . $value['id'] . '.png'); ?>" class="img_style">
                               <br><br>
                               <p class="bold text-center text-success"><?php echo _l('signed') . ' ' . _dt($value['date']); ?></p>
                            <?php } ?>
                          </div>
                        <?php } else { ?>
                           <div class="col-md-4 apr_div">
                              <p class="text-uppercase text-muted no-mtop bold">
                               <?php
                               $staff_name = '';
                               foreach ($value['staffid'] as $key => $val) {
                                  if ($staff_name != '') {
                                     $staff_name .= ' or ';
                                  }
                                  $staff_name .= $this->staff_model->get($val)->firstname;
                               }
                               echo pur_html_entity_decode($staff_name);
                               ?>
                              </p>

                              <?php if ($value['approve'] == 2) {
                              ?>
                              <?php if($value['approve_by_admin'] == 1) { ?>
                                 <img src="<?php echo site_url(PURCHASE_PATH . 'approval/approved_by_admin.png'); ?>" class="img_style">
                              <?php } else { ?>
                                 <img src="<?php echo site_url(PURCHASE_PATH . 'approval/approved.png'); ?>" class="img_style">
                              <?php } ?>
                              <?php } elseif ($value['approve'] == 3) { ?>
                                 <img src="<?php echo site_url(PURCHASE_PATH . 'approval/rejected.png'); ?>" class="img_style">
                              <?php } ?>
                              <br><br>
                              <p><?php echo pur_html_entity_decode($value['note']) ?></p>
                              <p class="bold text-center text-<?php if ($value['approve'] == 2) {
                                  echo 'success';
                               } elseif ($value['approve'] == 3) {
                                  echo 'danger';
                               } ?>"><?php echo _dt($value['date']); ?>
                              </p>

                              <?php
                              if (isset($check_approve_status['staffid'])) {
                                if (in_array(get_staff_user_id(), $check_approve_status['staffid']) && !in_array(get_staff_user_id(), $get_staff_sign) && $value['staffid'] == $check_approve_status['staffid']) { ?>
                                  <div class="btn-group">
                                    <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo _l('approve'); ?><span class="caret"></span></a>

                                    <ul class="dropdown-menu dropdown-menu-right ul_style" style="width: max-content;">
                                      <li>
                                         <div class="col-md-12">
                                            <?php echo render_textarea('reason', 'reason'); ?>
                                         </div>
                                      </li>
                                      <li>
                                         <div class="row text-right col-md-12">
                                            <a href="#" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="approve_payment_certificate_request(<?php echo pur_html_entity_decode($payment_certificate_id); ?>); return false;" class="btn btn-success mright15"><?php echo _l('approve'); ?></a>
                                            <a href="#" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="deny_payment_certificate_request(<?php echo pur_html_entity_decode($payment_certificate_id); ?>); return false;" class="btn btn-warning"><?php echo _l('deny'); ?></a>
                                         </div>
                                      </li>
                                    </ul>
                                  </div>
                              <?php }
                              } ?>
                           </div>
                        <?php }

                     } ?>
                  </div>
               </div>
           </div>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>

  </div>
</div>
</div>
<?php init_tail(); ?>
</body>
</html>

<script type="text/javascript">
</script>
<?php require 'modules/purchase/assets/js/payment_certificate_js.php'; ?>
<script>
  $(document).ready(function() {
    "use strict";
    var is_view = <?php echo $is_view; ?>;
    if(is_view == 1) {
      $('input, select').not('select[name="status"]').prop('disabled', true);
      $('.selectpicker').selectpicker('refresh');
    } else {
      $('.selectpicker').selectpicker('refresh');
    }
  });
</script>