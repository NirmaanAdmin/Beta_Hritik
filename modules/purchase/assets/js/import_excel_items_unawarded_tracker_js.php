<script>
  function uploadfilecsv(event) {
    "use strict";

    if (($("#file_csv").val() != '') && ($("#file_csv").val().split('.').pop() == 'xlsx')) {
      var formData = new FormData();
      formData.append("file_csv", $('#file_csv')[0].files[0]);
      formData.append("csrf_token_name", $('input[name="csrf_token_name"]').val());
      formData.append("leads_import", $('input[name="leads_import"]').val());
      //show box loading
      var html = '';
      html += '<div class="Box">';
      html += '<span>';
      html += '<span></span>';
      html += '</span>';
      html += '</div>';
      $('#box-loading').html(html);
      $('#loader-container').removeClass('hide');
      $(event).attr("disabled", "disabled");

      $.ajax({
        url: admin_url + 'purchase/import_file_xlsx_unawared_tracker_items',
        method: 'post',
        data: formData,
        contentType: false,
        processData: false

      }).done(function(response) {
        response = JSON.parse(response);
        //hide boxloading
        $('#box-loading').html('');
        $('#loader-container').addClass('hide');
        $(event).removeAttr('disabled')

        $("#file_csv").val(null);
        $("#file_csv").change();
        $(".panel-body").find("#file_upload_response").html();

        if ($(".panel-body").find("#file_upload_response").html() != '') {
          $(".panel-body").find("#file_upload_response").empty();
        };


        $("#file_upload_response").append("<h4><?php echo _l("_Result") ?></h4><h5><?php echo _l('import_line_number') ?> :" + response.total_rows + " </h5>");



        $("#file_upload_response").append("<h5><?php echo _l('import_line_number_success') ?> :" + response.total_row_success + " </h5>");



        $("#file_upload_response").append("<h5><?php echo _l('import_line_number_failed') ?> :" + response.total_row_false + " </h5>");

        $('.invoice-item table.order-tracker-items-table.items tbody').html('');
        $('.invoice-item table.order-tracker-items-table.items tbody').append(response.list_item);
        
        init_selectpicker();
        // pur_reorder_items('.invoice-item');
        // pur_clear_item_preview_values('.invoice-item');
        $('body').find('#items-warning').remove();
        $("body").find('.dt-loader').remove();
        $('#item_select').selectpicker('val', '');
        if ((response.total_row_false > 0) || (response.total_rows_data_error > 0)) {
          $("#file_upload_response").append('<a href="' + site_url + response.filename + '" class="btn btn-warning"  ><?php echo _l('download_file_error') ?></a>');
        }
        if (response.total_rows < 1) {
          alert_float('warning', response.message);
        }
      });
      return false;
    } else if ($("#file_csv").val() != '') {
      alert_float('warning', "<?php echo _l('_please_select_a_file') ?>");
    }

  }
</script>