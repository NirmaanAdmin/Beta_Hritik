<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
   .form-inline textarea.form-control {

      width: auto !important;
   }
</style>

<div id="wrapper">
   <div class="content">
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
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12" id="small-table">
               <div class="panel_s">
                  <div class="panel-body">
                     <?php
                     // Updated table headers to include "Completion Date"
                     $table_data = array(
                        _l('order_scope'),               // Corresponds to `order_name`
                        _l('rli_filter'),
                        _l('contractor'),                // Corresponds to `vendor`
                        _l('order_date'),                // Corresponds to `order_date`
                        _l('completion_date'),           // New field for Completion Date
                        _l('budget_ro_projection'),             // Corresponds to `total`
                        _l('committed_contract_amount'), // Corresponds to `total`
                        _l('change_order_amount'),               // Corresponds to `total`
                        _l('total_rev_contract_value'),
                        _l('anticipate_variation'),
                        _l('cost_to_complete'),
                        _l('final_certified_amount'),
                        _l('category'),                  // Corresponds to `kind`
                        _l('group_pur'),
                        _l('remarks')                // Corresponds to `group_pur`
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

<?php init_tail(); ?>
<script>
   $(function() {
      "use strict";

      // Initialize the DataTable
      var table_order_tracker = $('.table-table_order_tracker').DataTable();

      // Inline editing for "Completion Date"
      $('body').on('change', '.completion-date-input', function(e) {
         e.preventDefault();

         var rowId = $(this).data('id');
         var tableType = $(this).data('type'); // wo_order or pur_order
         var completionDate = $(this).val();

         // Perform AJAX request to update the completion date
         $.post(admin_url + 'purchase/update_completion_date', {
            id: rowId,
            table: tableType,
            completion_date: completionDate
         }).done(function(response) {
            response = JSON.parse(response);
            if (response.success) {
               alert_float('success', response.message);
               table_order_tracker.ajax.reload(null, false); // Reload table without refreshing the page
            } else {
               alert_float('danger', response.message);
            }
         });
      });
      // Inline editing for "budget"
      $('body').on('change', '.budget-input', function(e) {
         e.preventDefault();

         var rowId = $(this).data('id');
         var tableType = $(this).data('type'); // wo_order or pur_order
         var budget = $(this).val();

         // Perform AJAX request to update the budget
         $.post(admin_url + 'purchase/update_budget', {
            id: rowId,
            table: tableType,
            budget: budget
         }).done(function(response) {
            response = JSON.parse(response);
            if (response.success) {
               alert_float('success', response.message);
               table_order_tracker.ajax.reload(null, false); // Reload table without refreshing the page
            } else {
               alert_float('danger', response.message);
            }
         });
      });

      // Inline editing for "Amount" (toggle span to input)
      $('body').on('click', '.budget-display', function(e) {
         e.preventDefault();

         var rowId = $(this).data('id');
         var tableType = $(this).data('type');
         var currentAmount = $(this).text().replace(/[^\d.-]/g, ''); // Remove currency formatting

         // Replace the span with an input field
         $(this).replaceWith('<input type="number" class="form-control budget-input" value="' + currentAmount + '" data-id="' + rowId + '" data-type="' + tableType + '">');
      });
      // Inline editing for "Anticipate Variation" (toggle span to input)
      $('body').on('click', '.anticipate-variation-display', function(e) {
         e.preventDefault();

         var rowId = $(this).data('id');
         var tableType = $(this).data('type');
         var currentValue = $(this).text().replace(/[^\d.-]/g, ''); // Remove currency formatting

         // Replace the span with an input field
         $(this).replaceWith('<input type="number" class="form-control anticipate-variation-input" value="' + currentValue + '" data-id="' + rowId + '" data-type="' + tableType + '">');
      });

      // Save updated "Anticipate Variation" to the database
      $('body').on('change', '.anticipate-variation-input', function(e) {
         e.preventDefault();

         var rowId = $(this).data('id');
         var tableType = $(this).data('type');
         var anticipateVariation = $(this).val();

         // Perform AJAX request to update the anticipate_variation
         $.post(admin_url + 'purchase/update_anticipate_variation', {
            id: rowId,
            table: tableType,
            anticipate_variation: anticipateVariation
         }).done(function(response) {
            response = JSON.parse(response);
            if (response.success) {
               alert_float('success', response.message);

               // Replace input back with formatted value
               var formattedValue = new Intl.NumberFormat('en-IN', {
                  style: 'currency',
                  currency: 'INR'
               }).format(anticipateVariation);

               $('.anticipate-variation-input[data-id="' + rowId + '"]').replaceWith('<span class="anticipate-variation-display" data-id="' + rowId + '" data-type="' + tableType + '">' + formattedValue + '</span>');

               // Optionally reload the table if necessary
               table_order_tracker.ajax.reload(null, false);
            } else {
               alert_float('danger', response.message);
            }
         });
      });
      // Inline editing for "Remarks" (toggle span to textarea)
      $('body').on('click', '.remarks-display', function(e) {
         e.preventDefault();

         var rowId = $(this).data('id');
         var tableType = $(this).data('type');
         var currentRemarks = $(this).text();

         // Replace the span with a textarea for editing
         $(this).replaceWith('<textarea class="form-control remarks-input" data-id="' + rowId + '" data-type="' + tableType + '">' + currentRemarks + '</textarea>');
      });

      // Save updated "Remarks" to the database
      $('body').on('change', '.remarks-input', function(e) {
         e.preventDefault();

         var rowId = $(this).data('id');
         var tableType = $(this).data('type');
         var remarks = $(this).val();

         // Perform AJAX request to update the remarks
         $.post(admin_url + 'purchase/update_remarks', {
            id: rowId,
            table: tableType,
            remarks: remarks
         }).done(function(response) {
            response = JSON.parse(response);
            if (response.success) {
               alert_float('success', response.message);

               // Replace textarea back with formatted remarks
               $('.remarks-input[data-id="' + rowId + '"]').replaceWith('<span class="remarks-display" data-id="' + rowId + '" data-type="' + tableType + '">' + remarks + '</span>');

               // Optionally reload the table if necessary
               table_order_tracker.ajax.reload(null, false);
            } else {
               alert_float('danger', response.message);
            }
         });
      });
   });
</script>
</body>

</html>