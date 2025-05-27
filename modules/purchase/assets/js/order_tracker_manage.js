var hidden_columns = [2, 3, 4, 5], table_rec_campaign;
Dropzone.autoDiscover = false;
var expenseDropzone;
(function ($) {
    "use strict";
    table_rec_campaign = $('.table-table_order_tracker');

    var Params = {
        "type": "[name='type[]']",
        "rli_filter": "[name='rli_filter']",
        "vendors": "[name='vendors[]']",
        "kind": "[name='kind']",
        "budget_head": "[name='budget_head']",
        "order_type_filter": "[name='order_type_filter']",
        "projects": "[name='projects[]']",
        "aw_unw_order_status": "[name='aw_unw_order_status[]']"
    };

    initDataTable('.table-table_order_tracker', admin_url + 'purchase/table_order_tracker', [], [], Params, [3, 'desc']);

    $.each(Params, function (i, obj) {
        // console.log(obj);
        $('select' + obj).on('change', function () {
            table_rec_campaign.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });
    });

    $(document).on('change', 'select[name="type[]"]', function () {
        $('select[name="type[]"]').selectpicker('refresh');
    });

    $(document).on('change', 'select[name="vendors[]"]', function () {
        $('select[name="vendors[]"]').selectpicker('refresh');
    });
    $(document).on('change', 'select[name="projects[]"]', function () {
        $('select[name="projects[]"]').selectpicker('refresh');
    });
    $(document).on('change', 'select[name="aw_unw_order_status[]"]', function () {
        $('select[name="aw_unw_order_status[]"]').selectpicker('refresh');
    });

    $(document).on('change', 'select[name="rli_filter"]', function () {
        $('select[name="rli_filter"]').selectpicker('refresh');
    });

    $(document).on('change', 'select[name="kind"]', function () {
        $('select[name="kind"]').selectpicker('refresh');
    });

    $(document).on('change', 'select[name="budget_head"]', function () {
        $('select[name="budget_head"]').selectpicker('refresh');
    });

    $(document).on('change', 'select[name="order_type_filter"]', function () {
        $('select[name="order_type_filter"]').selectpicker('refresh');
    });

    $(document).on('click', '.reset_all_ot_filters', function () {
        var filterArea = $('.all_ot_filters');
        filterArea.find('input').val("");
        filterArea.find('select').selectpicker("val", "");
        table_rec_campaign.DataTable().ajax.reload().columns.adjust().responsive.recalc();
    });

    $('.table-table_order_tracker').on('draw.dt', function () {
        var reportsTable = $(this).DataTable();
        var sums = reportsTable.ajax.json().sums;
        $(this).find('tfoot').addClass('bold');
        $(this).find('tfoot td').eq(0).html("Total (Per Page)");
        $(this).find('tfoot td.total_budget_ro_projection').html(sums.total_budget_ro_projection);
        $(this).find('tfoot td.total_order_value').html(sums.total_order_value);
        $(this).find('tfoot td.total_committed_contract_amount').html(sums.total_committed_contract_amount);
        $(this).find('tfoot td.total_change_order_amount').html(sums.total_change_order_amount);
        $(this).find('tfoot td.total_rev_contract_value').html(sums.total_rev_contract_value);
        $(this).find('tfoot td.total_anticipate_variation').html(sums.total_anticipate_variation);
        $(this).find('tfoot td.total_cost_to_complete').html(sums.total_cost_to_complete);
        $(this).find('tfoot td.total_final_certified_amount').html(sums.total_final_certified_amount);
    });
    $('.buttons-collection').hide()
})(jQuery);

