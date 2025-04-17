<style type="text/css">
    .daily_report_title,
    .daily_report_activity {
        font-weight: bold;
        text-align: center;
        background-color: lightgrey;
    }

    .daily_report_title {
        font-size: 17px;
    }

    .daily_report_activity {
        font-size: 16px;
    }

    .daily_report_head {
        font-size: 14px;
    }

    .daily_report_label {
        font-weight: bold;
    }

    .daily_center {
        text-align: center;
    }

    .table-responsive {
        overflow-x: visible !important;
        scrollbar-width: none !important;
    }

    .laber-type .dropdown-menu .open,
    .agency .dropdown-menu .open {
        width: max-content !important;
    }

    .agency .dropdown-toggle,
    .laber-type .dropdown-toggle {
        width: 90px !important;
    }
    img.images_w_table {
      width: 116px;
      height: 73px;
   }
</style>
<div class="col-md-12">
    <hr class="hr-panel-separator" />
</div>
<?php  echo form_hidden('isedit'); ?>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table qcr-items-table items table-main-dpr-edit has-calculations no-mtop">
            <thead>
                <tr>
                    <th colspan="13" class="daily_report_activity">QUALITY COMPLIANCE REPORT</th>
                </tr>
                <tr>
                    <th class="daily_report_head daily_center">
                        <span class="daily_report_label">Date</span>
                    </th>
                    <th class="daily_report_head daily_center">
                        <span class="daily_report_label">Floor</span>
                    </th>
                    <th rowspan="2" class="daily_report_head daily_center">
                        <span class="daily_report_label">Location</span>
                    </th>
                    <th class="daily_report_head daily_center">
                        <span class="daily_report_label">Observation</span>
                    </th>
                    <th class="daily_report_head daily_center">
                        <span class="daily_report_label">Category</span>
                    </th>
                    <th class="daily_report_head daily_center" style="width: 9%;">
                        <span class="daily_report_label">Photograph</span>
                    </th>
                    <th class="daily_report_head daily_center" style="width: 9%;">
                        <span class="daily_report_label">Compliance Photograph</span>
                    </th>
                    <th class="daily_report_head daily_center">
                        <span class="daily_report_label">Compliance Detail</span>
                    </th>
                    <th class="daily_report_head daily_center">
                        <span class="daily_report_label">Status</span>
                    </th>
                    <th class="daily_report_head daily_center">
                        <span class="daily_report_label">Remarks</span>
                    </th>
                    <th class="daily_report_head daily_center">
                        <span class="daily_report_label"></span>
                    </th>
                </tr>
            </thead>
            <tbody class="dpr_body">
                <?php echo pur_html_entity_decode($qcr_row_template); ?>
            </tbody>
        </table>
    </div>
    <div id="removed-items"></div>
</div>

<script type="text/javascript">
    $(document).on('click', '.qcr-add-item-to-table', function(event) {
        "use strict";

        var data = 'undefined';
        data = typeof(data) == 'undefined' || data == 'undefined' ? qcr_get_item_preview_values() : data;
        var table_row = '';
        var item_key = lastAddedItemKey ? lastAddedItemKey += 1 : $("body").find('.qcr-items-table tbody .item').length + 1;
        lastAddedItemKey = item_key;

        qcr_get_item_row_template('newitems[' + item_key + ']', data.date, data.floor, data.location, data.observation, data.category, data.photograph, data.compliance_photograph, data.compliance_detail, data.status, data.remarks, item_key).done(function(output) {
            table_row += output;

            $('.dpr_body').append(table_row);
            var sourceInput = $("input[name='photograph']")[0];
            var targetInput = $("input[name='newitems[" + lastAddedItemKey + "][photograph]']")[0];
            if (sourceInput.files.length > 0) {
                var dataTransfer = new DataTransfer();
                for (var i = 0; i < sourceInput.files.length; i++) {
                    dataTransfer.items.add(sourceInput.files[i]);
                }
                targetInput.files = dataTransfer.files;
            }
            var sourceInput1 = $("input[name='compliance_photograph']")[0];
            var targetInput1 = $("input[name='newitems[" + item_key + "][compliance_photograph]']")[0];
            if (sourceInput1 && sourceInput1.files && sourceInput1.files.length > 0) {
                var dataTransfer1 = new DataTransfer();
                for (var i = 0; i < sourceInput1.files.length; i++) {
                    dataTransfer1.items.add(sourceInput1.files[i]);
                }
                targetInput1.files = dataTransfer1.files;
            }
            init_selectpicker();
            pur_clear_item_preview_values();
            $('body').find('#items-warning').remove();
            $("body").find('.dt-loader').remove();
            $('#item_select').selectpicker('val', '');

            return true;
        });
        return false;
    });

    function qcr_get_item_row_template(name, date, floor, location, observation, category, photograph, compliance_photograph, compliance_detail, status, remarks, item_key) {
        "use strict";

        jQuery.ajaxSetup({
            async: false
        });

        var d = $.post(admin_url + 'forms/get_qcr_row_template', {
            name: name,
            date: date,
            floor: floor,
            location: location,
            observation: observation,
            category: category,
            photograph: photograph,
            compliance_photograph: compliance_photograph,
            compliance_detail: compliance_detail,
            status: status,
            remarks: remarks,
            item_key: item_key
        });
        jQuery.ajaxSetup({
            async: true
        });
        return d;
    }

    function qcr_get_item_preview_values() {
        "use strict";

        var response = {};
        response.date = $('.qcr-items-table input[name="date"]').val();
        response.floor = $('.qcr-items-table input[name="floor"]').val();
        response.location = $('.qcr-items-table input[name="location"]').val();
        response.observation = $('.qcr-items-table textarea[name="observation"]').val();
        response.category = $('.qcr-items-table select[name="category"]').selectpicker('val');;
        response.compliance_detail = $('.qcr-items-table textarea[name="compliance_detail"]').val();
        response.status = $('.qcr-items-table select[name="status"]').val();
        response.remarks = $('.qcr-items-table textarea[name="remarks"]').val();

        return response;
    }

    function pur_clear_item_preview_values() {
        "use strict";

        var previewArea = $('.dpr_body .main');
        previewArea.find('input').val('');
        previewArea.find('textarea').val('');
        previewArea.find('select').val('').selectpicker('refresh');
    }

    function qcr_delete_item(row, itemid, parent){
        "use strict";

      $(row).parents('tr').addClass('animated fadeOut', function() {
         setTimeout(function() {
            $(row).parents('tr').remove();
            pur_calculate_total();
         }, 50);
      });
      if (itemid && $('input[name="isedit"]').length > 0) {
         $(parent + ' #removed-items').append(hidden_input('removed_items[]', itemid));
      }
    }
</script>