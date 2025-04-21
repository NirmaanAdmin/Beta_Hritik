<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix().'pur_vendor.company',
    'pdf_filename',
    'created',
];

$sIndexColumn = 'id';
$sTable = db_prefix().'dms_share_transmittal';
$join = [
    'LEFT JOIN '.db_prefix().'pur_contacts ON '.db_prefix().'pur_contacts.id = '.db_prefix().'dms_share_transmittal.vendor_contact',
    'LEFT JOIN '.db_prefix().'pur_vendor ON '.db_prefix().'pur_vendor.userid = '.db_prefix().'pur_contacts.userid',
];

$where = [];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, 
[
    db_prefix().'dms_share_transmittal.id',
    db_prefix().'pur_vendor.userid',
]);

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

foreach ($rResult as $aRow) {
    $row = [];

   for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

        if($aColumns[$i] == 'tblpur_vendor.company'){
            $_data = '<a href="' . admin_url('purchase/vendor/' . $aRow['userid']) . '" target="_blank">' .  $aRow[db_prefix().'pur_vendor.company'] . '</a>';
        } elseif($aColumns[$i] == 'pdf_filename'){
            $_data = '<a href="'.base_url(DRAWING_MANAGEMENT_PATH . 'transmittal/'.$aRow['pdf_filename']).'" class="btn btn-info" target="_blank">'._l('dmg_preview').'</a>';
        } elseif($aColumns[$i] == 'created'){
            $_data = _d($aRow['created']);
        } else {
            if (strpos($aColumns[$i], 'date_picker_') !== false) {
                $_data = (strpos($_data, ' ') !== false ? _dt($_data) : _d($_data));
            }
        }

        $row[] = $_data;
    }
    $output['aaData'][] = $row;
}
