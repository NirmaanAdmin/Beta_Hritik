<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Define common columns for both tables
$aColumns = [
   'order_name', // Will represent 'pur_order_name' or 'wo_order_name'
   'rli_filter',
   'vendor',
   'order_date',
   'completion_date',
   'budget',
   'total',
   'co_total',
   'total_rev_contract_value',
   'anticipate_variation',
   'cost_to_complete',
   'final_certified_amount',
   'kind',
   'group_name',
   'remarks',
];

$sIndexColumn = 'id';

// Use a derived table to union both tables
$sTable = "(
    SELECT 
        " . db_prefix() . "pur_orders.id,
        " . db_prefix() . "pur_orders.pur_order_name as order_name,
        " . db_prefix() . "pur_orders.vendor,
        " . db_prefix() . "pur_orders.order_date,
        " . db_prefix() . "pur_orders.total,
        " . db_prefix() . "pur_orders.group_name
    FROM " . db_prefix() . "pur_orders
    UNION ALL
    SELECT 
        " . db_prefix() . "wo_orders.id,
        " . db_prefix() . "wo_orders.wo_order_name as order_name,
        " . db_prefix() . "wo_orders.vendor,
        " . db_prefix() . "wo_orders.order_date,
        " . db_prefix() . "wo_orders.total,
        " . db_prefix() . "wo_orders.group_name
    FROM " . db_prefix() . "wo_orders
) as combined_orders";

$join = [
  
   'LEFT JOIN ' . db_prefix() . 'assets_group ON ' . db_prefix() . 'assets_group.group_id = combined_orders.group_pur',
];


$where = [];

$type = $this->ci->input->post('type');
if (isset($type)) {
    $where_type = '';
    foreach ($type as $t) {
        if ($t != '') {
            if ($where_type == '') {
                $where_type .= ' AND (source_table  = "' . $t . '"';
            } else {
                $where_type .= ' or source_table  = "' . $t . '"';
            }
        }
    }
    if ($where_type != '') {
        $where_type .= ')';
        array_push($where, $where_type);
    }
}
$having = '';

// Query and process data
$result = data_tables_init_union($aColumns, $sIndexColumn, $sTable, $join, $where, [
   'combined_orders.id as id',
   'rli_filter',
   'vendor',
   'order_date',
   'completion_date',
   'budget',
   'co_total',
   'total',
   'total_rev_contract_value',
   'anticipate_variation',
   'cost_to_complete',
   'final_certified_amount',
   'kind',
   'group_name',
   'remarks',
   'source_table',
]);

$output  = $result['output'];
$rResult = $result['rResult'];

