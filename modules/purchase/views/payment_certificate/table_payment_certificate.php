<?php

defined('BASEPATH') or exit('No direct script access allowed');
$module_name = 'payment_certificate';
$vendors_filter_name = 'vendors';
$group_pur_filter_name = 'group_pur';
$approval_status_filter_name = 'approval_status';

$aColumns = [
    db_prefix() . 'payment_certificate' . '.id as id',
    'po_id',
    '"vendor" as vendor',
    db_prefix() . 'payment_certificate' . '.order_date as order_date',
    '"group_pur" as group_pur',
    db_prefix() . 'payment_certificate' . '.approve_status as approve_status',
    db_prefix() . 'payment_certificate' . '.approve_status as applied_to_vendor_bill',
];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'payment_certificate';
$join = [
    'LEFT JOIN ' . db_prefix() . 'pur_orders 
    ON ' . db_prefix() . 'payment_certificate.po_id IS NOT NULL 
    AND ' . db_prefix() . 'pur_orders.id = ' . db_prefix() . 'payment_certificate.po_id',
    'LEFT JOIN ' . db_prefix() . 'wo_orders 
    ON ' . db_prefix() . 'payment_certificate.wo_id IS NOT NULL 
    AND ' . db_prefix() . 'wo_orders.id = ' . db_prefix() . 'payment_certificate.wo_id',
];

$where = [];
if ($this->ci->input->post('vendors') && count($this->ci->input->post('vendors')) > 0) {
    array_push($where, 'AND ((' . db_prefix() . 'payment_certificate.po_id IS NOT NULL AND ' . db_prefix() . 'pur_orders.vendor IN (' . implode(',', $this->ci->input->post('vendors')) . ')) OR (' . db_prefix() . 'payment_certificate.wo_id IS NOT NULL AND ' . db_prefix() . 'wo_orders.vendor IN (' . implode(',', $this->ci->input->post('vendors')) . ')))');
}

if ($this->ci->input->post('group_pur') && count($this->ci->input->post('group_pur')) > 0) {
    array_push($where, 'AND ((' . db_prefix() . 'payment_certificate.po_id IS NOT NULL AND ' . db_prefix() . 'pur_orders.group_pur IN (' . implode(',', $this->ci->input->post('group_pur')) . ')) OR (' . db_prefix() . 'payment_certificate.wo_id IS NOT NULL AND ' . db_prefix() . 'wo_orders.group_pur IN (' . implode(',', $this->ci->input->post('group_pur')) . ')))');
}
if ($this->ci->input->post('approval_status') && count($this->ci->input->post('approval_status')) > 0) {
    array_push($where, 'AND (' . db_prefix() . 'payment_certificate.approve_status IN (' . implode(',', $this->ci->input->post('approval_status')) . '))');
}

$vendors_filter_name_value = !empty($this->ci->input->post('vendors')) ? implode(',', $this->ci->input->post('vendors')) : NULL;
update_module_filter($module_name, $vendors_filter_name, $vendors_filter_name_value);

$group_pur_filter_name_value = !empty($this->ci->input->post('group_pur')) ? implode(',', $this->ci->input->post('group_pur')) : NULL;
update_module_filter($module_name, $group_pur_filter_name, $group_pur_filter_name_value);

$approval_status_filter_name_value = !empty($this->ci->input->post('approval_status')) ? implode(',', $this->ci->input->post('approval_status')) : NULL;
update_module_filter($module_name, $approval_status_filter_name, $approval_status_filter_name_value);

$having = '';

$result = data_tables_init(
    $aColumns,
    $sIndexColumn,
    $sTable,
    $join,
    $where,
    [
        db_prefix() . 'payment_certificate.id',
        db_prefix() . 'payment_certificate.po_id',
        db_prefix() . 'payment_certificate.wo_id',
        db_prefix() . 'payment_certificate.approve_status',
        'CASE 
        WHEN ' . db_prefix() . 'payment_certificate.po_id IS NOT NULL THEN ' . db_prefix() . 'pur_orders.vendor
        WHEN ' . db_prefix() . 'payment_certificate.wo_id IS NOT NULL THEN ' . db_prefix() . 'wo_orders.vendor
    END AS vendor',
        'CASE 
        WHEN ' . db_prefix() . 'payment_certificate.po_id IS NOT NULL THEN ' . db_prefix() . 'pur_orders.order_date
        WHEN ' . db_prefix() . 'payment_certificate.wo_id IS NOT NULL THEN ' . db_prefix() . 'wo_orders.order_date
    END AS order_date',
        'CASE 
        WHEN ' . db_prefix() . 'payment_certificate.po_id IS NOT NULL THEN ' . db_prefix() . 'pur_orders.group_pur
        WHEN ' . db_prefix() . 'payment_certificate.wo_id IS NOT NULL THEN ' . db_prefix() . 'wo_orders.group_pur
    END AS group_pur',
        'CASE 
        WHEN ' . db_prefix() . 'payment_certificate.po_id IS NOT NULL THEN ' . db_prefix() . 'pur_orders.pur_order_number
        WHEN ' . db_prefix() . 'payment_certificate.wo_id IS NOT NULL THEN ' . db_prefix() . 'wo_orders.wo_order_number
    END AS po_id',

    ],
    '',
    [],
    $having
);

