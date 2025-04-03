<?php

defined('BASEPATH') or exit('No direct script access allowed');

$module_name = 'order_tracker';
$type_filter_name = 'order_tracker_type';
$rli_filter_name = 'rli_filter';
$vendors_filter_name = 'vendors';
$kind_filter_name = 'order_tracker_kind';
$budget_head_filter_name = 'budget_head';
$order_type_filter_name = 'order_type_filter';

// Define common columns for both tables
$aColumns = [
   'order_name', // Will represent 'pur_order_name' or 'wo_order_name'
   'rli_filter',
   'vendor',
   'order_date',
   'completion_date',
   'budget',
   'order_value',
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

$orderType = $this->ci->input->post('order_type_filter');
if (isset($orderType)) {
    $where_order_type = '';
    if ($orderType == 'created') {
        if ($where_order_type == '') {
            $where_order_type .= ' AND (source_table  = "order_tracker"';
        }
    }
    if ($orderType == 'fetched') {
        if ($where_order_type == '') {
            $where_order_type .= ' AND (source_table  = "pur_orders"';
            $where_order_type .= ' or source_table = "wo_orders"';
        }
    }
    if ($where_order_type != '') {
        $where_order_type .= ')';
        array_push($where, $where_order_type);
    }
}

$vendors = $this->ci->input->post('vendors');
if (isset($vendors)) {
    $where_vendors = '';
    foreach ($vendors as $t) {
        if ($t != '') {
            if ($where_vendors == '') {
                $where_vendors .= ' AND (vendor_id = "' . $t . '"';
            } else {
                $where_vendors .= ' or vendor_id = "' . $t . '"';
            }
        }
    }
    if ($where_vendors != '') {
        $where_vendors .= ')';
        array_push($where, $where_vendors);
    }
}

$budget_head = $this->ci->input->post('budget_head');
if (isset($budget_head)) {
    $where_budget_head = '';
    if ($budget_head != '') {
        if ($where_budget_head == '') {
            $where_budget_head .= ' AND (group_pur = "' . $budget_head . '"';
        } else {
            $where_budget_head .= ' or group_pur = "' . $budget_head . '"';
        }
    }
    if ($where_budget_head != '') {
        $where_budget_head .= ')';
        array_push($where, $where_budget_head);
    }
}

$budget_head = $this->ci->input->post('budget_head');
if (isset($budget_head)) {
    $where_budget_head = '';
    if ($budget_head != '') {
        if ($where_budget_head == '') {
            $where_budget_head .= ' AND (group_pur = "' . $budget_head . '"';
        } else {
            $where_budget_head .= ' or group_pur = "' . $budget_head . '"';
        }
    }
    if ($where_budget_head != '') {
        $where_budget_head .= ')';
        array_push($where, $where_budget_head);
    }
}

$rli_filter = $this->ci->input->post('rli_filter');
if (isset($rli_filter)) {
    $where_rli_filter = '';
    if ($rli_filter != '') {
        if ($where_rli_filter == '') {
            $where_rli_filter .= ' AND (rli_filter = "' . $rli_filter . '"';
        } else {
            $where_rli_filter .= ' or rli_filter = "' . $rli_filter . '"';
        }
    }
    if ($where_rli_filter != '') {
        $where_rli_filter .= ')';
        array_push($where, $where_rli_filter);
    }
}

$kind = $this->ci->input->post('kind');
if (isset($kind)) {
    $where_kind = '';
    if ($kind != '') {
        if ($where_kind == '') {
            $where_kind .= ' AND (kind = "' . $kind . '"';
        } else {
            $where_kind .= ' or kind = "' . $kind . '"';
        }
    }
    if ($where_kind != '') {
        $where_kind .= ')';
        array_push($where, $where_kind);
    }
}

$having = '';

$type_filter_value = !empty($this->ci->input->post('type')) ? implode(',', $this->ci->input->post('type')) : NULL;
update_module_filter($module_name, $type_filter_name, $type_filter_value);

$vendors_filter_value = !empty($this->ci->input->post('vendors')) ? implode(',', $this->ci->input->post('vendors')) : NULL;
update_module_filter($module_name, $vendors_filter_name, $vendors_filter_value);

$rli_filter_value = !empty($this->ci->input->post('rli_filter')) ? $this->ci->input->post('rli_filter') : NULL;
update_module_filter($module_name, $rli_filter_name, $rli_filter_value);

$kind_filter_value = !empty($this->ci->input->post('kind')) ? $this->ci->input->post('kind') : NULL;
update_module_filter($module_name, $kind_filter_name, $kind_filter_value);

$budget_head_filter_name_value = !empty($this->ci->input->post('budget_head')) ? $this->ci->input->post('budget_head') : NULL;
update_module_filter($module_name, $budget_head_filter_name, $budget_head_filter_name_value);

$order_type_filter_name_value = !empty($this->ci->input->post('order_type_filter')) ? $this->ci->input->post('order_type_filter') : NULL;
update_module_filter($module_name, $order_type_filter_name, $order_type_filter_name_value);

// Query and process data
$result = data_tables_init_union($aColumns, $sIndexColumn, $sTable, $join, $where, [
   'combined_orders.id as id',
   'rli_filter',
   'vendor',
   'vendor_id',
   'order_date',
   'completion_date',
   'budget',
   'order_value',
   'co_total',
   'total',
   'total_rev_contract_value',
   'anticipate_variation',
   'cost_to_complete',
   'final_certified_amount',
   'kind',
   'group_name',
   'remarks',
   'group_pur',
   'source_table',
]);

$output  = $result['output'];
$rResult = $result['rResult'];

$footer_data = [
    'total_budget_ro_projection' => 0,
    'total_order_value' => 0,
    'total_committed_contract_amount' => 0,
    'total_change_order_amount' => 0,
    'total_rev_contract_value' => 0,
    'total_anticipate_variation' => 0,
    'total_cost_to_complete' => 0,
    'total_final_certified_amount' => 0,
];

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
         if ($aRow['source_table'] == "pur_orders") {
            $_data = '<a href="' . admin_url('purchase/pur_order/' . $aRow['id']) . '" target="_blank">' . $aRow['order_name'] . '</a>';
         } elseif ($aRow['source_table'] == "wo_orders") {
            $_data = '<a href="' . admin_url('purchase/wo_order/' . $aRow['id']) . '" target="_blank">' . $aRow['order_name'] . '</a>';
         } elseif ($aRow['source_table'] == "order_tracker") {
            $name = $aRow['order_name'];
            $name .= '<div class="row-options">';
            if ((has_permission('purchase-order', '', 'delete') || is_admin()) && ($aRow['source_table'] == "order_tracker")) {
               $name .= '<a href="' . admin_url('purchase/delete_order_tracker/' . $aRow['id']) . '" class="text-danger _delete" >' . _l('delete') . '</a>';
            }
            $name .= '</div>';
            $_data = $name;
         }
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
         // $base_currency = get_base_currency_pur();
         // $_data = app_format_money($aRow['co_total'], $base_currency->symbol);

         // Check if anticipate_variation exists in the database
         if (!empty($aRow['co_total'])) {
            // Display as plain text
            $_data = '<span class="co-total-display" data-id="' . $aRow['id'] . '" data-type="' . $aRow['source_table'] . '">' .
               app_format_money($aRow['co_total'], '₹') .
               '</span>';
         } else {
            // Render as an editable input if no value exists
            // $_data = '<input type="number" class="form-control co-total-input"
            //          placeholder="Enter Change Order"
            //          data-id="' . $aRow['id'] . '"
            //          data-type="' . $aRow['source_table'] . '">';
            $_data = '<span style="font-style: italic;font-size: 12px;">Values will be fetched directly from the change order module</span>';
         }
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
         // $_data = app_format_money($aRow['final_certified_amount'], '₹');

         if (!empty($aRow['final_certified_amount'])) {
            // Display as plain text
            $_data = '<span class="final-certified-amount-display" data-id="' . $aRow['id'] . '" data-type="' . $aRow['source_table'] . '">' .
               app_format_money($aRow['final_certified_amount'], '₹') .
               '</span>';
         } else {
            // Render as an editable input if no value exists
            $_data = '<span style="font-style: italic;font-size: 12px;">Please enter the certified amount in Vendor Billing Tracker</span>';
         }
      } elseif ($column == 'remarks') {
         // If remarks exist, display as plain text with an inline editing option
         $_data = '<span class="remarks-display" data-id="' . $aRow['id'] . '" data-type="' . $aRow['source_table'] . '">' .
            htmlspecialchars($aRow['remarks']) .
            '</span>';
         // If empty, allow direct input
         if (empty($aRow['remarks'])) {
            $_data = '<textarea class="form-control remarks-input" placeholder="Enter remarks" data-id="' . $aRow['id'] . '" data-type="' . $aRow['source_table'] . '"></textarea>';
         }
      } elseif ($column == 'order_value') {
         $base_currency = get_base_currency_pur();
         $_data = '<span class="order-value-display" data-id="' . $aRow['id'] . '" data-type="' . $aRow['source_table'] . '">' .app_format_money($aRow['order_value'], $base_currency->symbol) .'</span>';
      }

      $row[] = $_data;
   }

   $footer_data['total_budget_ro_projection'] += $aRow['budget'];
   $footer_data['total_order_value'] += $aRow['order_value'];
   $footer_data['total_committed_contract_amount'] += $aRow['total'];
   $footer_data['total_change_order_amount'] += $aRow['co_total'];
   $footer_data['total_rev_contract_value'] += $aRow['total_rev_contract_value'];
   $footer_data['total_anticipate_variation'] += $aRow['anticipate_variation'];
   $footer_data['total_cost_to_complete'] += $aRow['cost_to_complete'];
   $footer_data['total_final_certified_amount'] += $aRow['final_certified_amount'];
   $output['aaData'][] = $row;
   $sr++;
}

foreach ($footer_data as $key => $total) {
    $footer_data[$key] = app_format_money($total, $base_currency->symbol);
}
$output['sums'] = $footer_data;
