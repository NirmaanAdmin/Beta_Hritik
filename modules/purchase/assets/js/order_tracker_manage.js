var hidden_columns = [2,3,4,5], table_rec_campaign;
Dropzone.autoDiscover = false;
var expenseDropzone;
(function($) {
"use strict"; 
    table_rec_campaign = $('.table-table_order_tracker');

    var Params = {        
        "type": "[name='type[]']",
    };

    initDataTable('.table-table_order_tracker', admin_url+'purchase/table_order_tracker', [], [], Params,[3, 'desc']);
	
    $.each(Params, function(i, obj) {
        console.log(obj);
        $('select' + obj).on('change', function() {  
            table_rec_campaign.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });
    });


})(jQuery);

function change_rli_filter(status, id, table_name) {
    "use strict";
    if (id > 0) {
        $.post(admin_url + 'purchase/change_rli_filter/' + status + '/' + id + '/' + table_name)
            .done(function(response) {
                try {
                    response = JSON.parse(response);

                    if (response.success) {
                        var $statusSpan = $('#status_span_' + id);

                        // Debugging
                        console.log('Before:', $statusSpan.attr('class'));

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
                        console.log('After:', $statusSpan.attr('class'));

                        // Display success message
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
            .fail(function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert_float('danger', 'Failed to update status');
            });
    }
}

