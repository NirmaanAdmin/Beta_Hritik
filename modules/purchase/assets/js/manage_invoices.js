Dropzone.autoDiscover = false;
var expenseDropzone;
(function($) {
	"use strict"; 
	var table_invoice = $('.table-table_pur_invoices');
	var Params = {
		"from_date": 'input[name="from_date"]',
        "to_date": 'input[name="to_date"]',
        "contract": "[name='contract[]']",
        "pur_orders": "[name='pur_orders[]']",
        "wo_orders": "[name='wo_orders[]']",
        "vendors": "[name='vendor_ft[]']",
        "billing_invoices": "[name='billing_invoices']"
    };

	initDataTable(table_invoice, admin_url+'purchase/table_pur_invoices',[], [], Params, [5, 'desc']);
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

    $(document).on('change', 'select[name="vendor_ft[]"]', function() {
      $('select[name="vendor_ft[]"]').selectpicker('refresh');
    });

    $(document).on('change', 'select[name="billing_invoices"]', function() {
      $('select[name="billing_invoices"]').selectpicker('refresh');
    });

    $(document).on('click', '.reset_vbt_all_filters', function() {
      var filterArea = $('.vbt_all_filters');
      filterArea.find('input').val("");
      filterArea.find('select').selectpicker("val", "");
      table_invoice.DataTable().ajax.reload().columns.adjust().responsive.recalc();
    });

    if ($('#pur_invoice-expense-form').length > 0) {
        expenseDropzone = new Dropzone("#pur_invoice-expense-form", appCreateDropzoneOptions({
            autoProcessQueue: false,
            clickable: '#dropzoneDragArea',
            previewsContainer: '.dropzone-previews',
            addRemoveLinks: true,
            maxFiles: 1,
            success: function(file, response) {
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    window.location.reload();
                }
            }
      }));
    }

    $('.table-table_pur_invoices').on('draw.dt', function() {
       var reportsTable = $(this).DataTable();
       var sums = reportsTable.ajax.json().sums;
       $(this).find('tfoot').addClass('bold');
       $(this).find('tfoot td').eq(0).html("Total (Per Page)");
       $(this).find('tfoot td.total_invoice_amount').html(sums.total_invoice_amount);
       $(this).find('tfoot td.total_vendor_submitted_amount_without_tax').html(sums.total_vendor_submitted_amount_without_tax);
       $(this).find('tfoot td.total_vendor_submitted_tax_amount').html(sums.total_vendor_submitted_tax_amount);
       $(this).find('tfoot td.total_vendor_submitted_amount').html(sums.total_vendor_submitted_amount);
       $(this).find('tfoot td.total_final_certified_amount').html(sums.total_final_certified_amount);
     });

     var table_pur_invoices = $('.table-table_pur_invoices').DataTable();

     $('body').on('change', '.vin-input', function(e) {
       e.preventDefault();

       var rowId = $(this).data('id');
       var vin = $(this).val();

       // Perform AJAX request to update the vin
       $.post(admin_url + 'purchase/update_vendor_invoice_number', {
          id: rowId,
          vin: vin
       }).done(function(response) {
          response = JSON.parse(response);
          if (response.success) {
             alert_float('success', response.message);
             table_pur_invoices.ajax.reload(null, false); // Reload table without refreshing the page
          } else {
             alert_float('danger', response.message);
          }
       });
     });

     $('body').on('change', '.invoice-date-input', function(e) {
       e.preventDefault();

       var rowId = $(this).data('id');
       var invoiceDate = $(this).val();

       // Perform AJAX request to update the invoice date
       $.post(admin_url + 'purchase/update_invoice_date', {
          id: rowId,
          invoice_date: invoiceDate
       }).done(function(response) {
          response = JSON.parse(response);
          if (response.success) {
             alert_float('success', response.message);
             table_pur_invoices.ajax.reload(null, false); // Reload table without refreshing the page
          } else {
             alert_float('danger', response.message);
          }
       });
      });

    appValidateForm($('#pur_invoice-expense-form'), {
          expense_name: 'required',
          category: 'required',
          date: 'required',
          amount: 'required'
    }, projectExpenseSubmitHandler);
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

function change_budget_head(budgetid, id){
    "use strict";
    if(id > 0) {
      $.post(admin_url + 'purchase/change_budget_head/'+budgetid+'/'+id).done(function(response){
        response = JSON.parse(response);
        if(response.success == true) {
          $('#budget_span_'+id).removeClass('label-info');
          $('#budget_span_'+id).addClass(response.class);
          $('#budget_span_'+id).html(response.status_str+' '+response.html);
          alert_float('success', response.mess);
        }else{
          alert_float('warning', response.mess);
        }
      });
    }
}

function convert_expense(pur_invoice,total){
    "use strict";
    var module_type = 1;

    $.post(admin_url + 'purchase/get_project_info/'+pur_invoice+'/'+module_type).done(function(response){
      response = JSON.parse(response);
      $('select[name="project_id"]').val(response.project_id).change();
      $('select[name="clientid"]').val(response.customer).change();
      $('select[name="currency"]').val(response.currency).change();
      $('input[name="vendor"]').val(response.vendor);
      $('select[name="category"]').val(response.category).change();
      if(response.budget_head) {
        $('#category').val(response.budget_head).change();
      } else {
        $('#category').val('').change();
      }
      $('input[id="expense_name"]').val(response.description_services);
    });

    $('#pur_invoice_expense').modal('show');
    $('input[id="amount"]').val(total);
    $('#pur_invoice_additional').html('');
    $('#pur_invoice_additional').append(hidden_input('pur_invoice',pur_invoice));
}

function projectExpenseSubmitHandler(form) {
    "use strict";
      $.post(form.action, $(form).serialize()).done(function(response) {
          response = JSON.parse(response);
          if (response.expenseid) {
              if (typeof(expenseDropzone) !== 'undefined') {
                  if (expenseDropzone.getQueuedFiles().length > 0) {
                      expenseDropzone.options.url = admin_url + 'expenses/add_expense_attachment/' + response.expenseid;
                      expenseDropzone.processQueue();
                  } else {
                      window.location.assign(response.url);
                  }
              } else {
                  window.location.assign(response.url);
              }
          } else {
              window.location.assign(response.url);
          }
      });
      return false;
}