<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    '"id" as id',
    '"order_name" as order_name',
    '"vendor" as vendor',
    '"order_date" as order_date',
    '"group_pur" as group_pur',
    '"approve_status" as approve_status',
    '"applied_to_vendor_bill" as applied_to_vendor_bill',
];

$sIndexColumn = 'id';
$sTable = db_prefix().'payment_certificate';
$join = [
    'LEFT JOIN ' . db_prefix() . 'pur_orders 
    ON ' . db_prefix() . 'payment_certificate.po_id IS NOT NULL 
    AND ' . db_prefix() . 'pur_orders.id = ' . db_prefix() . 'payment_certificate.po_id',
    'LEFT JOIN ' . db_prefix() . 'wo_orders 
    ON ' . db_prefix() . 'payment_certificate.wo_id IS NOT NULL 
    AND ' . db_prefix() . 'wo_orders.id = ' . db_prefix() . 'payment_certificate.wo_id',
];

$where = [];

$having = '';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, 
[
    db_prefix().'payment_certificate.id',
    db_prefix().'payment_certificate.po_id',
    db_prefix().'payment_certificate.wo_id',
    db_prefix().'payment_certificate.approve_status',
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
    END AS order_name',

], '', [], $having);

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
        if($aRow['currency'] != 0){
            $base_currency = pur_get_currency_by_id($aRow['currency']);
        }

        if($aColumns[$i] == 'id'){
            $_data = '';
            if(!empty($aRow['po_id'])) {
                $_data = '<a href="' . admin_url('purchase/payment_certificate/' . $aRow['po_id'].'/'.$aRow['id']) . '" target="_blank">' . _l('view') . '</a>';
            } if(!empty($aRow['wo_id'])) {
                $_data = '<a href="' . admin_url('purchase/wo_payment_certificate/' . $aRow['wo_id'].'/'.$aRow['id']) . '" target="_blank">' . _l('view') . '</a>';
            }
        } elseif($aColumns[$i] == 'order_name'){
            $_data = '';
            if(!empty($aRow['po_id'])) {
                $_data = '<a href="' . admin_url('purchase/purchase_order/' . $aRow['po_id']) . '" target="_blank">' . $aRow['order_name'] . '</a>';
            }
            if(!empty($aRow['wo_id'])) {
                $_data = '<a href="' . admin_url('purchase/work_order/' . $aRow['wo_id']) . '" target="_blank">' . $aRow['order_name'] . '</a>';
            }
        } elseif($aColumns[$i] == 'vendor'){
            $_data = '<a href="' . admin_url('purchase/vendor/' . $aRow['vendor']) . '" >' .  get_vendor_company_name($aRow['vendor']) . '</a>';
        } elseif($aColumns[$i] == 'order_date'){
            $_data = _d($aRow['order_date']);
        } elseif($aColumns[$i] == 'group_pur'){
            $budget_head = get_group_name_item($aRow['group_pur']);
            $_data = $budget_head->name;
        } elseif($aColumns[$i] == 'approve_status'){
            $_data = '';
            $list_approval_details = get_list_approval_details($aRow['id'], 'payment_certificate');
            if(empty($list_approval_details)) {
                if($aRow['approve_status'] == 2) {
                    $_data = '<span class="label label-success">'._l('approved').'</span>';
                } else {
                    $_data = '<span class="label label-primary">'._l('send_request_approve_pur').'</span>';
                }
            } else if($aRow['approve_status'] == 1) {
                $_data = '<span class="label label-primary">'._l('pur_draft').'</span>';
            } else if($aRow['approve_status'] == 2) {
                $_data = '<span class="label label-success">'._l('approved').'</span>';
            } else if($aRow['approve_status'] == 3) {
                $_data = '<span class="label label-danger">'._l('rejected').'</span>';
            } else {
                $_data = '';
            }
        } elseif($aColumns[$i] == 'applied_to_vendor_bill'){
            $_data = '';
            if($aRow['approve_status'] == 2) {
                $_data = '<a href="'.admin_url('purchase/convert_pur_invoice_from_po/'.$aRow['id']).'" class="btn btn-info convert-pur-invoice" target="_blank">'._l('convert_to_vendor_bill').'</a>';
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
