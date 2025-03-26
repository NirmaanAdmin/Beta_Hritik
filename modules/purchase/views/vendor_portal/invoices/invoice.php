<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
<div class="row">
	<?php 
		$base_currency = get_base_currency_pur(); 
		if($pur_invoice->currency != 0){
			$base_currency = pur_get_currency_by_id($pur_invoice->currency);
		}
	?>
	<div class="col-md-12">
		<div class="panel_s">
			<div class="panel-body">
				<h4 class="mtop5"><?php echo pur_html_entity_decode($title); ?></h4>
				

				<div class="horizontal-scrollable-tabs preview-tabs-top">
            
		            <div class="horizontal-tabs">
		               <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
		                  <li role="presentation" class="<?php if($this->input->get('tab') != 'discussion'){echo 'active';} ?>">
		                     <a href="#general_infor" aria-controls="general_infor" role="tab" data-toggle="tab">
		                     <?php echo _l('general_infor'); ?>
		                     </a>
		                  </li>

		                  <li role="presentation">
		                     <a href="#payment_record" aria-controls="payment_record" role="tab" data-toggle="tab">
		                     <?php echo _l('payment_record'); ?>
		                     </a>
		                  </li>
		                  
		                  <li role="presentation" class="tab-separator <?php if($this->input->get('tab') === 'discussion'){echo 'active';} ?>">
		                    <?php
		                        $totalComments = total_rows(db_prefix().'pur_comments',['rel_id' => $pur_invoice->id, 'rel_type' => 'pur_invoice']);
		                     ?>
		                     <a href="#discuss" aria-controls="discuss" role="tab" data-toggle="tab">
		                     <?php echo _l('pur_discuss'); ?>
		                      <span class="badge comments-indicator<?php echo $totalComments == 0 ? ' hide' : ''; ?>"><?php echo $totalComments; ?></span>
		                     </a>
		                  </li>

		                  <li role="presentation">
		                     <a href="#attachment" aria-controls="attachment" role="tab" data-toggle="tab">
		                     <?php echo _l('attachment'); ?>
		                     </a>
		                  </li>

		                </ul>
		            </div>
		        </div>
		        <div class="tab-content">
             		<div role="tabpanel" class="tab-pane ptop10 <?php if($this->input->get('tab') != 'discussion'){echo 'active';} ?>" id="general_infor">
						<div class="col-md-7 pad_left_0">
	         				<div class="col-md-6 pad_left_0 border-right">
	         					<p><?php echo _l('invoice_code').':'; ?><span class="pull-right bold"><?php echo pur_html_entity_decode($pur_invoice->invoice_number); ?></span></p>
	         				</div>
	         				<div class="col-md-6 pad_right_0">
	         					<p><?php echo _l('invoice_number').':'; ?><span class="pull-right bold">
	         						<?php $vendor_invoice_number = $pur_invoice->vendor_invoice_number != '' ?  $pur_invoice->vendor_invoice_number : $pur_invoice->invoice_number;
	         							echo pur_html_entity_decode($vendor_invoice_number); 
	         					?></span></p>
	         				</div>

	         				<div class="col-md-12 pad_left_0 pad_right_0">
	         					<hr class="mtop5 mbot5">
	         				</div>

	         				<div class="col-md-6 pad_left_0 border-right">
	         					<p><?php echo _l('invoice_date').':'; ?><span class="pull-right bold"><?php echo _d($pur_invoice->invoice_date); ?></span></p>
	         				</div>

	         				<div class="col-md-6 pad_right_0">
	         					<p><?php echo _l('pur_vendor').':'; ?>
		         					<span class="pull-right bold">
		         						<a href="<?php echo admin_url('purchase/vendor/'.$pur_invoice->vendor); ?>" >
		         							<?php 
		         							echo wh_get_vendor_company_name($pur_invoice->vendor); 
		         							?>
		         						</a>
		         					</span>
	         					</p>
	         				</div>

	         				
	         				<div class="col-md-12 pad_left_0 pad_right_0">
	         					<hr class="mtop5 mbot5">
	         				</div>

	         				<div class="col-md-6  pad_left_0  border-right  ">
	         					<p><?php echo _l('pur_due_date').':'; ?><span class="pull-right bold"><?php echo _d($pur_invoice->duedate); ?></span></p>
	         				</div>
	         				<div class="col-md-6 pad_right_0">
	         					<p><?php echo _l('purchase_order').':'; ?><span class="pull-right bold"><a href="<?php echo admin_url('purchase/purchase_order/'.$pur_invoice->pur_order); ?>" ><?php echo get_pur_order_subject($pur_invoice->pur_order); ?></a></span></p>
	         					
	         				</div>
	         				<div class="col-md-12 pad_left_0 pad_right_0">
	         					<hr class="mtop5 mbot5">
	         				</div>
	         				<!-- <div class="col-md-6 pad_left_0  border-right">
	         					<p><?php echo _l('invoice_amount').':'; ?><span class="pull-right bold"><?php echo app_format_money($pur_invoice->total,$base_currency->symbol); ?></span></p>
	         				</div> -->
							 <div class="col-md-6 pad_left_0 border-right">
	         					<p><?php echo _l('amount_without_tax').':'; ?><span class="pull-right bold"><?php echo app_format_money($pur_invoice->vendor_submitted_amount_without_tax,$base_currency->symbol); ?></span></p>
	         				</div>
							<div class="col-md-6 pad_right_0 ">
	         					<p><?php echo _l('vendor_submitted_tax_amount').':'; ?><span class="pull-right bold"><?php echo app_format_money($pur_invoice->vendor_submitted_tax_amount,$base_currency->symbol); ?></span></p>
	         				</div>
							 <div class="col-md-12 pad_left_0 pad_right_0">
	         					<hr class="mtop5 mbot5">
	         				</div>
							<div class="col-md-6 pad_left_0 border-right">
	         					<p><?php echo _l('vendor_submitted_amount').':'; ?><span class="pull-right bold"><?php echo app_format_money($pur_invoice->vendor_submitted_amount,$base_currency->symbol); ?></span></p>
	         				</div>
							<div class="col-md-6 pad_right_0 ">
	         					<p><?php echo _l('final_certified_amount').':'; ?><span class="pull-right bold"><?php echo app_format_money($pur_invoice->final_certified_amount,$base_currency->symbol); ?></span></p>
	         				</div>
							 <div class="col-md-12 pad_left_0 pad_right_0">
	         					<hr class="mtop5 mbot5">
	         				</div>
							<div class="col-md-6 pad_left_0 border-right">
							 <p><?php echo _l('bill_accept_date').':'; ?><span class="pull-right bold"><?php echo _d($pur_invoice->bill_accept_date); ?></span></p>
	         				</div>
							<div class="col-md-6 pad_right_0 ">
							<p><?php echo _l('certified_bill_date').':'; ?><span class="pull-right bold"><?php echo _d($pur_invoice->certified_bill_date); ?></span></p>
	         				</div>
							 <div class="col-md-12 pad_left_0 pad_right_0">
	         					<hr class="mtop5 mbot5">
	         				</div>
							 <div class="col-md-6 pad_left_0 border-right">
							 <p><?php echo _l('payment_date_reliance').':'; ?><span class="pull-right bold"><?php echo _d($pur_invoice->payment_date); ?></span></p>
	         				</div>
	         				<div class="col-md-6 pad_right_0 ">
							<p><?php echo _l('group_pur').':'; ?><span class="pull-right bold"><?php echo get_group_name_by_id($pur_invoice->group_pur) ?></span></p>
							</div>
	         				<div class="col-md-12 pad_left_0 pad_right_0">
	         					<hr class="mtop5 mbot5">
	         				</div>
							 <div class="col-md-6 pad_left_0 border-right">
							 <p><?php echo _l('payment_date_basilius').':'; ?><span class="pull-right bold"><?php echo _d($pur_invoice->payment_date_basilius); ?></span></p>
	         				</div>
							</div>

	         			<div class="col-md-5">

	         				<div class="col-md-12 pad_left_0 pad_right_0 ">
	         					<div id="inputTagsWrapper">
			                       <label for="tags" class="control-label"><i class="fa fa-tag" aria-hidden="true"></i> <?php echo _l('tags'); ?></label>
			                       <input type="text" class="tagsinput" id="tags" name="tags" value="<?php echo (isset($pur_invoice) ? prep_tags_input(get_tags_in($pur_invoice->id,'pur_invoice')) : ''); ?>" data-role="tagsinput">
			                    </div>
	         				</div>
	         				<div class="col-md-12 pad_left_0 pad_right_0">
	         					<hr class="mtop5 mbot5">
	         				</div>

	         				<div class="col-md-6 pad_left_0 border-right">
	         					<p><?php echo _l('transaction_id').':'; ?><span class="pull-right bold"><?php echo pur_html_entity_decode($pur_invoice->transactionid); ?></span></p>
	         				</div>
	         				<div class="col-md-6 pad_right_0">
	         					<p><?php echo _l('transaction_date').':'; ?><span class="pull-right bold"><?php echo _d($pur_invoice->transaction_date); ?></span></p>
	         				</div>
	         				<div class="col-md-12 pad_left_0 pad_right_0">
	         					<hr class="mtop5 mbot5">
	         				</div>
	         				<div class="col-md-6 pad_left_0 border-right">
	         					<p><?php echo _l('add_from').':'; ?><span class="pull-right bold"><a href="<?php echo admin_url('staff/profile/'.$pur_invoice->add_from); ?>" ><?php echo get_staff_full_name($pur_invoice->add_from); ?></a></span></p>
	         				</div>
	         				<div class="col-md-6 pad_right_0">
	         					<p><?php echo _l('date_add').':'; ?><span class="pull-right bold"><?php echo _d($pur_invoice->date_add); ?></span></p>
	         				</div>
	         				<div class="col-md-12 pad_left_0 pad_right_0">
	         					<hr class="mtop5 mbot5">
	         				</div>

	         			</div>

						<?php /*
						<div class="col-md-12 pad_left_0 pad_right_0">
		         			<div class="table-responsive">
	                           <table class="table items items-preview estimate-items-preview" data-type="estimate">
	                              <thead>
	                                 <tr>
	          
	                                    <th class="description" width="30%" align="left"><?php echo _l('items'); ?></th>
	                                    <th align="right"><?php echo _l('purchase_quantity'); ?></th>
	                                    <th align="right"><?php echo _l('purchase_unit_price'); ?></th>
	                                    <th align="right"><?php echo _l('into_money'); ?></th>
	                                    <?php if(get_option('show_purchase_tax_column') == 1){ ?>
	                                    <th align="right"><?php echo _l('tax'); ?></th>
	                                    <?php } ?>
	                                    <th align="right"><?php echo _l('sub_total'); ?></th>
	                                    <th align="right"><?php echo _l('discount(%)'); ?></th>
	                                    <th align="right"><?php echo _l('discount(money)'); ?></th>
	                                    <th align="right"><?php echo _l('total'); ?></th>
	                                 </tr>
	                              </thead>
	                              <tbody class="ui-sortable">

	                                 <?php  $item_discount = 0;
	                                 if(count($invoice_detail) > 0){
	                                    $count = 1;
	                                    $t_mn = 0;
	                                   
	                                 foreach($invoice_detail as $es) { ?>
	                                 <tr nobr="true" class="sortable">

	                                    <td class="description" align="left;"><span><strong><?php 
	                                    $item = get_item_hp($es['item_code']); 
	                                    if(isset($item) && isset($item->commodity_code) && isset($item->description)){
	                                       echo pur_html_entity_decode($item->commodity_code.' - '.$item->description);
	                                    }else{
	                                       echo pur_html_entity_decode($es['item_name']);
	                                    }
	                                    ?></strong><?php if($es['description'] != ''){ ?><br><span><?php echo pur_html_entity_decode($es['description']); ?></span><?php } ?></td>
	                                    <td align="right"  width="12%"><?php echo pur_html_entity_decode($es['quantity']); ?></td>
	                                    <td align="right"><?php echo app_format_money($es['unit_price'],$base_currency->symbol); ?></td>
	                                    <td align="right"><?php echo app_format_money($es['into_money'],$base_currency->symbol); ?></td>
	                                    <?php if(get_option('show_purchase_tax_column') == 1){ ?>
	                                    <td align="right"><?php echo app_format_money(($es['total'] - $es['into_money']),$base_currency->symbol); ?></td>
	                                    <?php } ?>
	                                    <td class="amount" align="right"><?php echo app_format_money($es['total'],$base_currency->symbol); ?></td>
	                                    <td class="amount" width="12%" align="right"><?php echo ($es['discount_percent'].'%'); ?></td>
	                                    <td class="amount" align="right"><?php echo app_format_money($es['discount_money'],$base_currency->symbol); ?></td>
	                                    <td class="amount" align="right"><?php echo app_format_money($es['total_money'],$base_currency->symbol); ?></td>
	                                 </tr>
	                              <?php 
	                              $t_mn += $es['total_money'];
	                              $item_discount += $es['discount_money'];
	                              $count++; } } ?>
	                              </tbody>
	                           </table>
	                        </div>
	                    </div>

                        <div class="col-md-5 col-md-offset-7 pad_left_0 pad_right_0">
	                        <table class="table text-right">
	                           <tbody>
	                              <tr id="inv_subtotal">
	                                 <td><span class="bold"><?php echo _l('subtotal'); ?></span>
	                                 </td>
	                                 <td class="inv_subtotal">
	                                    <?php echo app_format_money($pur_invoice->subtotal,$base_currency->symbol); ?>
	                                 </td>
	                              </tr>

	                              <?php if($tax_data['preview_html'] != ''){
	                                echo pur_html_entity_decode($tax_data['preview_html']);
	                              } ?>


	                              <?php if(($pur_invoice->discount_total + $item_discount) > 0){ ?>
	                              
	                              <tr id="inv_discount_total">
	                                 <td><span class="bold"><?php echo _l('discount_total(money)'); ?></span>
	                                 </td>
	                                 <td class="inv_discount_total">
	                                    <?php echo '-'.app_format_money(($pur_invoice->discount_total + $item_discount), $base_currency->symbol); ?>
	                                 </td>
	                              </tr>
	                              <?php } ?>

	                              <?php if($pur_invoice->shipping_fee  > 0){ ?>
	                              
	                              <tr id="inv_discount_total">
	                                 <td><span class="bold"><?php echo _l('pur_shipping_fee'); ?></span>
	                                 </td>
	                                 <td class="inv_discount_total">
	                                    <?php echo app_format_money($pur_invoice->shipping_fee, $base_currency->symbol); ?>
	                                 </td>
	                              </tr>
	                              <?php } ?>


	                              <tr id="inv_total">
	                                 <td><span class="bold"><?php echo _l('total'); ?></span>
	                                 </td>
	                                 <td class="inv_total bold">
	                                    <?php echo app_format_money($pur_invoice->total, $base_currency->symbol); ?>
	                                 </td>
	                              </tr>
	                           </tbody>
	                        </table>
	                    </div>
	                    */ ?>


						<div class="col-md-12 pad_left_0 pad_right_0 align_div">
							<p><span class="bold"><?php echo _l('pur_note').': '; ?></span><span><?php echo pur_html_entity_decode($pur_invoice->vendor_note); ?></span></p>
						</div>
						<div class="col-md-12 pad_left_0 pad_right_0">
							<hr class="mtop5 mbot5">
						</div>
						<div class="col-md-12 pad_left_0 pad_right_0 align_div">
							<p><span class="bold"><?php echo _l('terms').': '; ?></span><span><?php echo pur_html_entity_decode($pur_invoice->terms); ?></span></p>
						</div>
						<div class="col-md-12 pad_left_0 pad_right_0">
							<hr class="mtop5 mbot5">
						</div>
						<div class="col-md-12 pad_left_0 pad_right_0 align_div">
							<p><span class="bold"><?php echo _l('client_note').': '; ?></span><span><?php echo pur_html_entity_decode($pur_invoice->adminnote); ?></span></p>
						</div>
					</div>

					<div role="tabpanel" class="tab-pane" id="payment_record">
		               <div class="col-md-6 pad_left_0" >
		               <h4 class="font-medium mbot15 bold text-success"><?php echo _l('payment_for_invoice').' '.$pur_invoice->invoice_number; ?></h4>
		               </div>
		               
		               <div class="clearfix"></div>
		               <table class="table dt-table">
		                   <thead>
		                     <th><?php echo _l('payments_table_amount_heading'); ?></th>
		                      <th><?php echo _l('payments_table_mode_heading'); ?></th>
		                      <th><?php echo _l('payment_transaction_id'); ?></th>
		                      <th><?php echo _l('payments_table_date_heading'); ?></th>
		                   </thead>
		                  <tbody>
		                     <?php foreach($payment as $pay) { ?>
		                        <tr>
		                           <td><?php echo app_format_money($pay['amount'],$base_currency->symbol); ?></td>
		                           <td><?php echo get_payment_mode_by_id($pay['paymentmode']); ?></td>
		                           <td><?php echo pur_html_entity_decode($pay['transactionid']); ?></td>
		                           <td><?php echo _d($pay['date']); ?></td>
		                          
		                        </tr>
		                     <?php } ?>
		                  </tbody>
		               </table>
		            </div>

					<div role="tabpanel" class="tab-pane <?php if($this->input->get('tab') === 'discussion'){echo ' active';} ?>" id="discuss">
		              <?php echo form_open($this->uri->uri_string()) ;?>
		               <div class="contract-comment">
		                  <textarea name="content" rows="4" class="form-control"></textarea>
		                  <button type="submit" class="btn btn-info mtop10 pull-right" data-loading-text="<?php echo _l('wait_text'); ?>"><?php echo _l('proposal_add_comment'); ?></button>
		                  <?php echo form_hidden('action','inv_comment'); ?>
		               </div>
		               <?php echo form_close(); ?>
		               <div class="clearfix"></div>
		               <?php
		                  $comment_html = '';
		                  foreach ($comments as $comment) {
		                   $comment_html .= '<div class="contract_comment mtop10 mbot20" data-commentid="' . $comment['id'] . '">';
		                   if($comment['staffid'] != 0){
		                    $comment_html .= staff_profile_image($comment['staffid'], array(
		                     'staff-profile-image-small',
		                     'media-object img-circle pull-left mright10'
		                  ));
		                  }
		                  $comment_html .= '<div class="media-body valign-middle">';
		                  $comment_html .= '<div class="mtop5">';
		                  $comment_html .= '<b>';
		                  if($comment['staffid'] != 0){
		                    $comment_html .= get_staff_full_name($comment['staffid']);
		                  } else {
		                    $comment_html .= get_vendor_company_name(get_vendor_user_id());
		                  }
		                  $comment_html .= '</b>';
		                  $comment_html .= ' - <small class="mtop10 text-muted">' . time_ago($comment['dateadded']) . '</small>';
		                  $comment_html .= '</div>';
		              
		                  $comment_html .= check_for_links($comment['content']) . '<br />';
		                  $comment_html .= '</div>';
		                  $comment_html .= '</div>';
		                  $comment_html .= '<hr />';
		                  }
		                  echo $comment_html; ?>
		            </div>

		            <div role="tabpanel" class="tab-pane" id="attachment">
		            	<div class="col-md-12" id="purinv_pv_file">
                            <?php
                            $file_html = '';
                            if(count($pur_invoice_attachments) > 0){
                                $file_html .= '<hr />
                                        <p class="bold text-muted">'._l('customer_attachments').'</p>';
                                foreach ($pur_invoice_attachments as $f) {
                                    $href_url = site_url(PURCHASE_PATH.'pur_invoice/'.$f['rel_id'].'/'.$f['file_name']).'" download';
                                                    if(!empty($f['external'])){
                                                      $href_url = $f['external_link'];
                                                    }
                                   $file_html .= '<div class="mbot15 row inline-block full-width" data-attachment-id="'. $f['id'].'">
                                  <div class="col-md-8">
                                     <a name="preview-purinv-btn" onclick="preview_purinv_btn(this); return false;" rel_id = "'. $f['rel_id']. '" id = "'.$f['id'].'" href="Javascript:void(0);" class="mbot10 mright5 btn btn-success pull-left" data-toggle="tooltip" title data-original-title="'. _l('preview_file').'"><i class="fa fa-eye"></i></a>
                                     <div class="pull-left"><i class="'. get_mime_class($f['filetype']).'"></i></div>
                                     <a href=" '. $href_url.'" target="_blank" download>'.$f['file_name'].'</a>
                                     <br />
                                     <small class="text-muted">'.$f['filetype'].'</small>
                                  </div>
                                  <div class="col-md-4 text-right">';
                                    if($f['staffid'] == get_staff_user_id() || is_admin()){
                                    $file_html .= '<a href="#" class="text-danger" onclick="delete_purinv_attachment('. $f['id'].'); return false;"><i class="fa fa-times"></i></a>';
                                    } 
                                   $file_html .= '</div></div>';
                                }
                                $file_html .= '<hr />';
                                echo pur_html_entity_decode($file_html);
                            }
                            ?>
                        </div>
		                <div id="purinv_file_data"></div>
		            </div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php hooks()->do_action('app_admin_footer'); ?>