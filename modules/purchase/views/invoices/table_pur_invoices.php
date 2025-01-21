<?php

defined('BASEPATH') or exit('No direct script access allowed');

$custom_fields = get_custom_fields('pur_invoice', [
    'show_on_table' => 1,
]);


$aColumns = [
    'invoice_number',
    'vendor_invoice_number',
    db_prefix() . 'pur_invoices.vendor',
    db_prefix() . 'items_groups.name',
    // db_prefix() . 'projects.name',
    db_prefix() . 'pur_invoices.pur_order',
    // db_prefix() . 'pur_invoices.wo_order',
    'invoice_date',
    // 'payment_request_status',
    'payment_status',
    'expense_convert',
    'vendor_submitted_amount_without_tax',
    'vendor_submitted_tax_amount',    
    'vendor_submitted_amount',
    'final_certified_amount',
    'transactionid',
    'vendor_note',
];
if(isset($vendor) || isset($project)){
    $aColumns = [
        'invoice_number',
        'vendor_invoice_number',
        db_prefix() . 'pur_invoices.vendor',
        db_prefix() . 'items_groups.name',
        // db_prefix() . 'projects.name',
        db_prefix() . 'pur_invoices.pur_order',
        'invoice_date',
        // 'payment_request_status',
        'payment_status',
        'vendor_submitted_amount_without_tax',
        'vendor_submitted_tax_amount',    
        'vendor_submitted_amount',
        'final_certified_amount',
        'transactionid',
        'vendor_note',
    ];
}

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'pur_invoices';
$join         = [
    'LEFT JOIN ' . db_prefix() . 'pur_contracts ON ' . db_prefix() . 'pur_contracts.id = ' . db_prefix() . 'pur_invoices.contract',
    'LEFT JOIN ' . db_prefix() . 'projects ON ' . db_prefix() . 'pur_invoices.project_id = ' . db_prefix() . 'projects.id',
    'LEFT JOIN ' . db_prefix() . 'items_groups ON ' . db_prefix() . 'pur_invoices.group_pur = ' . db_prefix() . 'items_groups.id',
];

$i = 0;
foreach ($custom_fields as $field) {
    $select_as = 'cvalue_' . $i;
    if ($field['type'] == 'date_picker' || $field['type'] == 'date_picker_time') {
        $select_as = 'date_picker_cvalue_' . $i;
    }
    array_push($aColumns, 'ctable_' . $i . '.value as ' . $select_as);
    array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $i . ' ON ' . db_prefix() . 'pur_invoices.id = ctable_' . $i . '.relid AND ctable_' . $i . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $i . '.fieldid=' . $field['id']);
    $i++;
}


$where = [];


if (
    $this->ci->input->post('from_date')
    && $this->ci->input->post('from_date') != ''
) {
    array_push($where, 'AND invoice_date >= "' . to_sql_date($this->ci->input->post('from_date')) . '"');
}

if (isset($vendor)) {
    array_push($where, ' AND ' . db_prefix() . 'pur_invoices.vendor = ' . $vendor);
}


if (
    $this->ci->input->post('to_date')
    && $this->ci->input->post('to_date') != ''
) {
    array_push($where, 'AND invoice_date <= "' . to_sql_date($this->ci->input->post('to_date')) . '"');
}

if (!has_permission('purchase_invoices', '', 'view')) {
    array_push($where, 'AND (' . db_prefix() . 'pur_invoices.add_from = ' . get_staff_user_id() . ' OR ' . db_prefix() . 'pur_invoices.vendor IN (SELECT vendor_id FROM ' . db_prefix() . 'pur_vendor_admin WHERE staff_id=' . get_staff_user_id() . '))');
}

$contract = $this->ci->input->post('contract');
if (isset($contract)) {
    $where_contract = '';
    foreach ($contract as $t) {
        if ($t != '') {
            if ($where_contract == '') {
                $where_contract .= ' AND (' . db_prefix() . 'pur_invoices.contract = "' . $t . '"';
            } else {
                $where_contract .= ' or ' . db_prefix() . 'pur_invoices.contract = "' . $t . '"';
            }
        }
    }
    if ($where_contract != '') {
        $where_contract .= ')';
        array_push($where, $where_contract);
    }
}

