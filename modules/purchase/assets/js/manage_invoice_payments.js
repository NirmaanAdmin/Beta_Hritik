(function ($) {
  "use strict";
  var table_invoice = $('.table-table_pur_invoice_payments');
  var Params = {
    "from_date": 'input[name="from_date"]',
    "to_date": 'input[name="to_date"]',
    "vendors": "[name='vendor_ft[]']",
    "budget_head": "[name='budget_head']",
    "billing_invoices": "[name='billing_invoices']",
  };

  initDataTable(table_invoice, admin_url + 'purchase/table_pur_invoice_payments', [], [], Params, [5, 'desc']);
  $.each(Params, function (i, obj) {
    $('select' + obj).on('change', function () {
      table_invoice.DataTable().ajax.reload()
        .columns.adjust()
        .responsive.recalc();
    });
  });

  $('input[name="from_date"]').on('change', function () {
    table_invoice.DataTable().ajax.reload()
      .columns.adjust()
      .responsive.recalc();
  });
  $('input[name="to_date"]').on('change', function () {
    table_invoice.DataTable().ajax.reload()
      .columns.adjust()
      .responsive.recalc();
  });

  $(document).on('change', 'select[name="vendor_ft[]"]', function () {
    $('select[name="vendor_ft[]"]').selectpicker('refresh');
  });
  $(document).on('change', 'select[name="budget_head"]', function () {
    $('select[name="budget_head"]').selectpicker('refresh');
  });
  $(document).on('change', 'select[name="billing_invoices"]', function () {
    $('select[name="billing_invoices"]').selectpicker('refresh');
  });

  $(document).on('click', '.reset_vbt_all_filters', function () {
    var filterArea = $('.vbt_all_filters');
    filterArea.find('input').val("");
    filterArea.find('select').selectpicker("val", "");
    table_invoice.DataTable().ajax.reload().columns.adjust().responsive.recalc();
  });

  $('.table-table_pur_invoice_payments').on('draw.dt', function () {
    var reportsTable = $(this).DataTable();
    var sums = reportsTable.ajax.json().sums;
    $(this).find('tfoot').addClass('bold');
    $(this).find('tfoot td').eq(0).html("Total (Per Page)");
    $(this).find('tfoot td.total_vendor_submitted_amount_without_tax').html(sums.total_vendor_submitted_amount_without_tax);
    $(this).find('tfoot td.total_vendor_submitted_tax_amount').html(sums.total_vendor_submitted_tax_amount);
    $(this).find('tfoot td.total_final_certified_amount').html(sums.total_final_certified_amount);
    $(this).find('tfoot td.total_payment_made').html(sums.total_payment_made);
    $(this).find('tfoot td.total_bil_tds').html(sums.total_bil_tds);
    $(this).find('tfoot td.total_bil_total').html(sums.total_bil_total);
    $(this).find('tfoot td.total_ril_previous').html(sums.total_ril_previous);
    $(this).find('tfoot td.total_ril_this_bill').html(sums.total_ril_this_bill);
    $(this).find('tfoot td.total_ril_amount').html(sums.total_ril_amount);
  });

  var table_pur_invoice_payments = $('.table-table_pur_invoice_payments').DataTable();

  $('body').on('click', '.bil-tds-display', function(e) {
      e.preventDefault();
      var rowId = $(this).data('id');
      var currentAmount = $(this).text().replace(/[^\d.-]/g, '');
      $(this).replaceWith('<input type="number" class="form-control bil-tds-input" value="' + currentAmount + '" data-id="' + rowId + '" style="width: 138px">');
   });

   $('body').on('change', '.bil-tds-input', function(e) {
      e.preventDefault();
      var rowId = $(this).data('id');
      var amount = $(this).val();
      $.post(admin_url + 'purchase/update_bil_tds_amount', {
         id: rowId,
         amount: amount
      }).done(function(response) {
         response = JSON.parse(response);
         if (response.success) {
            alert_float('success', response.message);
            table_pur_invoice_payments.ajax.reload(null, false);
         } else {
            alert_float('danger', response.message);
         }
      });
   });

   $('body').on('click', '.ril-previous-display', function(e) {
      e.preventDefault();
      var rowId = $(this).data('id');
      var currentAmount = $(this).text().replace(/[^\d.-]/g, '');
      $(this).replaceWith('<input type="number" class="form-control ril-previous-input" value="' + currentAmount + '" data-id="' + rowId + '" style="width: 138px">');
   });

   $('body').on('change', '.ril-previous-input', function(e) {
      e.preventDefault();
      var rowId = $(this).data('id');
      var amount = $(this).val();
      $.post(admin_url + 'purchase/update_ril_previous_amount', {
         id: rowId,
         amount: amount
      }).done(function(response) {
         response = JSON.parse(response);
         if (response.success) {
            alert_float('success', response.message);
            table_pur_invoice_payments.ajax.reload(null, false);
         } else {
            alert_float('danger', response.message);
         }
      });
   });

   $('body').on('click', '.ril-this-bill-display', function(e) {
      e.preventDefault();
      var rowId = $(this).data('id');
      var currentAmount = $(this).text().replace(/[^\d.-]/g, '');
      $(this).replaceWith('<input type="number" class="form-control ril-this-bill-input" value="' + currentAmount + '" data-id="' + rowId + '" style="width: 138px">');
   });

   $('body').on('change', '.ril-this-bill-input', function(e) {
      e.preventDefault();
      var rowId = $(this).data('id');
      var amount = $(this).val();
      $.post(admin_url + 'purchase/update_ril_this_bill_amount', {
         id: rowId,
         amount: amount
      }).done(function(response) {
         response = JSON.parse(response);
         if (response.success) {
            alert_float('success', response.message);
            table_pur_invoice_payments.ajax.reload(null, false);
         } else {
            alert_float('danger', response.message);
         }
      });
   });

   $('body').on('change', '.ril-date-input', function (e) {
    e.preventDefault();
    var rowId = $(this).data('id');
    var rilDate = $(this).val();
    $.post(admin_url + 'purchase/update_ril_date', {
      id: rowId,
      ril_date: rilDate
    }).done(function (response) {
      response = JSON.parse(response);
      if (response.success) {
        alert_float('success', response.message);
        table_pur_invoice_payments.ajax.reload(null, false);
      } else {
        alert_float('danger', response.message);
      }
    });
  });

  $('body').on('click', '.add_new_payment_date', function(e) {
    e.preventDefault();
    var rowId = $(this).data('id');
    var newPaymentDateHTML = `
      <div class="input-group date all_payment_date" data-id="${rowId}">
        <input type="date" class="form-control payment-date-input" data-payment-id="0" data-id="${rowId}" style="width: 138px">
        <div class="input-group-addon">
            <i class="fa fa-plus add_new_payment_date" data-id="${rowId}" style="cursor: pointer;"></i>
        </div>
      </div>
    `;
    $(this).closest('.all_payment_date').after(newPaymentDateHTML);
  });

  $('body').on('change', '.payment-date-input', function (e) {
    e.preventDefault();
    var rowId = $(this).data('id');
    var paymentId = $(this).data('payment-id');
    var paymentDate = $(this).val();
    $.post(admin_url + 'purchase/update_bil_payment_date', {
      id: paymentId,
      vbt_id: rowId,
      payment_date: paymentDate,
    }).done(function (response) {
      response = JSON.parse(response);
      if (response.success) {
        alert_float('success', response.message);
        table_pur_invoice_payments.ajax.reload(null, false);
      } else {
        alert_float('danger', response.message);
      }
    });
  });

  $('body').on('click', '.add_new_payment_made', function(e) {
    e.preventDefault();
    var rowId = $(this).data('id');
    var newPaymentMadeHTML = `
      <div class="input-group all_payment_made" data-id="${rowId}">
        <input type="number" class="form-control payment-made-input" data-payment-id="0" data-id="${rowId}" style="width: 138px">
        <div class="input-group-addon">
            <i class="fa fa-plus add_new_payment_made" data-id="${rowId}" style="cursor: pointer;"></i>
        </div>
      </div>
    `;
    $(this).closest('.all_payment_made').after(newPaymentMadeHTML);
  });

  $('body').on('change', '.payment-made-input', function (e) {
    e.preventDefault();
    var rowId = $(this).data('id');
    var paymentId = $(this).data('payment-id');
    var paymentMade = $(this).val();
    $.post(admin_url + 'purchase/update_bil_payment_made', {
      id: paymentId,
      vbt_id: rowId,
      payment_made: paymentMade,
    }).done(function (response) {
      response = JSON.parse(response);
      if (response.success) {
        alert_float('success', response.message);
        table_pur_invoice_payments.ajax.reload(null, false);
      } else {
        alert_float('danger', response.message);
      }
    });
  });

  $('body').on('click', '.add_new_payment_tds', function(e) {
    e.preventDefault();
    var rowId = $(this).data('id');
    var newPaymentMadeHTML = `
      <div class="input-group all_payment_tds" data-id="${rowId}">
        <input type="number" class="form-control payment-tds-input" data-payment-id="0" data-id="${rowId}" style="width: 138px">
        <div class="input-group-addon">
            <i class="fa fa-plus add_new_payment_tds" data-id="${rowId}" style="cursor: pointer;"></i>
        </div>
      </div>
    `;
    $(this).closest('.all_payment_tds').after(newPaymentMadeHTML);
  });

  $('body').on('change', '.payment-tds-input', function (e) {
    e.preventDefault();
    var rowId = $(this).data('id');
    var paymentId = $(this).data('payment-id');
    var paymentMade = $(this).val();
    $.post(admin_url + 'purchase/update_bil_payment_tds', {
      id: paymentId,
      vbt_id: rowId,
      payment_tds: paymentMade,
    }).done(function (response) {
      response = JSON.parse(response);
      if (response.success) {
        alert_float('success', response.message);
        table_pur_invoice_payments.ajax.reload(null, false);
      } else {
        alert_float('danger', response.message);
      }
    });
  });

  $('body').on('change', '.payment-remarks-input', function (e) {
    e.preventDefault();

    var rowId = $(this).data('id');
    var payment_remarks = $(this).val();

    // Perform AJAX request to update the invoice date
    $.post(admin_url + 'purchase/update_payment_remarks', {
      id: rowId,
      payment_remarks: payment_remarks
    }).done(function (response) {
      response = JSON.parse(response);
      if (response.success) {
        alert_float('success', response.message);
        table_pur_invoice_payments.ajax.reload(null, false); // Reload table without refreshing the page
      } else {
        alert_float('danger', response.message);
      }
    });
  });

})(jQuery);