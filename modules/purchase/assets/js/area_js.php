<script>
  var area_value = {};

  function new_area() {
    "use strict";
    $('#area_model').modal('show');
    $('.edit-title').addClass('hide');
    $('.add-title').removeClass('hide');
    $('input[name="area_id"]').val('');
  }

  function edit_area(invoker, id) {
    "use strict";
    appValidateForm($('#add_area'),{area_name:'required', project:'required'});
    var name = $(invoker).data('name');
    var project_id = $(invoker).data('project');
    $('input[name="area_id"]').val(id);
    $('input[name="area_name"]').val(name);
    $('select[name="project"]').val(project_id).selectpicker('refresh');
    $('#area_model').modal('show');
    $('#area_model .add-title').addClass('hide');
    $('#area_model .edit-title').removeClass('hide');
  }

  appValidateForm($('#add_area'),{area_name:'required', project:'required'});

  var area_table;
  area_table = $('.area-table');
  var Params = {
    "project": "[name='select_project']"
  };
  initDataTable('.area-table', admin_url + 'purchase/table_pur_area', [], [], Params, [0, 'desc']);
  $('select[name="select_project"]').on('change', function () {
    area_table.DataTable().ajax.reload();
  });
</script>