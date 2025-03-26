<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
<div class="row">
	
	<div class="col-md-12">
		<div class="panel_s">
			<div class="panel-body">
				<h4><?php echo pur_html_entity_decode($title) ?></h4>
				<hr class="mtop5">
				<?php /* 
				<a href="<?php echo site_url('purchase/vendors_portal/add_update_invoice'); ?>" class="btn btn-info"><?php echo _l('add_new'); ?></a> 
				<br><br>
				*/ ?>

				<table class="table dt-table">
			       <thead>
			       	  <th><?php echo _l('invoice_code'); ?></th>
                      <th><?php echo _l('invoice_number'); ?></th>
                      <th><?php echo _l('vendor'); ?></th>
                      <th><?php echo _l('group_pur'); ?></th>
                      <th><?php echo _l('description_of_services'); ?></th>
                      <th><?php echo _l('invoice_date'); ?></th>
                      <th><?php echo _l('billing_status'); ?></th>
                      <th><?php echo _l('ril_invoice'); ?></th>
                      <th><?php echo _l('amount_without_tax'); ?></th>
                      <th><?php echo _l('vendor_submitted_tax_amount'); ?></th>
                      <th><?php echo _l('final_certified_amount'); ?></th>
                      <th><?php echo _l('adminnote'); ?></th>
			       </thead>
			      <tbody>
			         <?php foreach($invoices as $inv) { ?>
			         	<?php 
			         		$base_currency = get_base_currency_pur(); 
			         		if($inv['currency'] != 0){
			         			$base_currency = pur_get_currency_by_id($inv['currency']);
			         		}
			         	?>
			         <tr class="inv_tr">
			         	<td class="inv_tr">
			         		<a href="<?php echo site_url('purchase/vendors_portal/invoice/'.$inv['id']); ?>"><?php echo pur_html_entity_decode($inv['invoice_number']); ?>
			         		</a>
			         	</td>
			         	<td class="inv_tr">
			         		<?php 
			         		$vendor_invoice_number = ($inv['vendor_invoice_number'] != '' ? $inv['vendor_invoice_number'] : $inv['invoice_number']);
			         		echo pur_html_entity_decode($vendor_invoice_number); 
			         		?>
			         	</td>
			         	<td class="inv_tr">
			         		<?php echo get_vendor_company_name($inv['vendor'],''); ?>
			         	</td>
			         	<td class="inv_tr">
			         		<?php 
			         		$budget_head = get_group_name_item($inv['group_pur']);
			         		echo $budget_head->name; ?>
			         	</td>
			         	<td class="inv_tr">
			         		<?php echo $inv['description_services']; ?>
			         	</td>
			         	<td class="inv_tr">
			         		<?php echo _d($inv['invoice_date']); ?>
			         	</td>
			         	<td class="inv_tr">
			         		<?php 
			         		$delivery_status = ''; 
			         		if ($inv['payment_status'] == 1) {
				                $delivery_status = '<span class="inline-block label label-danger" id="status_span_' . $inv['id'] . '" task-status-table="rejected">' . _l('rejected');
				            } else if ($inv['payment_status'] == 2) {
				                $delivery_status = '<span class="inline-block label label-info" id="status_span_' . $inv['id'] . '" task-status-table="recevied_with_comments">' . _l('recevied_with_comments');
				            } else if ($inv['payment_status'] == 3) {
				                $delivery_status = '<span class="inline-block label label-warning" id="status_span_' . $inv['id'] . '" task-status-table="bill_verification_in_process">' . _l('bill_verification_in_process');
				            } else if ($inv['payment_status'] == 4) {
				                $delivery_status = '<span class="inline-block label label-primary" id="status_span_' . $inv['id'] . '" task-status-table="bill_verification_on_hold">' . _l('bill_verification_on_hold');
				            } else if ($inv['payment_status'] == 5) {
				                $delivery_status = '<span class="inline-block label label-success" id="status_span_' . $inv['id'] . '" task-status-table="bill_verified_by_ril">' . _l('bill_verified_by_ril');
				            } else if ($inv['payment_status'] == 6) {
				                $delivery_status = '<span class="inline-block label label-success" id="status_span_' . $inv['id'] . '" task-status-table="payment_certifiate_issued">' . _l('payment_certifiate_issued');
				            } else if ($inv['payment_status'] == 7) {
				                $delivery_status = '<span class="inline-block label label-success" id="status_span_' . $inv['id'] . '" task-status-table="payment_processed">' . _l('payment_processed');
				            } else if ($inv['payment_status'] == 0) {
				                $delivery_status = '<span class="inline-block label label-danger" id="status_span_' . $inv['id'] . '" task-status-table="unpaid">' . _l('unpaid');
				            }
				            echo $delivery_status;
			         		?>
			         	</td>
			         	<td class="inv_tr">
			         		<?php 
			         		$expense_convert = '';
				            if ($inv['expense_convert'] == 0) {
				                $expense_convert = '';
				            } else {
				                $expense_convert_check = get_expense_data($inv['expense_convert']);
				                if (!empty($expense_convert_check)) {
				                    if (!empty($expense_convert_check->invoiceid)) {
				                        $invoice_data = get_invoice_data($expense_convert_check->invoiceid);
				                        if (!empty($invoice_data)) {
				                            $expense_convert = $invoice_data->title;
				                        }
				                    }
				                }
				            }
				            echo $expense_convert;
			         		?>
			         	</td>
			         	<td class="inv_tr">
			         		<?php echo app_format_money($inv['vendor_submitted_amount_without_tax'], $base_currency->symbol); ?>
			         	</td>
			         	<td class="inv_tr">
			         		<?php echo app_format_money($inv['vendor_submitted_tax_amount'], $base_currency->symbol); ?>
			         	</td>
			         	<td class="inv_tr">
			         		<?php echo app_format_money($inv['final_certified_amount'], $base_currency->symbol); ?>
			         	</td>
			         	<td class="inv_tr">
			         		<?php echo $inv['adminnote']; ?>
			         	</td>
			         </tr>
			         <?php } ?>
			      </tbody>
			   </table>	
			</div>
		</div>
	</div>
</div>
<?php hooks()->do_action('app_admin_footer'); ?>