(function($) {
	"use strict"; 
	var table_invoice = $('.table-table_pur_invoices');
	var Params = {
		"from_date": 'input[name="from_date"]',
        "to_date": 'input[name="to_date"]',
        "contract": "[name='contract[]']",
        "pur_orders": "[name='pur_orders[]']",
        "vendors": "[name='vendor_ft[]']"
    };

	initDataTable(table_invoice, admin_url+'purchase/table_pur_invoices',[], [], Params);
	$.each(Params, function(i, obj) {
        $('select' + obj).on('change', function() {  
            table_invoice.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });
    });

    $('input[name="from_date"]').on('change', function() {
        table_invoice.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
    });
    $('input[name="to_date"]').on('change', function() {
        table_invoice.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
    });
})(jQuery);
/**
 * Changes the payment status of a purchase order.
 *
 * This function sends an asynchronous POST request to update the payment status
 * of a purchase order identified by `id` to the specified `status`. It updates
 * the DOM element displaying the current payment status with the new status
 * and class if the request is successful. An alert is shown to indicate the
 * success or failure of the operation.
 *
 * @param {string} status - The new payment status to be set (e.g., 'paid', 'unpaid').
 * @param {number} id - The unique identifier of the purchase order.
 */

function change_payment_status(status, id){ 
    "use strict";
    if(id > 0){
      $.post(admin_url + 'purchase/change_payment_status/'+status+'/'+id).done(function(response){
        response = JSON.parse(response);
        if(response.success == true){
          if($('#status_span_'+id).hasClass('label-danger')){
            $('#status_span_'+id).removeClass('label-danger');
            $('#status_span_'+id).addClass(response.class);
            $('#status_span_'+id).html(response.status_str+' '+response.html);
          }else if($('#status_span_'+id).hasClass('label-success')){
            $('#status_span_'+id).removeClass('label-success');
            $('#status_span_'+id).addClass(response.class);
            $('#status_span_'+id).html(response.status_str+' '+response.html);
          }else if($('#status_span_'+id).hasClass('label-info')){
            $('#status_span_'+id).removeClass('label-info');
            $('#status_span_'+id).addClass(response.class);
            $('#status_span_'+id).html(response.status_str+' '+response.html);
          }else if($('#status_span_'+id).hasClass('label-warning')){
            $('#status_span_'+id).removeClass('label-warning');
            $('#status_span_'+id).addClass(response.class);
            $('#status_span_'+id).html(response.status_str+' '+response.html);
          }else if($('#status_span_'+id).hasClass('label-primary')){
            $('#status_span_'+id).removeClass('label-primary');
            $('#status_span_'+id).addClass(response.class);
            $('#status_span_'+id).html(response.status_str+' '+response.html);
          }else if($('#status_span_'+id).hasClass('label-muted')){
            $('#status_span_'+id).removeClass('label-muted');
            $('#status_span_'+id).addClass(response.class);
            $('#status_span_'+id).html(response.status_str+' '+response.html);
          }
          alert_float('success', response.mess);
        }else{
          alert_float('warning', response.mess);
        }
      });
    }
  }