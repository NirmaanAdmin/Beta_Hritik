<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php
            echo form_open($this->uri->uri_string(), ['id' => 'estimate-form', 'class' => '_transaction_form estimate-form']);
            if (isset($estimate)) {
                echo form_hidden('isedit');
            }
            ?>
            <div class="col-md-12">
                <h4
                    class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-flex tw-items-center tw-space-x-2">
                    <span>
                        <?php echo e( isset($estimate) ? format_estimate_number($estimate) : 'Create New Budget'); ?>
                    </span>
                    <?php echo isset($estimate) ? format_estimate_status($estimate->status) : ''; ?>
                </h4>
                <?php $this->load->view('admin/estimates/estimate_template'); ?>
            </div>
            <?php echo form_close(); ?>
            <?php $this->load->view('admin/invoice_items/item'); ?>
        </div>
    </div>
</div>
</div>
<?php init_tail(); ?>
<script>
$(function() {
    validate_estimate_form();
    // Init accountacy currency symbol
    init_currency();
    // Project ajax search
    init_ajax_project_search_by_customer_id();
    // Maybe items ajax search
    init_ajax_search('items', '#item_select.ajax-search', undefined, admin_url + 'items/search');

    initItemSelect();

    /**
    * Initializes the logic for handling item selection and input events.
    */
    function initItemSelect() {
        // Listen for input events on the search box of specific dropdowns
        $(document).on('input', '.item-select  .bs-searchbox input', function() {
          let tab = $('.detailed-costing-tab.active').attr('id');
          let query = $(this).val(); // Get the user's query
          let $bootstrapSelect = $(this).closest('#' + tab + ' .bootstrap-select'); // Get the parent bootstrap-select wrapper
          let $selectElement = $bootstrapSelect.find('select.item-select'); // Get the associated select element

          // console.log("Target Select Element:", $selectElement); // Debug the target <select> element

          if (query.length >= 3) {
            fetchItems(query, $selectElement); // Fetch items dynamically
          }
        });

        // Handle the change event for the item-select dropdown
        $(document).on('change', '.item-select', function() {
          handleItemChange($(this)); // Handle item selection change
        });
    }

    /**
    * Fetches items dynamically based on the search query and populates the target select element.
    * @param {string} query - The search query entered by the user.
    * @param {jQuery} $selectElement - The select element to populate.
    */

    function fetchItems(query, $selectElement) {
        var admin_url = '<?php echo admin_url(); ?>';
        $.ajax({
          url: admin_url + 'purchase/fetch_items', // Controller method URL
          type: 'GET',
          data: {
            search: query
          },
          success: function(data) {
            // console.log("Raw Response Data:", data); // Debug the raw data

            try {
              let items = JSON.parse(data); // Parse JSON response
              // console.log("Parsed Items:", items); // Debug parsed items

              if ($selectElement.length === 0) {
                console.error("Target select element not found.");
                return;
              }

              // Clear existing options in the specific select element
              $selectElement.empty();

              // Add default "Type to search..." option
              $selectElement.append('<option value="">Type to search...</option>');

              // Get the pre-selected ID if available (from a data attribute or a hidden field)
              let preSelectedId = $selectElement.data('selected-id') || null;

              // Populate the specific select element with new options
              items.forEach(function(item) {
                let isSelected = preSelectedId && item.id === preSelectedId ? 'selected' : '';
                let option = `<option  data-commodity-code="${item.id}" value="${item.id}"> ${item.commodity_code} ${item.description}</option>`;
                // console.log("Appending Option:", option); // Debug each option
                $selectElement.append(option);
              });

              // Refresh the selectpicker to reflect changes
              $selectElement.selectpicker('refresh');

              // console.log("Updated Select Element HTML:", $selectElement.html()); // Debug the final HTML
            } catch (error) {
              console.error("Error Processing Response:", error);
            }
          },
          error: function() {
            console.error('Failed to fetch items.');
          }
        });
    }

    /**
    * Handles the change event for the item-select dropdown.
    * @param {jQuery} $selectElement - The select element that triggered the change.
    */
    function handleItemChange($selectElement) {
        let selectedId = $selectElement.val(); // Get the selected item's ID
        let selectedCommodityCode = $selectElement.find(':selected').data('commodity-code'); // Get the commodity code
        let $inputField = $selectElement.closest('tr').find('input[name="item_code"]'); // Find the associated input field

        if ($inputField.length > 0) {
          $inputField.val(selectedCommodityCode || ''); // Update the input field with the commodity code
          // console.log("Updated Input Field:", $inputField, "Value:", selectedCommodityCode); // Debug input field
        }
    }
});
</script>
</body>

</html>
