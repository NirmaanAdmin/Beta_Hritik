<script>
  var area_value = {};

  function new_area() {
    "use strict";
    $('#area').modal('show');
    $('.edit-title').addClass('hide');
    $('.add-title').removeClass('hide');
    $('#area_id').html('');

    var handsontable_html = '<div id="hot_area" class="hot handsontable htColumnHeaders"></div>';
    if ($('#add_handsontable').html() != null) {
      $('#add_handsontable').empty();

      $('#add_handsontable').html(handsontable_html);
    } else {
      $('#add_handsontable').html(handsontable_html);

    }


    setTimeout(function() {
      "use strict";

      //hansometable for allowance no taxable
      var hotElement1 = document.querySelector('#hot_area');

      var area = new Handsontable(hotElement1, {
        contextMenu: true,
        manualRowMove: true,
        manualColumnMove: true,
        stretchH: 'all',
        autoWrapRow: true,
        rowHeights: 30,
        defaultRowHeight: 100,
        maxRows: 22,
        minRows: 9,
        width: '100%',
        height: 330,
        licenseKey: 'non-commercial-and-evaluation',
        rowHeaders: true,
        autoColumncommodity_group: {
          samplingRatio: 23
        },


        filters: true,
        manualRowRecommodity_group: true,
        manualColumnRecommodity_group: true,
        allowInsertRow: true,
        allowRemoveRow: true,
        columnHeaderHeight: 40,

        colWidths: [40, 100, 30, 30, 30, 140],
        rowHeights: 30,

        rowHeaderWidth: [44],
        minRow: 10,

        columns: [{
            type: 'text',
            data: 'area_name'
          }, {
            type: 'numeric',
            data: 'order',
          },
          {
            type: 'checkbox',
            data: 'display',
            checkedTemplate: 'yes',
            uncheckedTemplate: 'no'
          },
          {
            type: 'text',
            data: 'note',
          },

        ],

        colHeaders: [
          "<?php echo _l('area_name') ?>",
          "<?php echo _l('order') ?>",
          "<?php echo _l('display') ?>",
          "<?php echo _l('note') ?>",
        ],


        data: [{
            "area_name": "",
            "order": "",
            "display": "yes",
            "note": ""
          },
          {
            "area_name": "",
            "order": "",
            "display": "yes",
            "note": ""
          },
          {
            "area_name": "",
            "order": "",
            "display": "yes",
            "note": ""
          },
          {
            "area_name": "",
            "order": "",
            "display": "yes",
            "note": ""
          },
          {
            "area_name": "",
            "order": "",
            "display": "yes",
            "note": ""
          },
          {
            "area_name": "",
            "order": "",
            "display": "yes",
            "note": ""
          },
          {
            "area_name": "",
            "order": "",
            "display": "yes",
            "note": ""
          },
          {
            "area_name": "",
            "order": "",
            "display": "yes",
            "note": ""
          },
          {
            "area_name": "",
            "order": "",
            "display": "yes",
            "note": ""
          },
        ],

      });
      area_value = area;
    }, 300);

  }

  function edit_area(invoker, id) {
    "use strict";

    var name = $(invoker).data('name');

    var order = $(invoker).data('order');
    if ($(invoker).data('display') == 0) {
      var display = 'no';
    } else {
      var display = 'yes';
    }
    var note = $(invoker).data('note');

    $('#area_id').html('');
    $('#area_id').append(hidden_input('id', id));

    $('#area').modal('show');
    $('.edit-title').removeClass('hide');
    $('.add-title').addClass('hide');


    var handsontable_html = '<div id="hot_area" class="hot handsontable htColumnHeaders"></div>';
    if ($('#add_handsontable').html() != null) {
      $('#add_handsontable').empty();

      $('#add_handsontable').html(handsontable_html);
    } else {
      $('#add_handsontable').html(handsontable_html);

    }

    setTimeout(function() {
      "use strict";
      var hotElement1 = document.querySelector('#hot_area');

      var area = new Handsontable(hotElement1, {
        contextMenu: true,
        manualRowMove: true,
        manualColumnMove: true,
        stretchH: 'all',
        autoWrapRow: true,
        rowHeights: 30,
        defaultRowHeight: 100,
        maxRows: 1,
        width: '100%',
        height: 130,
        rowHeaders: true,
        autoColumncommodity_group: {
          samplingRatio: 23
        },

        licenseKey: 'non-commercial-and-evaluation',
        filters: true,
        manualRowRecommodity_group: true,
        manualColumnRecommodity_group: true,

        columnHeaderHeight: 40,

        colWidths: [40, 100, 30, 30, 30, 140],
        rowHeights: 30,

        rowHeaderWidth: [44],

        columns: [{
            type: 'text',
            data: 'area_name'
          }, {
            type: 'numeric',
            data: 'order',
          },
          {
            type: 'checkbox',
            data: 'display',
            checkedTemplate: 'yes',
            uncheckedTemplate: 'no'
          },
          {
            type: 'text',
            data: 'note',
          },

        ],

        colHeaders: [
          "<?php echo _l('area_name') ?>",
          "<?php echo _l('order') ?>",
          "<?php echo _l('display') ?>",
          "<?php echo _l('note') ?>",
        ],


        data: [{
          "area_name": name,
          "order": order,
          "display": display,
          "note": note
        }],
        

      });
      area_value = area;
    }, 300);

  }

  function add_commodity_group_type(invoker) {
    "use strict";

    var valid_commodity_group_type = $('#hot_area').find('.htInvalid').html();

    if (valid_commodity_group_type) {
      alert_float('danger', "<?php echo _l('data_must_number'); ?>");

    } else {

      $('input[name="hot_area"]').val(area_value.getData());
      $('#add_area').submit();

    }

  }
</script>