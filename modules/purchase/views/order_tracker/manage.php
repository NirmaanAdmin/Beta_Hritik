<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="panel_s mbot10">
            <div class="panel-body">
               <div class="row">
                  <div class="_buttons col-md-3">
                     <strong><h3><?php echo _l('order_tracker'); ?></h3></strong>
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
                        _l('committed_contract_amount'), // Corresponds to `total`
                        _l('category'),                  // Corresponds to `kind`
                        _l('group_pur'),                 // Corresponds to `group_pur`
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
   });
</script>
</body>
</html>
