<style type="text/css">
	.dash-main-title {
		font-weight: bold;
		font-size: 17px;
	}
	.dash-sub-title {
		font-weight: bold;
	}
</style>
<div class="panel_s">
    <div class="panel-body">
    	<div class="col-md-12">
			<div class="row">
				<div class="col-md-2">
		        	<?php echo render_select('vendors', $vendors, array('userid', 'company'), 'vendor'); ?>
		      	</div>
		      	<div class="col-md-2">
		        	<?php echo render_select('group_pur', $commodity_groups_pur, array('id', 'name'), 'group_pur'); ?>
		      	</div>
		      	<div class="col-md-2 form-group">
			        <label for="kind"><?php echo _l('cat'); ?></label>
				    <select name="kind" id="kind" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
				        <option value=""></option>
				        <option value="Client Supply"><?php echo _l('client_supply'); ?></option>
				        <option value="Bought out items"><?php echo _l('bought_out_items'); ?></option>
			        </select>
			    </div>
				<div class="col-md-2">
		        	<?php echo render_date_input('from_date','from_date', ''); ?>
		      	</div>
		      	<div class="col-md-2">
		        	<?php echo render_date_input('to_date','to_date', ''); ?>
		      	</div>
		      	<div class="col-md-1" style="margin-top: 21px;">
		        	<a href="#" onclick="get_purchase_order_dashboard(); return false;" class="btn btn-info"><?php echo _l('_filter'); ?></a>
		      	</div>
			</div>
		</div>
	</div>
</div>

<div class="panel_s">
	<div class="panel-body">
	  <p class="dash-main-title">1. <?php echo _l('work_order_summary_report'); ?>:</p>

	  <div class="col-md-12 mtop20">
		<div class="row">
			<div class="quick-stats-invoices col-xs-12 col-md-6 col-sm-6 col-lg-3 tw-mb-2 sm:tw-mb-0">    <div class="top_stats_wrapper">                                  
					<div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
						<div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate">                          
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-6 tw-h-6 tw-mr-3 rtl:tw-ml-3 tw-text-neutral-600">                              
								<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z">
								</path>                          
							</svg>                          
							<span class="tw-truncate">Total WO Value</span>                 
						</div>                      
						<span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0 total_po_value"></span>  
					</div>                    
					<div class="progress tw-mb-0 tw-mt-4 progress-bar-mini">                      
						<div class="progress-bar progress-bar-info no-percent-text not-dynamic" role="progressbar" aria-valuenow="100.00" aria-valuemin="0" aria-valuemax="100" style="width: 100%;" data-percent="100.00">                      
						</div>                  
					</div>              
				</div>          
			</div>

			<div class="quick-stats-invoices col-xs-12 col-md-6 col-sm-6 col-lg-3 tw-mb-2 sm:tw-mb-0">    <div class="top_stats_wrapper">                                  
					<div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
						<div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate">                          
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-6 tw-h-6 tw-mr-3 rtl:tw-ml-3 tw-text-neutral-600">                              
								<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z">
								</path>                          
							</svg>                          
							<span class="tw-truncate">Approved WO Value</span>                 
						</div>                      
						<span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0 approved_po_value"></span>  
					</div>                    
					<div class="progress tw-mb-0 tw-mt-4 progress-bar-mini">                      
						<div class="progress-bar progress-bar-success no-percent-text not-dynamic" role="progressbar" aria-valuenow="100.00" aria-valuemin="0" aria-valuemax="100" style="width: 100%;" data-percent="100.00">                      
						</div>                  
					</div>              
				</div>          
			</div>

			<div class="quick-stats-invoices col-xs-12 col-md-6 col-sm-6 col-lg-3 tw-mb-2 sm:tw-mb-0">    <div class="top_stats_wrapper">                                  
					<div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
						<div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate">                          
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-6 tw-h-6 tw-mr-3 rtl:tw-ml-3 tw-text-neutral-600">                              
								<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z">
								</path>                          
							</svg>                          
							<span class="tw-truncate">Draft WO Value</span>                 
						</div>                      
						<span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0 draft_po_value"></span>  
					</div>                    
					<div class="progress tw-mb-0 tw-mt-4 progress-bar-mini">                      
						<div class="progress-bar progress-bar-primary no-percent-text not-dynamic" role="progressbar" aria-valuenow="100.00" aria-valuemin="0" aria-valuemax="100" style="width: 100%;" data-percent="100.00">                      
						</div>                  
					</div>              
				</div>          
			</div>
		</div>
	  </div>

	  <div class="col-md-12 mtop20">
	  	<div class="row">
	  		<div class="col-md-12">
	  			<p class="mbot15 dash-sub-title">Pie Chart for WO Approval Status</p>
	  			<div style="width: 100%; height: 430px; display: flex; justify-content: center;">
	  				<canvas id="pieChartForPOApprovalStatus"></canvas>
	  			</div>
	  		</div>
	  	</div>
	  </div>

	  <div class="col-md-12 mtop20">
	  	<div class="row">
	  		<div class="col-md-12">
	  			<p class="mbot15 dash-sub-title">Line Chart showing WO trends over time</p>
	  			<div style="width: 100%; height: 400px;">
				  <canvas id="lineChartPOTrendsOverTime"></canvas>
				</div>
	  		</div>
	  	</div>
	  </div>

	</div>
</div>

<div class="panel_s">
	<div class="panel-body">
		<p class="dash-main-title">2. <?php echo _l('vendor_performance_analysis'); ?>:</p>

	  <div class="col-md-12 mtop20">
	  	<div class="row">
	  		<div class="col-md-12">
	  			<p class="mbot15 dash-sub-title">Bar Chart for Top 10 Vendors by WO Value</p>
	  			<div style="width: 100%; height: 400px;">
				  <canvas id="barChartTopVendors"></canvas>
				</div>
	  		</div>
	  	</div>
	  </div>

	  <div class="col-md-12 mtop20">
	  	<div class="row">
	  		<div class="col-md-12">
	  			<p class="mbot15 dash-sub-title">Pie Chart for WO Value Share by Vendor</p>
	  			<div style="width: 100%; height: 520px; display: flex; justify-content: center;">
				  <canvas id="pieChartTopVendors"></canvas>
				</div>
	  		</div>
	  	</div>
	  </div>

	</div>
</div>

<div class="panel_s">
	<div class="panel-body">
	  <p class="dash-main-title">3. <?php echo _l('work_order_tax_analysis'); ?>:</p>

	  <div class="col-md-12 mtop20">
	  	<div class="row">
	  		<div class="col-md-12">
	  			<p class="mbot15 dash-sub-title">Column Chart for WO Value vs. Tax Value</p>
				<div style="width: 100%; height: 400px;">
			      <canvas id="barChartPOVsTax"></canvas>
			    </div>
	  		</div>
	  	</div>
	  </div>

	  <div class="col-md-12 mtop20">
	  	<div class="row">
	  		<div class="col-md-12">
	  			<p class="mbot15 dash-sub-title">Pie Chart for Tax Contribution per Budget Head</p>
	  			<div style="width: 100%; height: 490px; display: flex; justify-content: center;">
	  				<canvas id="pieChartForTaxByBudget"></canvas>
	  			</div>
	  		</div>
	  	</div>
	  </div>

	</div>
</div>