<script>

var lastAddedItemKey = null;

function add_vendor_work_completed_item_to_table(data, itemid, merge_invoice, bill_expense) {

  data = typeof data == "undefined" || data == "undefined"
  ? get_work_completed_item_preview_values()
  : data;

  var table_row = "";
  var item_key = lastAddedItemKey
    ? (lastAddedItemKey += 1)
    : $("body").find("tbody.work_completed_main .item").length + 1;
  lastAddedItemKey = item_key;

  table_row += '<tr class="item">';

  table_row +=
  '<td><input type="text" name="newworkcompleteditems['+item_key+'][client]" value="'+data.client+'" class="form-control" id="client"></td>';

  table_row +=
  '<td><input type="text" name="newworkcompleteditems['+item_key+'][type_of_project]" value="'+data.type_of_project+'" class="form-control" id="type_of_project"></td>';

  table_row +=
  '<td><input type="text" name="newworkcompleteditems['+item_key+'][location]" value="'+data.location+'" class="form-control" id="location"></td>';

  table_row +=
  '<td><input type="text" name="newworkcompleteditems['+item_key+'][mini_contractor]" value="'+data.mini_contractor+'" class="form-control" id="mini_contractor"></td>';

  table_row +=
  '<td><input type="text" name="newworkcompleteditems['+item_key+'][scope_of_works]" value="'+data.scope_of_works+'" class="form-control" id="scope_of_works"></td>';

  table_row +=
  '<td><input type="text" name="newworkcompleteditems['+item_key+'][contract_prices]" value="'+data.contract_prices+'" class="form-control" id="contract_prices"></td>';

  table_row +=
  '<td><input type="text" name="newworkcompleteditems['+item_key+'][start_date]" value="'+data.start_date+'" class="form-control" id="start_date"></td>';

  table_row +=
  '<td><input type="text" name="newworkcompleteditems['+item_key+'][end_date]" value="'+data.end_date+'" class="form-control" id="end_date"></td>';

  table_row +=
  '<td><input type="text" name="newworkcompleteditems['+item_key+'][size_of_project]" value="'+data.size_of_project+'" class="form-control" id="size_of_project"></td>';

  table_row +=
    '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_work_completed_item(this,' +
    itemid +
    '); return false;"><i class="fa fa-trash"></i></a></td>';

  table_row += "</tr>";

  $('table.items tbody.work_completed_main').append(table_row);

  $(document).trigger({
    type: "item-added-to-table",
    data: data,
    row: table_row,
  });

  clear_work_completed_item_preview_values();
  return true;
}

function get_work_completed_item_preview_values(tab) {
  var response = {};
  response.client = $('tbody.work_completed_main input[name="client"]').val();
  response.type_of_project = $('tbody.work_completed_main input[name="type_of_project"]').val();
  response.location = $('tbody.work_completed_main input[name="location"]').val();
  response.mini_contractor = $('tbody.work_completed_main input[name="mini_contractor"]').val();
  response.scope_of_works = $('tbody.work_completed_main input[name="scope_of_works"]').val();
  response.contract_prices = $('tbody.work_completed_main input[name="contract_prices"]').val();
  response.start_date = $('tbody.work_completed_main input[name="start_date"]').val();
  response.end_date = $('tbody.work_completed_main input[name="end_date"]').val();
  response.size_of_project = $('tbody.work_completed_main input[name="size_of_project"]').val();
  return response;
}

function clear_work_completed_item_preview_values() {
  var previewArea = $('tbody.work_completed_main').find("tr").eq(0);
  previewArea.find('input').val("");
}

function delete_work_completed(row, itemid) {
  var userConfirmed = confirm("Are you sure you want to delete this item?");
  if (!userConfirmed) {
    return false;
  }
  $(row)
    .parents("tr")
    .addClass("animated fadeOut", function () {
  });

  // If is edit we need to add to input removed_items to track activity
  $("#removed-work-completed").append(hidden_input("rworkcompleteditems[]", itemid));
}