$pur_orders = $this->ci->input->post('pur_orders');
if (isset($pur_orders)) {
    $where_pur_orders = '';
    foreach ($pur_orders as $t) {
        if ($t != '') {
            if ($where_pur_orders == '') {
                $where_pur_orders .= ' AND (' . db_prefix() . 'pur_invoices.pur_order = "' . $t . '"';
            } else {
                $where_pur_orders .= ' or ' . db_prefix() . 'pur_invoices.pur_order = "' . $t . '"';
            }
        }
    }
    if ($where_pur_orders != '') {
        $where_pur_orders .= ')';
        array_push($where, $where_pur_orders);
    }
}
$wo_orders = $this->ci->input->post('wo_orders');
if (isset($wo_orders)) {
    $where_wo_orders = '';
    foreach ($wo_orders as $t) {
        if ($t != '') {
            if ($where_wo_orders == '') {
                $where_wo_orders .= ' AND (' . db_prefix() . 'pur_invoices.wo_order = "' . $t . '"';
            } else {
                $where_wo_orders .= ' or ' . db_prefix() . 'pur_invoices.wo_order = "' . $t . '"';
            }
        }
    }
    if ($where_wo_orders != '') {
        $where_wo_orders .= ')';
        array_push($where, $where_wo_orders);
    }
}

$vendors = $this->ci->input->post('vendors');
if (isset($vendors)) {
    $where_vendors = '';
    foreach ($vendors as $t) {
        if ($t != '') {
            if ($where_vendors == '') {
                $where_vendors .= ' AND (' . db_prefix() . 'pur_invoices.vendor = "' . $t . '"';
            } else {
                $where_vendors .= ' or ' . db_prefix() . 'pur_invoices.vendor = "' . $t . '"';
            }
        }
    }
    if ($where_vendors != '') {
        $where_vendors .= ')';
        array_push($where, $where_vendors);
    }
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    db_prefix() . 'pur_invoices.id as id',
    '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'pur_invoices.id and rel_type="pur_invoice" ORDER by tag_order ASC) as tags',
    'contract_number',
    'invoice_number',
    'currency',
    'expense_convert',
    db_prefix() . 'pur_invoices.wo_order',
    db_prefix() . 'items_groups.name',
]);

$output  = $result['output'];
$rResult = $result['rResult'];

$footer_data = [
    'total_vendor_submitted_amount_without_tax' => 0,
    'total_vendor_submitted_tax_amount' => 0,
    'total_vendor_submitted_amount' => 0,
    'total_final_certified_amount' => 0,
];

$this->ci->load->model('purchase/purchase_model');

