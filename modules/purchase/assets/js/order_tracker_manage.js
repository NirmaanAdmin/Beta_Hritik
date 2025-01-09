var hidden_columns = [2,3,4,5], table_rec_campaign;
Dropzone.autoDiscover = false;
var expenseDropzone;
(function($) {
"use strict"; 
    table_rec_campaign = $('.table-table_order_tracker');

    var Params = {
        "from_date": 'input[name="from_date"]',
        "to_date": 'input[name="to_date"]',
        "vendor": "[name='vendor_ft[]']",
        "status": "[name='status[]']",
        "item_filter": "[name='item_filter[]']",
        "type": "[name='type[]']",
        "project": "[name='project[]']",
        "department": "[name='department[]']",
        "delivery_status": "[name='delivery_status[]']",
        "purchase_request": "[name='pur_request[]']"
    };

    initDataTable('.table-table_order_tracker', admin_url+'purchase/table_order_tracker', [], [], Params,[2, 'desc']);
	init_pur_order();
    $.each(Params, function(i, obj) {
        $('select' + obj).on('change', function() {  
            table_rec_campaign.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });
    });


})(jQuery);