$output  = $result['output'];
$rResult = $result['rResult'];

$aColumns = array_map(function ($col) {
    $col = trim($col);
    if (stripos($col, ' as ') !== false) {
        $parts = preg_split('/\s+as\s+/i', $col);
        return trim($parts[1], '"` ');
    }
    return trim($col, '"` ');
}, $aColumns);

$this->ci->load->model('purchase/purchase_model');
foreach ($rResult as $aRow) {
    $row = [];

    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

        $base_currency = get_base_currency_pur();
        if ($aRow['currency'] != 0) {
            $base_currency = pur_get_currency_by_id($aRow['currency']);
        }

        if ($aColumns[$i] == 'id') {
            $_data = '';
            if (!empty($aRow['po_id'])) {
                $_data = '<a href="' . admin_url('purchase/payment_certificate/' . $aRow['po_id'] . '/' . $aRow['id']) . '" target="_blank">' . _l('view') . '</a>';
            }
            if (!empty($aRow['wo_id'])) {
                $_data = '<a href="' . admin_url('purchase/wo_payment_certificate/' . $aRow['wo_id'] . '/' . $aRow['id']) . '" target="_blank">' . _l('view') . '</a>';
            }
        } elseif ($aColumns[$i] == 'po_id') {
            $_data = '';
            if (!empty($aRow['po_id'])) {
                $_data = '<a href="' . admin_url('purchase/purchase_order/' . $aRow['po_id']) . '" target="_blank">' . $aRow['po_id'] . '</a>';
            }
            if (!empty($aRow['wo_id'])) {
                $_data = '<a href="' . admin_url('purchase/work_order/' . $aRow['wo_id']) . '" target="_blank">' . $aRow['po_id'] . '</a>';
            }
        } elseif ($aColumns[$i] == 'vendor') {
            $_data = '<a href="' . admin_url('purchase/vendor/' . $aRow['vendor']) . '" >' .  get_vendor_company_name($aRow['vendor']) . '</a>';
        } elseif ($aColumns[$i] == 'order_date') {
            $_data = _d($aRow['order_date']);
        } elseif ($aColumns[$i] == 'group_pur') {
            $budget_head = get_group_name_item($aRow['group_pur']);
            $_data = $budget_head->name;
        } elseif ($aColumns[$i] == 'approve_status') {
            $_data = '';
            $list_approval_details = get_list_approval_details($aRow['id'], 'payment_certificate');
            if (empty($list_approval_details)) {
                if ($aRow['approve_status'] == 2) {
                    $_data = '<span class="label label-success">' . _l('approved') . '</span>';
                } else if ($aRow['approve_status'] == 3) {
                    $_data = '<span class="label label-danger">' . _l('rejected') . '</span>';
                } else {
                    $_data = '<span class="label label-primary">' . _l('send_request_approve_pur') . '</span>';
                }
            } else if ($aRow['approve_status'] == 1) {
                $_data = '<span class="label label-primary">' . _l('pur_draft') . '</span>';
            } else if ($aRow['approve_status'] == 2) {
                $_data = '<span class="label label-success">' . _l('approved') . '</span>';
            } else if ($aRow['approve_status'] == 3) {
                $_data = '<span class="label label-danger">' . _l('rejected') . '</span>';
            } else {
                $_data = '';
            }
        } elseif ($aColumns[$i] == 'applied_to_vendor_bill') {
            $_data = '';
            if ($aRow['approve_status'] == 2) {
                $_data = '<a href="' . admin_url('purchase/convert_pur_invoice_from_po/' . $aRow['id']) . '" class="btn btn-info convert-pur-invoice" target="_blank">' . _l('convert_to_vendor_bill') . '</a>';
            }
        } else {
            if (strpos($aColumns[$i], 'date_picker_') !== false) {
                $_data = (strpos($_data, ' ') !== false ? _dt($_data) : _d($_data));
            }
        }

        $row[] = $_data;
    }
    $output['aaData'][] = $row;
    $sr++;
}