foreach ($rResult as $aRow) {
    $row = [];

    for ($i = 0; $i < count($aColumns); $i++) {

        $base_currency = get_base_currency_pur();
        if ($aRow['currency'] != 0) {
            $base_currency = pur_get_currency_by_id($aRow['currency']);
        }

        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        if ($aColumns[$i] == 'invoice_number') {
            $numberOutput = '';

            $numberOutput = '<a href="' . admin_url('purchase/purchase_invoice/' . $aRow['id']) . '"  >' . $aRow['invoice_number'] . '</a>';

            $numberOutput .= '<div class="row-options">';

            if (has_permission('purchase_invoices', '', 'view') || has_permission('purchase_invoices', '', 'view_own')) {
                $numberOutput .= ' <a href="' . admin_url('purchase/purchase_invoice/' . $aRow['id']) . '" >' . _l('view') . '</a>';
            }
            if ((has_permission('purchase_invoices', '', 'edit') || is_admin())) {
                $numberOutput .= ' | <a href="' . admin_url('purchase/pur_invoice/' . $aRow['id']) . '">' . _l('edit') . '</a>';
            }
            if (has_permission('purchase_invoices', '', 'delete') || is_admin()) {
                $numberOutput .= ' | <a href="' . admin_url('purchase/delete_pur_invoice/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            $numberOutput .= '</div>';

            $_data = $numberOutput;
        } else if($aColumns[$i] == db_prefix() . 'items_groups.name') {
            $budget_head = '';
            $budget_head .= '<span class="inline-block label label-info" id="budget_span_' . $aRow['id'] . '">' . $aRow['name'];
            $budget_head .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
            $budget_head .= '<a href="#" class="dropdown-toggle text-dark" id="tableChangeBudget-' . $aRow['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $budget_head .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
            $budget_head .= '</a>';
            $budget_head .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tableChangeBudget-' . $aRow['id'] . '">';
            $group_name_item = get_group_name_item();
            foreach ($group_name_item as $gkey => $gvalue) {
                $budget_head .= '<li>
                    <a href="#" onclick="change_budget_head( ' . $gvalue['id'] . ',' . $aRow['id'] . '); return false;">
                    ' . $gvalue['name'] . '
                    </a>
                </li>';
            }
            $budget_head .= '</ul>';
            $budget_head .= '</div>';
            $budget_head .= '</span>';
            $_data = $budget_head;
        } else if ($aColumns[$i] == 'vendor_invoice_number') {
            if ($aRow['vendor_invoice_number'] != '') {
                $_data = '<input type="text" class="form-control vin-input" placeholder="Enter invoice number" data-id="' . $aRow['id'] . '" value="' . $aRow['vendor_invoice_number'] . '" size="10">';
            } else {
                $_data = $aRow['invoice_number'];
            }
        } elseif ($aColumns[$i] == 'vendor_note') {
            $_data = render_tags($aRow['tags']);
        } elseif ($aColumns[$i] == 'invoice_date') {
            $_data = '<input type="date" class="form-control invoice-date-input" value="' . $aRow['invoice_date'] . '" data-id="' . $aRow['id'] . '">';
        } elseif ($aColumns[$i] == 'vendor_submitted_amount_without_tax') {
            $_data = app_format_money($aRow['vendor_submitted_amount_without_tax'], $base_currency->symbol);
        } elseif ($aColumns[$i] == 'vendor_submitted_tax_amount') {
            // $tax = $this->ci->purchase_model->get_html_tax_pur_invoice($aRow['id']);
            // $total_tax = 0;
            // foreach ($tax['taxes_val'] as $tax_val) {
            //     $total_tax += $tax_val;
            // }

            $_data = app_format_money($aRow['vendor_submitted_tax_amount'], $base_currency->symbol);
        } elseif ($aColumns[$i] == 'final_certified_amount') {
            $_data = app_format_money($aRow['final_certified_amount'], $base_currency->symbol);
        } elseif ($aColumns[$i] == 'vendor_submitted_amount') {
            $_data = app_format_money($aRow['vendor_submitted_amount'], $base_currency->symbol);
        }elseif ($aColumns[$i] == 'payment_status') {
            // $class = ''; 
            // if($aRow['payment_status'] == 'unpaid'){
            //     $class = 'danger';
            // }elseif($aRow['payment_status'] == 'paid'){
            //     $class = 'success';
            // }elseif ($aRow['payment_status'] == 'partially_paid') {
            //     $class = 'warning';
            // }

            // $_data = '<span class="label label-'.$class.' s-status invoice-status-3">'._l($aRow['payment_status']).'</span>';

            $delivery_status = '';

            if ($aRow['payment_status'] == 1) {
                $delivery_status = '<span class="inline-block label label-danger" id="status_span_' . $aRow['id'] . '" task-status-table="rejected">' . _l('rejected');
            } else if ($aRow['payment_status'] == 2) {
                $delivery_status = '<span class="inline-block label label-info" id="status_span_' . $aRow['id'] . '" task-status-table="recevied_with_comments">' . _l('recevied_with_comments');
            } else if ($aRow['payment_status'] == 3) {
                $delivery_status = '<span class="inline-block label label-warning" id="status_span_' . $aRow['id'] . '" task-status-table="bill_verification_in_process">' . _l('bill_verification_in_process');
            } else if ($aRow['payment_status'] == 4) {
                $delivery_status = '<span class="inline-block label label-primary" id="status_span_' . $aRow['id'] . '" task-status-table="bill_verification_on_hold">' . _l('bill_verification_on_hold');
            } else if ($aRow['payment_status'] == 5) {
                $delivery_status = '<span class="inline-block label label-success" id="status_span_' . $aRow['id'] . '" task-status-table="bill_verified_by_ril">' . _l('bill_verified_by_ril');
            } else if ($aRow['payment_status'] == 6) {
                $delivery_status = '<span class="inline-block label label-success" id="status_span_' . $aRow['id'] . '" task-status-table="payment_certifiate_issued">' . _l('payment_certifiate_issued');
            } else if ($aRow['payment_status'] == 7) {
                $delivery_status = '<span class="inline-block label label-success" id="status_span_' . $aRow['id'] . '" task-status-table="payment_processed">' . _l('payment_processed');
            } else if ($aRow['payment_status'] == 0) {
                $delivery_status = '<span class="inline-block label label-danger" id="status_span_' . $aRow['id'] . '" task-status-table="unpaid">' . _l('unpaid');
            }
            if (has_permission('purchase_invoices', '', 'edit') || is_admin()) {
                $delivery_status .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
                $delivery_status .= '<a href="#" class="dropdown-toggle text-dark" id="tablePurOderStatus-' . $aRow['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $delivery_status .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
                $delivery_status .= '</a>';

                $delivery_status .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tablePurOderStatus-' . $aRow['id'] . '">';

                $delivery_status .= '<li>
                            <a href="#" onclick="change_payment_status( 0,' . $aRow['id'] . '); return false;">
                            ' . _l('unpaid') . '
                            </a>
                        </li>';
                $delivery_status .= '<li>
                              <a href="#" onclick="change_payment_status( 1,' . $aRow['id'] . '); return false;">
                                 ' . _l('rejected') . '
                              </a>
                           </li>';
                $delivery_status .= '<li>
                              <a href="#" onclick="change_payment_status( 2,' . $aRow['id'] . '); return false;">
                                 ' . _l('recevied_with_comments') . '
                              </a>
                           </li>';
                $delivery_status .= '<li>
                              <a href="#" onclick="change_payment_status( 3,' . $aRow['id'] . '); return false;">
                                 ' . _l('bill_verification_in_process') . '
                              </a>
                           </li>';
                $delivery_status .= '<li>
                           <a href="#" onclick="change_payment_status( 4,' . $aRow['id'] . '); return false;">
                              ' . _l('bill_verification_on_hold') . '
                           </a>
                        </li>';
                $delivery_status .= '<li>
                           <a href="#" onclick="change_payment_status( 5,' . $aRow['id'] . '); return false;">
                              ' . _l('bill_verified_by_ril') . '
                           </a>
                        </li>';
                $delivery_status .= '<li>
                        <a href="#" onclick="change_payment_status( 6,' . $aRow['id'] . '); return false;">
                           ' . _l('payment_certifiate_issued') . '
                        </a>
                     </li>';
                $delivery_status .= '<li>
                        <a href="#" onclick="change_payment_status( 7,' . $aRow['id'] . '); return false;">
                           ' . _l('payment_processed') . '
                        </a>
                     </li>';


                $delivery_status .= '</ul>';
                $delivery_status .= '</div>';
            }
            $delivery_status .= '</span>';
            $_data = $delivery_status;
        } elseif ($aColumns[$i] == 'contract') {
            $_data = '<a href="' . admin_url('purchase/contract/' . $aRow['contract']) . '">' . $aRow['contract_number'] . '</a>';
        } elseif ($aColumns[$i] == 'payment_request_status') {
            $_data = get_payment_request_status_by_inv($aRow['id']);
        } elseif ($aColumns[$i] == db_prefix() . 'pur_invoices.pur_order') {
            $order_name = '';
            $order_name .= '<a href="' . admin_url('purchase/purchase_order/' . $aRow[db_prefix() . 'pur_invoices.pur_order']) . '">' . get_pur_order_subject($aRow[db_prefix() . 'pur_invoices.pur_order']) . '</a>';
            $order_name .= '<a href="' . admin_url('purchase/work_order/' . $aRow[db_prefix() . 'pur_invoices.wo_order']) . '">' . get_wo_order_subject($aRow[db_prefix() . 'pur_invoices.wo_order']) . '</a>';
            $_data = $order_name;
        } 
        // elseif ($aColumns[$i] == db_prefix() . 'pur_invoices.wo_order') {
        //     $_data = '<a href="' . admin_url('purchase/work_order/' . $aRow[db_prefix() . 'pur_invoices.wo_order']) . '">' . get_wo_order_subject($aRow[db_prefix() . 'pur_invoices.wo_order']) . '</a>';
        // } 
        elseif ($aColumns[$i] == db_prefix() . 'pur_invoices.vendor') {
            $_data = '<a href="' . admin_url('purchase/vendor/' . $aRow[db_prefix() . 'pur_invoices.vendor']) . '" >' .  get_vendor_company_name($aRow[db_prefix() . 'pur_invoices.vendor']) . '</a>';
        } elseif ($aColumns[$i] == 'expense_convert') {
            $expense_convert = '';
            if($aRow['expense_convert'] == 0){
             $expense_convert = '<a href="javascript:void(0)" onclick="convert_expense('.$aRow['id'].','.$aRow['final_certified_amount'].'); return false;" class="btn btn-warning btn-icon">'._l('convert').'</a>';
            }else{
                $expense_convert_check = get_expense_data($aRow['expense_convert']);
                if(!empty($expense_convert_check)) {
                    if(!empty($expense_convert_check->invoiceid)) {
                        $invoice_data = get_invoice_data($expense_convert_check->invoiceid);
                        if(!empty($invoice_data)) {
                            $expense_convert = e(format_invoice_number($invoice_data->id)). " (".$invoice_data->title.")";
                        }
                    }
                } else {
                    $expense_convert = '<a href="javascript:void(0)" onclick="convert_expense('.$aRow['id'].','.$aRow['final_certified_amount'].'); return false;" class="btn btn-warning btn-icon">'._l('convert').'</a>';
                }
            }
            $_data = $expense_convert;
        } else {
            if (strpos($aColumns[$i], 'date_picker_') !== false) {
                $_data = (strpos($_data, ' ') !== false ? _dt($_data) : _d($_data));
            }
        }

        $row[] = $_data;
    }

    $footer_data['total_vendor_submitted_amount_without_tax'] += $aRow['vendor_submitted_amount_without_tax'];
    $footer_data['total_vendor_submitted_tax_amount'] += $aRow['vendor_submitted_tax_amount'];
    $footer_data['total_vendor_submitted_amount'] += $aRow['vendor_submitted_amount'];
    $footer_data['total_final_certified_amount'] += $aRow['final_certified_amount'];
    $output['aaData'][] = $row;
}

foreach ($footer_data as $key => $total) {
    $footer_data[$key] = app_format_money($total, $base_currency->symbol);
}

$output['sums'] = $footer_data;