$sr = 1;
foreach ($rResult as $aRow) {
   $row = [];

   foreach ($aColumns as $column) {
      $_data = isset($aRow[$column]) ? $aRow[$column] : '';

      // Process specific columns
      if ($column == 'total') {
         $base_currency = get_base_currency_pur();
         $_data = app_format_money($aRow['total'], $base_currency->symbol);
      } elseif ($column == 'order_name') {
         $_data = '<a href="#">' . $aRow['order_name'] . '</a>';
      } elseif ($column == 'vendor') {
         $_data = '<a href="#">' . $aRow['vendor'] . '</a>';
      } elseif ($column == 'order_date') {
         $_data = _d($aRow['order_date']);
      } elseif ($column == 'completion_date') {
         // Inline editable input for Completion Date
         $_data = '<input type="date" class="form-control completion-date-input" 
                        value="' . $aRow['completion_date'] . '" 
                        data-id="' . $aRow['id'] . '" 
                        data-type="' . $aRow['source_table'] . '">';
      } elseif ($column == 'rli_filter') {
         // Define an array of statuses with their corresponding labels and table attributes
         $status_labels = [
            0 => ['label' => 'danger', 'table' => 'provided_by_ril', 'text' => _l('provided_by_ril')],
            1 => ['label' => 'success', 'table' => 'new_item_service_been_addded_as_per_instruction', 'text' => _l('new_item_service_been_addded_as_per_instruction')],
            2 => ['label' => 'info', 'table' => 'due_to_spec_change_then_original_cost', 'text' => _l('due_to_spec_change_then_original_cost')],
            3 => ['label' => 'warning', 'table' => 'deal_slip', 'text' => _l('deal_slip')],
            4 => ['label' => 'primary', 'table' => 'to_be_provided_by_ril_but_managed_by_bil', 'text' => _l('to_be_provided_by_ril_but_managed_by_bil')],
            5 => ['label' => 'secondary', 'table' => 'due_to_additional_item_as_per_apex_instrution', 'text' => _l('due_to_additional_item_as_per_apex_instrution')],
            6 => ['label' => 'purple', 'table' => 'event_expense', 'text' => _l('event_expense')],
            7 => ['label' => 'teal', 'table' => 'pending_procurements', 'text' => _l('pending_procurements')],
            8 => ['label' => 'orange', 'table' => 'common_services_in_ghj_scope', 'text' => _l('common_services_in_ghj_scope')],
            9 => ['label' => 'green', 'table' => 'common_services_in_ril_scope', 'text' => _l('common_services_in_ril_scope')],
            10 => ['label' => 'default', 'table' => 'due_to_site_specfic_constraint', 'text' => _l('due_to_site_specfic_constraint')],
         ];
         // Start generating the HTML
         $rli_filter = '';
         if (isset($status_labels[$aRow['rli_filter']])) {
            $status = $status_labels[$aRow['rli_filter']];
            $rli_filter = '<span class="inline-block label label-' . $status['label'] . '" id="status_span_' . $aRow['id'] . '" task-status-table="' . $status['table'] . '">' . $status['text'];
         }

         if (has_permission('order_tracker', '', 'edit') || is_admin()) {
            $rli_filter .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
            $rli_filter .= '<a href="#" class="dropdown-toggle text-dark" id="tablePurOderStatus-' . $aRow['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $rli_filter .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
            $rli_filter .= '</a>';

            $rli_filter .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tablePurOderStatus-' . $aRow['id'] . '">';

            foreach ($status_labels as $key => $status) {
               if ($key != $aRow['rli_filter']) {
                  $rli_filter .= '<li>
                       <a href="#" onclick="change_rli_filter(' . $key . ', ' . $aRow['id'] . ', \'' . htmlspecialchars($aRow['source_table'], ENT_QUOTES) . '\'); return false;">
                           ' . $status['text'] . '
                       </a>
                   </li>';
               }
            }


            $rli_filter .= '</ul>';
            $rli_filter .= '</div>';
         }

         $rli_filter .= '</span>';
         $_data = $rli_filter;
      } elseif ($column == 'budget') {
         // Check if budget exists in the database
         if (!empty($aRow['budget'])) {
            // Display as plain text
            $_data = '<span class="budget-display" data-id="' . $aRow['id'] . '" data-type="' . $aRow['source_table'] . '">' .
               app_format_money($aRow['budget'], '₹') .
               '</span>';
         } else {
            // Render as an editable input if no budget exists
            $_data = '<input type="number" class="form-control budget-input" 
                         placeholder="Enter budget" 
                         data-id="' . $aRow['id'] . '" 
                         data-type="' . $aRow['source_table'] . '">';
         }
      } elseif ($column == 'co_total') {
         $base_currency = get_base_currency_pur();
         $_data = app_format_money($aRow['co_total'], $base_currency->symbol);
      } elseif ($column == 'total_rev_contract_value') {
         $base_currency = get_base_currency_pur();
         $_data = app_format_money($aRow['total_rev_contract_value'], $base_currency->symbol);
      } elseif ($column == 'anticipate_variation') {
         // Check if anticipate_variation exists in the database
         if (!empty($aRow['anticipate_variation'])) {
            // Display as plain text
            $_data = '<span class="anticipate-variation-display" data-id="' . $aRow['id'] . '" data-type="' . $aRow['source_table'] . '">' .
               app_format_money($aRow['anticipate_variation'], '₹') .
               '</span>';
         } else {
            // Render as an editable input if no value exists
            $_data = '<input type="number" class="form-control anticipate-variation-input" 
                     placeholder="Enter variation" 
                     data-id="' . $aRow['id'] . '" 
                     data-type="' . $aRow['source_table'] . '">';
         }
      } elseif ($column == 'cost_to_complete') {
         $base_currency = get_base_currency_pur();
         $_data = app_format_money($aRow['cost_to_complete'], $base_currency->symbol);
      } elseif ($column == 'final_certified_amount') {
         // Format final_certified_amount to display as currency
         $_data = app_format_money($aRow['final_certified_amount'], '₹');
      } elseif ($column == 'remarks') {
         // If remarks exist, display as plain text with an inline editing option
         $_data = '<span class="remarks-display" data-id="' . $aRow['id'] . '" data-type="' . $aRow['source_table'] . '">' .
            htmlspecialchars($aRow['remarks']) .
            '</span>';
         // If empty, allow direct input
         if (empty($aRow['remarks'])) {
            $_data = '<textarea class="form-control remarks-input" placeholder="Enter remarks" data-id="' . $aRow['id'] . '" data-type="' . $aRow['source_table'] . '"></textarea>';
         }
      }

      $row[] = $_data;
   }

   $output['aaData'][] = $row;
   $sr++;
}
