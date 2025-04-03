<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="panel_s">
            <div class="panel-body">
                <div class="panel-table-full">
                    <?php $this->load->view('admin/payments/table_html'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
$(function() {
    initDataTable('.table-payments', admin_url + 'payments/table', undefined, undefined, 'undefined',
        <?php echo hooks()->apply_filters('payments_table_default_order', json_encode([0, 'desc'])); ?>);

    $('.table-payments').on('draw.dt', function () {
        var reportsTable = $(this).DataTable();
        var sums = reportsTable.ajax.json().sums;
        $(this).find('tfoot').addClass('bold');
        $(this).find('tfoot td').eq(1).html("Total (Per Page)");
        $(this).find('tfoot td.total_payments_amount').html(sums.total_payments_amount);
    });
});
</script>
</body>

</html>