function change_rli_filter(status, id, table_name) {
    "use strict";
    if (id > 0) {
        $.post(admin_url + 'purchase/change_rli_filter/' + status + '/' + id + '/' + table_name)
            .done(function (response) {
                try {
                    response = JSON.parse(response);

                    if (response.success) {
                        var $statusSpan = $('#status_span_' + id);

                        // Debugging
                        // console.log('Before:', $statusSpan.attr('class'));

                        // Remove all status-related classes
                        $statusSpan.removeClass('label-danger label-success label-info label-warning label-primary label-purple label-teal label-orange label-green label-defaul label-secondaryt');

                        // Add the new class and update content
                        if (response.class) {
                            $statusSpan.addClass(response.class);
                        }
                        if (response.status_str) {
                            $statusSpan.html(response.status_str + ' ' + (response.html || ''));
                        }

                        // Debugging
                        // console.log('After:', $statusSpan.attr('class'));

                        // Display success message
                        // $(".table-table_order_tracker").DataTable().ajax.reload();
                        alert_float('success', response.mess);
                    } else {
                        // Display warning message if the operation fails
                        alert_float('warning', response.mess);
                    }
                } catch (e) {
                    console.error('Error parsing server response:', e);
                    alert_float('danger', 'Invalid server response');
                }
            })
            .fail(function (xhr, status, error) {
                console.error('AJAX Error:', error);
                alert_float('danger', 'Failed to update status');
            });
    }
}

function change_aw_unw_order_status(status, id, table_name) {
    "use strict";
    if (id > 0) {
        $.post(admin_url + 'purchase/change_aw_unw_order_status/' + status + '/' + id + '/' + table_name)
            .done(function (response) {
                try {
                    response = JSON.parse(response);

                    if (response.success) {
                        var $statusSpan = $('#status_aw_uw_span_' + id);

                        // Debugging
                        // console.log('Before:', $statusSpan.attr('class'));

                        // Remove all status-related classes
                        $statusSpan.removeClass('label-danger label-success label-info label-warning label-primary label-purple label-teal label-orange label-green label-defaul label-secondaryt');

                        // Add the new class and update content
                        if (response.class) {
                            $statusSpan.addClass(response.class);
                        }
                        if (response.status_str) {
                            $statusSpan.html(response.status_str + ' ' + (response.html || ''));
                        }

                        // Debugging
                        // console.log('After:', $statusSpan.attr('class'));

                        // Display success message
                        // var table_critical_tracker = $(".table-table_order_tracker");
                        // table_critical_tracker.DataTable().ajax.reload();
                        alert_float('success', response.mess);
                    } else {
                        // Display warning message if the operation fails
                        alert_float('warning', response.mess);
                    }
                } catch (e) {
                    console.error('Error parsing server response:', e);
                    alert_float('danger', 'Invalid server response');
                }
            })
            .fail(function (xhr, status, error) {
                console.error('AJAX Error:', error);
                alert_float('danger', 'Failed to update status');
            });
    }
}

function update_budget_head(status, id, table_name) {
    "use strict";
    if (id > 0) {
        $.post(admin_url + 'purchase/update_budget_head/' + status + '/' + id + '/' + table_name)
            .done(function (response) {
                try {
                    response = JSON.parse(response);

                    if (response.success) {
                        var $statusSpan = $('#budget_head_span_' + id);

                        // Debugging
                        // console.log('Before:', $statusSpan.attr('class'));

                        // Remove all status-related classes
                        $statusSpan.removeClass('label-danger label-success label-info label-warning label-primary label-purple label-teal label-orange label-green label-defaul label-secondaryt');

                        // Add the new class and update content
                        if (response.class) {
                            $statusSpan.addClass(response.class);
                        }
                        if (response.status_str) {
                            $statusSpan.html(response.status_str + ' ' + (response.html || ''));
                        }

                        // Debugging
                        // console.log('After:', $statusSpan.attr('class'));

                        // Display success message
                        // var table_critical_tracker = $(".table-table_order_tracker");
                        // table_critical_tracker.DataTable().ajax.reload();
                        alert_float('success', response.mess);
                    } else {
                        // Display warning message if the operation fails
                        alert_float('warning', response.mess);
                    }
                } catch (e) {
                    console.error('Error parsing server response:', e);
                    alert_float('danger', 'Invalid server response');
                }
            })
            .fail(function (xhr, status, error) {
                console.error('AJAX Error:', error);
                alert_float('danger', 'Failed to update status');
            });
    }
}