function add_vendor_work_progress_item_to_table(data, itemid, merge_invoice, bill_expense) {

  data = typeof data == "undefined" || data == "undefined"
  ? get_work_progress_item_preview_values()
  : data;

  var table_row = "";
  var item_key = lastAddedItemKey
    ? (lastAddedItemKey += 1)
    : $("body").find("tbody.work_progress_main .item").length + 1;
  lastAddedItemKey = item_key;

  table_row += '<tr class="item">';

  table_row +=
  '<td><input type="text" name="newworkprogressitems['+item_key+'][client]" value="'+data.client+'" class="form-control" id="client"></td>';

  table_row +=
  '<td><input type="text" name="newworkprogressitems['+item_key+'][type_of_project]" value="'+data.type_of_project+'" class="form-control" id="type_of_project"></td>';

  table_row +=
  '<td><input type="text" name="newworkprogressitems['+item_key+'][location]" value="'+data.location+'" class="form-control" id="location"></td>';

  table_row +=
  '<td><input type="text" name="newworkprogressitems['+item_key+'][mini_contractor]" value="'+data.mini_contractor+'" class="form-control" id="mini_contractor"></td>';

  table_row +=
  '<td><input type="text" name="newworkprogressitems['+item_key+'][scope_of_works]" value="'+data.scope_of_works+'" class="form-control" id="scope_of_works"></td>';

  table_row +=
  '<td><input type="text" name="newworkprogressitems['+item_key+'][contract_prices]" value="'+data.contract_prices+'" class="form-control" id="contract_prices"></td>';

  table_row +=
  '<td><input type="text" name="newworkprogressitems['+item_key+'][start_date]" value="'+data.start_date+'" class="form-control" id="start_date"></td>';

  table_row +=
  '<td><input type="text" name="newworkprogressitems['+item_key+'][end_date]" value="'+data.end_date+'" class="form-control" id="end_date"></td>';

  table_row +=
  '<td><input type="text" name="newworkprogressitems['+item_key+'][size_of_project]" value="'+data.size_of_project+'" class="form-control" id="size_of_project"></td>';

  table_row +=
    '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_work_progress_item(this,' +
    itemid +
    '); return false;"><i class="fa fa-trash"></i></a></td>';

  table_row += "</tr>";

  $('table.items tbody.work_progress_main').append(table_row);

  $(document).trigger({
    type: "item-added-to-table",
    data: data,
    row: table_row,
  });

  clear_work_progress_item_preview_values();
  return true;
}

function get_work_progress_item_preview_values(tab) {
  var response = {};
  response.client = $('tbody.work_progress_main input[name="client"]').val();
  response.type_of_project = $('tbody.work_progress_main input[name="type_of_project"]').val();
  response.location = $('tbody.work_progress_main input[name="location"]').val();
  response.mini_contractor = $('tbody.work_progress_main input[name="mini_contractor"]').val();
  response.scope_of_works = $('tbody.work_progress_main input[name="scope_of_works"]').val();
  response.contract_prices = $('tbody.work_progress_main input[name="contract_prices"]').val();
  response.start_date = $('tbody.work_progress_main input[name="start_date"]').val();
  response.end_date = $('tbody.work_progress_main input[name="end_date"]').val();
  response.size_of_project = $('tbody.work_progress_main input[name="size_of_project"]').val();
  return response;
}

function clear_work_progress_item_preview_values() {
  var previewArea = $('tbody.work_progress_main').find("tr").eq(0);
  previewArea.find('input').val("");
}

function delete_work_progress(row, itemid) {
  var userConfirmed = confirm("Are you sure you want to delete this item?");
  if (!userConfirmed) {
    return false;
  }
  $(row)
    .parents("tr")
    .addClass("animated fadeOut", function () {
  });

  // If is edit we need to add to input removed_items to track activity
  $("#removed-work-progress").append(hidden_input("rworkprogressitems[]", itemid));
}
</script>