get_data_vendor_allocation_report();

function get_data_vendor_allocation_report() {
  "use strict";
  var formData = new FormData();
  formData.append("csrf_token_name", $('input[name="csrf_token_name"]').val());
  formData.append("from_date", $('input[name="from_date"]').val());
  formData.append("to_date", $('input[name="to_date"]').val());
  formData.append("warehouse_id", $('select[id="warehouse_filter"]').val());
  formData.append("commodity_id", $('select[id="commodity_filter"]').val());
  $.ajax({ 
    url: admin_url + 'warehouse/get_data_vendor_allocation_report', 
    method: 'post', 
    data: formData, 
    contentType: false, 
    processData: false
  }).done(function(response) {
      var response = JSON.parse(response);
      $('#vendor_a_report').html('');
      $('#vendor_a_report').append(response.value);
  });   
}

function vendor_allocation_submit(invoker) {
  "use strict";
  $('#print_report').submit(); 
}
