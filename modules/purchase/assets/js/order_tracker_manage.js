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
                response = JSON.parse(response);
                if (response.success == true) {
                    var $statusSpan = $('#status_span_' + id);
                    $statusSpan.removeClass('label-danger label-success label-info label-warning');
                    $statusSpan.addClass(response.class);
                    $statusSpan.html(response.status_str + ' ' + response.html);
                    alert_float('success', response.mess);
                } else {
                    alert_float('warning', response.mess);
                }
            });
    }
}
