Dropzone.autoDiscover = false;
var expenseDropzone;
(function ($) {
  "use strict";
  var table_invoice = $('.table-table_pur_invoices');
  var Params = {
    "from_date": 'input[name="from_date"]',
    "to_date": 'input[name="to_date"]',
    "contract": "[name='contract[]']",
    "pur_orders": "[name='pur_orders[]']",
    "wo_orders": "[name='wo_orders[]']",
    "vendors": "[name='vendor_ft[]']",
    "billing_invoices": "[name='billing_invoices']",
    "budget_head": "[name='budget_head']",
    "billing_status": "[name='billing_status']",
  };

  initDataTable(table_invoice, admin_url + 'purchase/table_pur_invoices', [], [], Params, [5, 'desc']);
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

  $(document).on('change', 'select[name="billing_invoices"]', function () {
    $('select[name="billing_invoices"]').selectpicker('refresh');
  });
  $(document).on('change', 'select[name="budget_head"]', function () {
    $('select[name="budget_head"]').selectpicker('refresh');
  });
  $(document).on('change', 'select[name="billing_status"]', function () {
    $('select[name="billing_status"]').selectpicker('refresh');
  });

  $(document).on('click', '.reset_vbt_all_filters', function () {
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
      success: function (file, response) {
        if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
          window.location.reload();
        }
      }
    }));
  }

  $('.table-table_pur_invoices').on('draw.dt', function () {
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

  $('body').on('change', '.vin-input', function (e) {
    e.preventDefault();

    var rowId = $(this).data('id');
    var vin = $(this).val();

    // Perform AJAX request to update the vin
    $.post(admin_url + 'purchase/update_vendor_invoice_number', {
      id: rowId,
      vin: vin
    }).done(function (response) {
      response = JSON.parse(response);
      if (response.success) {
        alert_float('success', response.message);
        table_pur_invoices.ajax.reload(null, false); // Reload table without refreshing the page
      } else {
        alert_float('danger', response.message);
      }
    });
  });

  $('body').on('change', '.invoice-date-input', function (e) {
    e.preventDefault();

    var rowId = $(this).data('id');
    var invoiceDate = $(this).val();

    // Perform AJAX request to update the invoice date
    $.post(admin_url + 'purchase/update_invoice_date', {
      id: rowId,
      invoice_date: invoiceDate
    }).done(function (response) {
      response = JSON.parse(response);
      if (response.success) {
        alert_float('success', response.message);
        table_pur_invoices.ajax.reload(null, false); // Reload table without refreshing the page
      } else {
        alert_float('danger', response.message);
      }
    });
  });
  $('body').on('change', '.description-services-input', function (e) {
    e.preventDefault();

    var rowId = $(this).data('id');
    var description_services = $(this).val();

    // Perform AJAX request to update the invoice date
    $.post(admin_url + 'purchase/update_description_services', {
      id: rowId,
      description_services: description_services
    }).done(function (response) {
      response = JSON.parse(response);
      if (response.success) {
        alert_float('success', response.message);
        table_pur_invoices.ajax.reload(null, false); // Reload table without refreshing the page
      } else {
        alert_float('danger', response.message);
      }
    });
  });
  $('body').on('change', '.adminnote-input', function (e) {
    e.preventDefault();

    var rowId = $(this).data('id');
    var adminnote = $(this).val();

    // Perform AJAX request to update the invoice date
    $.post(admin_url + 'purchase/update_adminnote', {
      id: rowId,
      admin_note: adminnote
    }).done(function (response) {
      response = JSON.parse(response);
      if (response.success) {
        alert_float('success', response.message);
        table_pur_invoices.ajax.reload(null, false); // Reload table without refreshing the page
      } else {
        alert_float('danger', response.message);
      }
    });
  });

  $('body').on('change', '.billing-remarks-input', function (e) {
    e.preventDefault();

    var rowId = $(this).data('id');
    var billing_remarks = $(this).val();

    // Perform AJAX request to update the invoice date
    $.post(admin_url + 'purchase/update_billing_remarks', {
      id: rowId,
      billing_remarks: billing_remarks
    }).done(function (response) {
      response = JSON.parse(response);
      if (response.success) {
        alert_float('success', response.message);
        table_pur_invoices.ajax.reload(null, false); // Reload table without refreshing the page
      } else {
        alert_float('danger', response.message);
      }
    });
  });

  $('body').on('change', '#select_invoice', function (e) {
    e.preventDefault();
    var select_invoice = $(this).val();
    if(select_invoice == 'applied_invoice') {
      $('.applied-to-invoice').removeClass('hide');
    } else {
      $('.applied-to-invoice').addClass('hide');
      $('#applied_to_invoice').val('').selectpicker('refresh');
    }
  });

  $('body').on('change', '#bulk_select_invoice', function (e) {
    e.preventDefault();
    var select_invoice = $(this).val();
    var id = $(this).data('id');
    if(select_invoice == 'applied_invoice') {
      $('#bulk_applied_to_invoice[data-id="' + id + '"]').closest('.bulk-applied-to-invoice').removeClass('hide')
    } else {
      $('#bulk_applied_to_invoice[data-id="' + id + '"]').closest('.bulk-applied-to-invoice').addClass('hide');
      $('#bulk_applied_to_invoice[data-id="' + id + '"]').val('').selectpicker('refresh');
    }
  });

  $('body').on('change', '#convert_select_invoice', function (e) {
    e.preventDefault();
    var select_invoice = $(this).val();
    if(select_invoice == 'applied_invoice') {
      $('#convert_applied_to_invoice').closest('.convert-applied-to-invoice').removeClass('hide')
    } else {
      $('#convert_applied_to_invoice').closest('.convert-applied-to-invoice').addClass('hide');
      $('#convert_applied_to_invoice').val('').selectpicker('refresh');
    }
  });

  $('body').on('click', '.update_vbt_convert', function (e) {
    e.preventDefault();
    var convert_expense_name = $('#convert_expense_name').val();
    var convert_category = $('#convert_category').val();
    var convert_date = $('#convert_date').val();
    var convert_select_invoice = $('#convert_select_invoice').val();
    var convert_applied_to_invoice = $('#convert_applied_to_invoice').val();

    if(convert_expense_name) {
      $('.all_expense_name textarea').val(convert_expense_name);
    }
    if(convert_category) {
      $('.all_budget_head select').val(convert_category).trigger('change');
    }
    if(convert_date) {
      $('.all_invoice_date input').val(convert_date).trigger('change');
    }
    if(convert_select_invoice == 'create_invoice') {
      $('select#bulk_select_invoice').val(convert_select_invoice).trigger('change');
    } else {
      $('select#bulk_select_invoice').val(convert_select_invoice).trigger('change');
      $('select#bulk_applied_to_invoice').val(convert_applied_to_invoice).trigger('change');
    }
  });

  $('body').on('change', '#single_pur_order', function (e) {
    e.preventDefault();
    var container = $(this).closest('.row');
    var woSelect = container.find('#single_wo_order');
    var ordertrackerSelect = container.find('#single_order_tracker');
    woSelect.val('').prop('disabled', true).selectpicker('refresh');
    ordertrackerSelect.val('').prop('disabled', true).selectpicker('refresh');
  });

  $('body').on('change', '#single_wo_order', function (e) {
    e.preventDefault();
    var container = $(this).closest('.row');
    var poSelect = container.find('#single_pur_order');
    var ordertrackerSelect = container.find('#single_order_tracker');
    poSelect.val('').prop('disabled', true).selectpicker('refresh');
    ordertrackerSelect.val('').prop('disabled', true).selectpicker('refresh');
  });

  $('body').on('change', '#single_order_tracker', function (e) {
    e.preventDefault();
    var container = $(this).closest('.row');
    var poSelect = container.find('#single_pur_order');
    var woSelect = container.find('#single_wo_order');
    poSelect.val('').prop('disabled', true).selectpicker('refresh');
    woSelect.val('').prop('disabled', true).selectpicker('refresh');
  });

  $('body').on('change', '#bulk_pur_order', function (e) {
    e.preventDefault();
    var container = $(this).closest('.row');
    var woSelect = container.find('#bulk_wo_order');
    var ordertrackerSelect = container.find('#bulk_order_tracker');
    woSelect.val('').prop('disabled', true).selectpicker('refresh');
    ordertrackerSelect.val('').prop('disabled', true).selectpicker('refresh');
  });

  $('body').on('change', '#bulk_wo_order', function (e) {
    e.preventDefault();
    var container = $(this).closest('.row');
    var poSelect = container.find('#bulk_pur_order');
    var ordertrackerSelect = container.find('#bulk_order_tracker');
    poSelect.val('').prop('disabled', true).selectpicker('refresh');
    ordertrackerSelect.val('').prop('disabled', true).selectpicker('refresh');
  });

  $('body').on('change', '#bulk_order_tracker', function (e) {
    e.preventDefault();
    var container = $(this).closest('.row');
    var poSelect = container.find('#bulk_pur_order');
    var woSelect = container.find('#bulk_wo_order');
    poSelect.val('').prop('disabled', true).selectpicker('refresh');
    woSelect.val('').prop('disabled', true).selectpicker('refresh');
  });

  $('body').on('click', '.update_bulk_assign', function (e) {
    e.preventDefault();
    var bulk_pur_order = $('#bulk_pur_order').val();
    var bulk_wo_order = $('#bulk_wo_order').val();
    var bulk_order_tracker = $('#bulk_order_tracker').val();
    if ($('#bulk_pur_order').prop('disabled')) {
      $('.all_pur_order select').val('').prop('disabled', true).selectpicker('refresh');
    } else {
      $('.all_pur_order select').val(bulk_pur_order).selectpicker('refresh');
    }
    if ($('#bulk_wo_order').prop('disabled')) {
      $('.all_wo_order select').val('').prop('disabled', true).selectpicker('refresh');
    } else {
      $('.all_wo_order select').val(bulk_wo_order).selectpicker('refresh');
    }
    if ($('#bulk_order_tracker').prop('disabled')) {
      $('.all_order_tracker select').val('').prop('disabled', true).selectpicker('refresh');
    } else {
      $('.all_order_tracker select').val(bulk_order_tracker).selectpicker('refresh');
    }
  });

  $('body').on('shown.bs.tab', '#tab_bulk_action, #tab_bulk_assign', function (e) {
    var target = $(e.target).attr("href").replace('#', '');
    $('#bulk_active_tab').val(target);
  });

  appValidateForm($('#pur_invoice-expense-form'), {
    expense_name: 'required',
    category: 'required',
    date: 'required',
    amount: 'required',
    select_invoice: 'required',
    applied_to_invoice: {
      required: function () {
          return $('#select_invoice').val() == 'applied_invoice';
      }
    },
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

function change_payment_status(status, id) {
  "use strict";
  if (id > 0) {
    $.post(admin_url + 'purchase/change_payment_status/' + status + '/' + id).done(function (response) {
      response = JSON.parse(response);
      if (response.success == true) {
        if ($('#status_span_' + id).hasClass('label-danger')) {
          $('#status_span_' + id).removeClass('label-danger');
          $('#status_span_' + id).addClass(response.class);
          $('#status_span_' + id).html(response.status_str + ' ' + response.html);
        } else if ($('#status_span_' + id).hasClass('label-success')) {
          $('#status_span_' + id).removeClass('label-success');
          $('#status_span_' + id).addClass(response.class);
          $('#status_span_' + id).html(response.status_str + ' ' + response.html);
        } else if ($('#status_span_' + id).hasClass('label-info')) {
          $('#status_span_' + id).removeClass('label-info');
          $('#status_span_' + id).addClass(response.class);
          $('#status_span_' + id).html(response.status_str + ' ' + response.html);
        } else if ($('#status_span_' + id).hasClass('label-warning')) {
          $('#status_span_' + id).removeClass('label-warning');
          $('#status_span_' + id).addClass(response.class);
          $('#status_span_' + id).html(response.status_str + ' ' + response.html);
        } else if ($('#status_span_' + id).hasClass('label-primary')) {
          $('#status_span_' + id).removeClass('label-primary');
          $('#status_span_' + id).addClass(response.class);
          $('#status_span_' + id).html(response.status_str + ' ' + response.html);
        } else if ($('#status_span_' + id).hasClass('label-muted')) {
          $('#status_span_' + id).removeClass('label-muted');
          $('#status_span_' + id).addClass(response.class);
          $('#status_span_' + id).html(response.status_str + ' ' + response.html);
        }
        alert_float('success', response.mess);
      } else {
        alert_float('warning', response.mess);
      }
    });
  }
}

function change_budget_head(budgetid, id) {
  "use strict";
  if (id > 0) {
    $.post(admin_url + 'purchase/change_budget_head/' + budgetid + '/' + id).done(function (response) {
      response = JSON.parse(response);
      if (response.success == true) {
        $('#budget_span_' + id).removeClass('label-info');
        $('#budget_span_' + id).addClass(response.class);
        $('#budget_span_' + id).html(response.status_str + ' ' + response.html);
        alert_float('success', response.mess);
      } else {
        alert_float('warning', response.mess);
      }
    });
  }
}

function convert_expense(pur_invoice, total) {
  "use strict";
  var module_type = 1;

  $.post(admin_url + 'purchase/get_project_info/' + pur_invoice + '/' + module_type).done(function (response) {
    response = JSON.parse(response);
    $('select[name="project_id"]').val(response.project_id).change();
    $('select[name="clientid"]').val(response.customer).change();
    $('select[name="currency"]').val(response.currency).change();
    $('input[name="vendor"]').val(response.vendor);
    $('select[name="category"]').val(response.category).change();
    if (response.budget_head) {
      $('#category').val(response.budget_head).change();
    } else {
      $('#category').val('').change();
    }
    $('input[id="expense_name"]').val(response.description_services);
  });

  $('#pur_invoice_expense').modal('show');
  $('input[id="amount"]').val(total);
  $('#pur_invoice_additional').html('');
  $('#pur_invoice_additional').append(hidden_input('pur_invoice', pur_invoice));
}

function projectExpenseSubmitHandler(form) {
  "use strict";
  var userConfirmed = confirm("Are you sure you want to convert this bill?");
  if (!userConfirmed) {
    return false;
  }
  $.post(form.action, $(form).serialize()).done(function (response) {
    response = JSON.parse(response);
    if (response.expenseid) {
      if (typeof (expenseDropzone) !== 'undefined') {
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

function bulk_convert_ril_bill() {
  "use strict";
  var print_id = '';
  var rows = $('.table-table_pur_invoices').find('tbody tr');
  $.each(rows, function() {
    var checkbox = $($(this).find('td').eq(0)).find('input');
    if (checkbox.prop('checked') === true) {
        if (print_id !== '') {
            print_id += ','; // Append a comma before adding the next value
        }
        print_id += checkbox.val();
    }
  });
  if (print_id !== '') {
    // Perform AJAX request to update the invoice date
    $.post(admin_url + 'purchase/bulk_convert_ril_bill', {
      ids: print_id,
    }).done(function (response) {
      response = JSON.parse(response);
      if (response.success) {
        $('.convert-bulk-actions-body').html('');
        $('.convert-bulk-actions-body').html(response.bulk_html);
        init_selectpicker();
        $('#convert_ril_bill_modal').modal('show');
      } else {
        alert_float('danger', response.message);
      }
    });
  } else {
    alert_float('danger', 'Please select at least one item from the list');
  }
}