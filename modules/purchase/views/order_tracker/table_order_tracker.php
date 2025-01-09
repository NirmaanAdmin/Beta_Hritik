<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Define common columns for both tables
$aColumns = [
    'order_name', // Will represent 'pur_order_name' or 'wo_order_name'
    'rli_filter',
    'vendor',
    'order_date',
    'completion_date',
    'total',
    'kind',
    'group_name',
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
    'LEFT JOIN ' . db_prefix() . 'pur_vendor ON ' . db_prefix() . 'pur_vendor.userid = combined_orders.vendor',
    'LEFT JOIN ' . db_prefix() . 'assets_group ON ' . db_prefix() . 'assets_group.group_id = combined_orders.group_pur',
];


$where = [];
$having = '';

// Query and process data
$result = data_tables_init_union($aColumns, $sIndexColumn, $sTable, $join, $where, [
    'combined_orders.id as id',
    'rli_filter',
    'vendor',
    'order_date',
    'completion_date',
    'total',
    'kind',
    'group_name',
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
            $rli_filter = '';

            if ($aRow['rli_filter'] == 0) {
                $rli_filter = '<span class="inline-block label label-danger" id="status_span_' . $aRow['id'] . '" task-status-table="provided_by_ril">' . _l('provided_by_ril');
            } else if ($aRow['rli_filter'] == 1) {
                $rli_filter = '<span class="inline-block label label-success" id="status_span_' . $aRow['id'] . '" task-status-table="new_item_service_been_addded_as_per_instruction">' . _l('new_item_service_been_addded_as_per_instruction');
            } else if ($aRow['rli_filter'] == 2) {
                $rli_filter = '<span class="inline-block label label-info" id="status_span_' . $aRow['id'] . '" task-status-table="pending_delivered">' . _l('due_to_spec_change_then_original_cost');
            } else if ($aRow['rli_filter'] == 3) {
                $rli_filter = '<span class="inline-block label label-warning" id="status_span_' . $aRow['id'] . '" task-status-table="partially_delivered">' . _l('deal_slip');
            } else if ($aRow['rli_filter'] == 4) {
                $rli_filter = '<span class="inline-block label label-warning" id="status_span_' . $aRow['id'] . '" task-status-table="partially_delivered">' . _l('to_be_provided_by_ril_but_managed_by_bil');
            } else if ($aRow['rli_filter'] == 5) {
                $rli_filter = '<span class="inline-block label label-warning" id="status_span_' . $aRow['id'] . '" task-status-table="partially_delivered">' . _l('due_to_additional_item_as_per_apex_instrution');
            } else if ($aRow['rli_filter'] == 6) {
                $rli_filter = '<span class="inline-block label label-warning" id="status_span_' . $aRow['id'] . '" task-status-table="partially_delivered">' . _l('event_expense');
            } else if ($aRow['rli_filter'] == 7) {
                $rli_filter = '<span class="inline-block label label-warning" id="status_span_' . $aRow['id'] . '" task-status-table="partially_delivered">' . _l('pending_procurements');
            } else if ($aRow['rli_filter'] == 8) {
                $rli_filter = '<span class="inline-block label label-warning" id="status_span_' . $aRow['id'] . '" task-status-table="partially_delivered">' . _l('common_services_in_ghj_scope');
            } else if ($aRow['rli_filter'] == 9) {
                $rli_filter = '<span class="inline-block label label-warning" id="status_span_' . $aRow['id'] . '" task-status-table="partially_delivered">' . _l('common_services_in_ril_scope');
            } else if ($aRow['rli_filter'] == 10) {
                $rli_filter = '<span class="inline-block label label-warning" id="status_span_' . $aRow['id'] . '" task-status-table="partially_delivered">' . _l('due_to_site_specfic_constraint');
            }


            if (has_permission('order_tracker', '', 'edit') || is_admin()) {
                $rli_filter .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
                $rli_filter .= '<a href="#" class="dropdown-toggle text-dark" id="tablePurOderStatus-' . $aRow['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $rli_filter .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
                $rli_filter .= '</a>';

                $rli_filter .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tablePurOderStatus-' . $aRow['id'] . '">';

                if ($aRow['rli_filter'] == 0) {
                    $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 0 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('provided_by_ril') . '
                              </a>
                           </li>';
                    $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 1 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('new_item_service_been_addded_as_per_instruction') . '
                              </a>
                           </li>';
                    $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 2 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('due_to_spec_change_then_original_cost') . '
                              </a>
                           </li>';
                    $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 3 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('deal_slip') . '
                              </a>
                           </li>';
                    $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 4 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('to_be_provided_by_ril_but_managed_by_bil') . '
                              </a>
                           </li>';
                           $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 5 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('due_to_additional_item_as_per_apex_instrution') . '
                              </a>
                           </li>';
                    $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 6 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('event_expense') . '
                              </a>
                           </li>';
                    $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 7 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('pending_procurements') . '
                              </a>
                           </li>';
                    $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 8 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('common_services_in_ghj_scope') . '
                              </a>
                           </li>';
                    $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 9 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('common_services_in_ril_scope') . '
                              </a>
                           </li>';
                           $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 10 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('due_to_site_specfic_constraint') . '
                              </a>
                           </li>';
                } else if ($aRow['rli_filter'] == 1) {
                    $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 0 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('undelivered') . '
                              </a>
                           </li>';
                    $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 2 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('pending_delivered') . '
                              </a>
                           </li>';
                    $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 3 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('partially_delivered') . '
                              </a>
                           </li>';
                } else if ($aRow['rli_filter'] == 2) {
                    $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 0 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('undelivered') . '
                              </a>
                           </li>';
                    $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 1 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('completely_delivered') . '
                              </a>
                           </li>';
                    $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 3 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('partially_delivered') . '
                              </a>
                           </li>';
                } else if ($aRow['rli_filter'] == 3) {
                    $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 0 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('undelivered') . '
                              </a>
                           </li>';
                    $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 1 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('completely_delivered') . '
                              </a>
                           </li>';
                    $rli_filter .= '<li>
                              <a href="#" onclick="change_rli_filter( 2 ,' . $aRow['id'] . '); return false;">
                                 ' . _l('pending_delivered') . '
                              </a>
                           </li>';
                }

                $rli_filter .= '</ul>';
                $rli_filter .= '</div>';
            }
            $rli_filter .= '</span>';
            $_data = $rli_filter;
        }
        $row[] = $_data;
    }

    $output['aaData'][] = $row;
    $sr++;
}
