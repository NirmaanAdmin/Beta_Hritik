<?php

defined('BASEPATH') or exit('No direct script access allowed');

$dimensions = $pdf->getPageDimensions();

$info_right_column = '';
$info_left_column  = '';

$info_right_column .= '<span style="font-weight:bold;font-size:27px;">' . _l('estimate_pdf_heading') . '</span><br />';
$info_right_column .= '<b style="color:#4e4e4e;"># ' . $estimate_number . '</b>';

if (get_option('show_status_on_pdf_ei') == 1) {
    $info_right_column .= '<br /><span style="color:rgb(' . estimate_status_color_pdf($status) . ');text-transform:uppercase;">' . format_estimate_status($status, '', false) . '</span>';
}

// Add logo
$info_left_column .= pdf_logo_url();
// Write top left logo and right column info/text
pdf_multi_row($info_left_column, $info_right_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$pdf->ln(10);

$organization_info = '<div style="color:#424242;">';
    $organization_info .= format_organization_info();
$organization_info .= '</div>';

// Estimate to
$estimate_info = '<b>' . _l('estimate_to') . '</b>';
$estimate_info .= '<div style="color:#424242;">';
$estimate_info .= format_customer_info($estimate, 'estimate', 'billing');
$estimate_info .= '</div>';

// ship to to
if ($estimate->include_shipping == 1 && $estimate->show_shipping_on_estimate == 1) {
    $estimate_info .= '<br /><b>' . _l('ship_to') . '</b>';
    $estimate_info .= '<div style="color:#424242;">';
    $estimate_info .= format_customer_info($estimate, 'estimate', 'shipping');
    $estimate_info .= '</div>';
}

$estimate_info .= '<br />' . _l('estimate_data_date') . ': ' . _d($estimate->date) . '<br />';

if (!empty($estimate->expirydate)) {
    $estimate_info .= _l('estimate_data_expiry_date') . ': ' . _d($estimate->expirydate) . '<br />';
}

if (!empty($estimate->reference_no)) {
    $estimate_info .= _l('reference_no') . ': ' . $estimate->reference_no . '<br />';
}

if ($estimate->sale_agent && get_option('show_sale_agent_on_estimates') == 1) {
    $estimate_info .= _l('sale_agent_string') . ': ' . get_staff_full_name($estimate->sale_agent) . '<br />';
}

if ($estimate->project_id && get_option('show_project_on_estimate') == 1) {
    $estimate_info .= _l('project') . ': ' . get_project_name_by_id($estimate->project_id) . '<br />';
}

foreach ($pdf_custom_fields as $field) {
    $value = get_custom_field_value($estimate->id, $field['id'], 'estimate');
    if ($value == '') {
        continue;
    }
    $estimate_info .= $field['name'] . ': ' . $value . '<br />';
}

$left_info  = $swap == '1' ? $estimate_info : $organization_info;
$right_info = $swap == '1' ? $organization_info : $estimate_info;

pdf_multi_row($left_info, $right_info, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

// The Table
$pdf->Ln(hooks()->apply_filters('pdf_info_and_table_separator', 6));

$base_currency = $cost_planning_details['estimate_detail']['currency'];
$tablecontents = '';
$tablecontents .= '<h4>TABLE OF CONTENTS</h4>';
$tablecontents .= '<table width="100%" cellspacing="0" cellpadding="3" border="0">';
$tablecontents .= '<tbody>
    <tr style="font-size:14px; font-weight:bold;">
        <td>1. '.strtoupper(_l('project_brief')).'</td>
    </tr>
    <tr style="font-size:14px; font-weight:bold;">
        <td>2. '.strtoupper(_l('area_summary')).'</td>
    </tr>
    <tr style="font-size:14px; font-weight:bold;">
        <td>3. '.strtoupper(_l('area_working')).'</td>
    </tr>
    <tr style="font-size:14px; font-weight:bold;">
        <td>4. '.strtoupper(_l('cost_plan_summary')).'</td>
    </tr>
    <tr style="font-size:14px; font-weight:bold;">
        <td>5. '.strtoupper(_l('detailed_costing_technical_assumptions')).'</td>
    </tr>
    <tr style="font-size:14px; font-weight:bold;">
        <td>6. '.strtoupper(_l('project_timelines')).'</td>
    </tr>
</tbody>';
$tablecontents .= '</table>';

$pdf->writeHTML($tablecontents, true, false, false, false, '');

$projectbrief = '';
$projectbrief .= '<p style="font-weight:bold;">1. '.strtoupper(_l('project_brief')).'</p>';
$projectbrief .= '<table width="100%" cellspacing="0" cellpadding="3" border="0">';
$projectbrief .= '<tbody>
    <tr style="font-size:13px;">
        <td>'.$cost_planning_details['estimate_detail']['project_brief'].'</td>
    </tr>
</tbody>';
$projectbrief .= '</table>';

$pdf->AddPage();
$pdf->writeHTML($projectbrief, true, false, false, false, '');

$areasummary = '';
$show_as_unit_name = $cost_planning_details['estimate_detail']['show_as_unit'] == 1 ? 'sqft' : 'sqm';
$areasummary .= '<p style="font-weight:bold;">2. '.strtoupper(_l('area_summary')).'</p>';
if(!empty($cost_planning_details['area_summary_tabs'])) {
    foreach ($cost_planning_details['area_summary_tabs'] as $akey => $avalue) {
        $areasummary .= '<p style="font-size:13px; padding-bottom: 5px;">'.($akey + 1).'. '.$avalue['name'].'</p>';
        $areasummary .= '<table width="100%" cellspacing="0" cellpadding="3" border="1">';
        $areasummary .= '
        <thead>
          <tr bgcolor="#323a45" style="color:#ffffff; font-size:13px;">
             <th width="50%;" align="center">'._l('floor').'</th>
             <th width="50%;" align="center">'._l('area').' ('.$show_as_unit_name.')</th>
          </tr>
        </thead>';
        $areasummary .= '<tbody>';
        if(!empty($cost_planning_details['all_area_summary'])) {
            $total_area_summary = 0;
            foreach ($cost_planning_details['all_area_summary'] as $item) {
                if($item['area_id'] == $avalue['id']) {
                    $total_area_summary = $total_area_summary + $item['area'];
                    $master_area_name = '';
                    if($avalue['id'] == 3) {
                        $master_area_name = get_functionality_area($item['master_area']); 
                    } else {
                        $master_area_name = get_master_area($item['master_area']);
                    }
                    $areasummary .= '
                    <tr style="font-size:12px;">
                        <td align="left">'.$master_area_name.'</td>
                        <td align="center">'.$item['area'].'</td>
                    </tr>';
                }
            }
            $areasummary .= '
            <tr style="font-size:12px; font-weight:bold;">
                <td align="left">Total</td>
                <td align="center">'.$total_area_summary.'</td>
            </tr>';
        }
        $areasummary .= '</tbody>';
        $areasummary .= '</table>';
    }
}

$pdf->AddPage();
$pdf->writeHTML($areasummary, true, false, false, false, '');

$areastatement = '';
$show_aw_unit_name = $cost_planning_details['estimate_detail']['show_aw_unit'] == 1 ? 'sqft' : 'sqm';
$areastatement .= '<p style="font-weight:bold;">3. '.strtoupper(_l('area_working')).'</p>';
if(!empty($cost_planning_details['area_statement_tabs'])) {
    $areastatement .= '<table width="100%" cellspacing="0" cellpadding="3" border="1">';
    $areastatement .= '
    <thead>
      <tr bgcolor="#323a45" style="color:#ffffff; font-size:13px;">
         <th width="40%" align="center">Room/Spaces</th>
         <th width="20%" align="center">Length ('.$show_aw_unit_name.')</th>
         <th width="20%" align="center">Width ('.$show_aw_unit_name.')</th>
         <th width="20%" align="center">Carpet Area ('.$show_aw_unit_name.')</th>
      </tr>
    </thead>';
    $areastatement .= '<tbody>';
    foreach ($cost_planning_details['area_statement_tabs'] as $akey => $avalue) {
        if($akey > 0) {
            $areastatement .= '
            <tr bgcolor="#323a45" style="color:#ffffff; font-size:13px;">
                <td width="40%" align="center">Room/Spaces</td>
                <td width="20%" align="center">Length ('.$show_aw_unit_name.')</td>
                <td width="20%" align="center">Width ('.$show_aw_unit_name.')</td>
                <td width="20%" align="center">Carpet Area ('.$show_aw_unit_name.')</td>
            </tr>'; 
        }
        $areastatement .= '
        <tr style="font-size:12px; font-weight:bold;">
            <td colspan="4" align="left">'.$avalue['name'].'</td>
        </tr>';
        $total_carpet_area = 0;
        if(!empty($cost_planning_details['area_working'])) {
            foreach ($cost_planning_details['area_working'] as $item) {
                if($item['area_id'] == $avalue['id']) {
                    $carpet_area = $item['area_length'] * $item['area_width'];
                    $total_carpet_area = $total_carpet_area + $carpet_area;
                    $areastatement .= '
                    <tr style="font-size:12px;">
                        <td width="40%" align="left">'.clear_textarea_breaks($item['area_description']).'</td>
                        <td width="20%" align="center">'.$item['area_length'].'</td>
                        <td width="20%" align="center">'.$item['area_width'].'</td>
                        <td width="20%" align="center">'.$carpet_area.'</td>
                    </tr>';
                }
            }
        }
        $areastatement .= '
        <tr style="font-size:12px; font-weight:bold;">
            <td colspan="3" align="left">Total</td>
            <td align="center">'.$total_carpet_area.'</td>
        </tr>';
    }
    $areastatement .= '</tbody>';
    $areastatement .= '</table>';
}

$pdf->AddPage();
$pdf->writeHTML($areastatement, true, false, false, false, '');

$costplansummary = '';
$costplansummary .= '<p style="font-weight:bold;">4. '.strtoupper(_l('cost_plan_summary')).'</p>';
$costplansummary .= '<table width="100%" cellspacing="0" cellpadding="3" border="0">';
$costplansummary .= '<tbody>
    <tr style="font-size:13px;">
        <td>'.$cost_planning_details['estimate_detail']['cost_plan_summary'].'</td>
    </tr>
</tbody>';
$costplansummary .= '</table><br><br>';
$costplansummary .= '<table width="100%" cellspacing="0" cellpadding="3" border="1">';
$costplansummary .= '
<thead>
  <tr bgcolor="#323a45" style="color:#ffffff; font-size:13px;">
     <th width="25%;" align="center">'._l('group_pur').'</th>
     <th width="25%;" align="center">Cost (INR)</th>
     <th width="25%;" align="center">Cost/BUA</th>
     <th width="25%;" align="center">'._l('remarks').'</th>
  </tr>
</thead>';
$costplansummary .= '<tbody>';
if(!empty($cost_planning_details['annexure_estimate'])) {
    $annexure_estimate = $cost_planning_details['annexure_estimate'];
    $total_amount = 0;
    $total_bua = 0;
    foreach($annexure_estimate as $ikey => $svalue) {
        $budget_summary_remarks = '';
        if(!empty($cost_planning_details['budget_info'])) {
            foreach ($cost_planning_details['budget_info'] as $cpkey => $cpvalue) 
            {
                if($cpvalue['budget_id'] == $svalue['annexure']) {
                    $budget_summary_remarks = $cpvalue['budget_summary_remarks'];
                }
            }
        }
        $total_amount = $total_amount + $svalue['amount'];
        $total_bua = $total_bua + $svalue['total_bua'];
        $costplansummary .= '<tr style="font-size:12px;">
            <td align="center">'.$svalue['name'].'</td>
            <td align="center">'.app_format_money($svalue['amount'], $base_currency).'</td>
            <td align="center">'.app_format_money($svalue['total_bua'], $base_currency).'</td>
            <td align="center">'.$budget_summary_remarks.'</td>
        </tr>';
    }
    $costplansummary .= '<tr style="font-size:12px; font-weight:bold;">
        <td align="center">Total</td>
        <td align="center">'.app_format_money($total_amount, $base_currency).'</td>
        <td align="center">'.app_format_money($total_bua, $base_currency).'</td>
        <td align="center"></td>
    </tr>';
}
$costplansummary .= '</tbody>';
$costplansummary .= '</table>';

$pdf->AddPage();
$pdf->writeHTML($costplansummary, true, false, false, false, '');

$detailedcosting = '';
$detailedcosting .= '<p style="font-weight:bold;">5. '.strtoupper(_l('detailed_costing_technical_assumptions')).'</p>';

$annexures = get_all_annexures();
$main_annexure_items = array();
$sub_annexure_items = array();
$estimate_items = $cost_planning_details['estimate_items'];
$multilevel_items = $cost_planning_details['multilevel_items'];
$sub_multilevel_items = $cost_planning_details['sub_multilevel_items'];
if(!empty($estimate_items)) {
    foreach ($estimate_items as $item) {
        if (!isset($main_annexure_items[$item['annexure']])) {
            $main_annexure_items[$item['annexure']] = $item;
        }
    }
    $main_annexure_items = array_column($main_annexure_items, 'annexure');
    $main_annexure_items = array_values(array_filter($annexures, function($annexure) use ($main_annexure_items) {
        return in_array($annexure['id'], $main_annexure_items, true);
    }));
    $main_annexure_items = array_values($main_annexure_items);
}
if(!empty($multilevel_items)) {
    $sub_annexure_items = array_column($multilevel_items, 'annexure');
    $sub_annexure_items = array_values(array_filter($annexures, function($annexure) use ($sub_annexure_items) {
        return in_array($annexure['id'], $sub_annexure_items, true);
    }));
    $sub_annexure_items = array_values($sub_annexure_items);
}
$annexures = array_merge($main_annexure_items, $sub_annexure_items);
usort($annexures, function($a, $b) {
    return $a['id'] <=> $b['id'];
});

$unique_multilevel_items = array();
if(!empty($multilevel_items)) {
    $unique_multilevel_items = array_values(array_intersect_key($multilevel_items, array_unique(array_column($multilevel_items, 'int_master_area'))
));
}

function alphaCounter($index) {
    $result = '';
    while ($index >= 0) {
        $result = chr($index % 26 + 97) . $result;
        $index = floor($index / 26) - 1;
    }
    return $result;
}

function toRoman($number) {
    $map = [
        'm' => 1000, 'cm' => 900, 'd' => 500, 'cd' => 400,
        'c' => 100, 'xc' => 90, 'l' => 50, 'xl' => 40,
        'x' => 10, 'ix' => 9, 'v' => 5, 'iv' => 4, 'i' => 1
    ];
    $roman = '';
    foreach ($map as $romanChar => $value) {
        while ($number >= $value) {
            $roman .= $romanChar;
            $number -= $value;
        }
    }
    return $roman;
}

foreach ($annexures as $key => $annexure) {
    if($annexure['id'] == 7) {
        $detailedcosting .= '<p style="font-weight:bold; font-size:13px;">' . strtoupper($annexure['name']) . '</p>';
        if(!empty($unique_multilevel_items)) {
            foreach ($unique_multilevel_items as $ukey => $uvalue) {
                $detailedcosting .= '<p style="font-weight:bold; font-size:11px;">' . alphaCounter($ukey). '. ' . get_master_area($uvalue['int_master_area']) . '</p>';
                $detailedcosting .= '<p style="font-size:11px;">Summary of ' . get_master_area($uvalue['int_master_area']) . '</p>';
                $detailedcosting .= '<table width="100%" cellspacing="0" cellpadding="3" border="1">';
                $detailedcosting .= '
                <thead>
                  <tr bgcolor="#323a45" style="color:#ffffff; font-size:12px;">
                     <th width="40%;" align="left">'._l('estimate_table_item_description').'</th>
                     <th width="20%;" align="center">'._l('area').' (sqft)</th>
                     <th width="20%;" align="center">'._l('estimate_table_rate_heading').'</th>
                     <th width="20%;" align="center">'._l('estimate_table_amount_heading').'</th>
                  </tr>
                </thead>';
                $master_id = $uvalue['int_master_area'];
                $filtered_master_area = array_values(array_filter($multilevel_items, function($item) use ($master_id) {
                    return $item['int_master_area'] == $master_id;
                }));
                $detailedcosting .= '<tbody>';
                $fm_total_rate = 0;
                $fm_total_amount = 0;
                if(!empty($filtered_master_area)) {
                    foreach ($filtered_master_area as $fmkey => $fmvalue) {
                        $fm_amount = $fmvalue['int_area'] * $fmvalue['int_rate'];
                        $fm_total_rate = $fm_total_rate + $fmvalue['int_rate'];
                        $fm_total_amount = $fm_total_amount + $fm_amount;
                        $detailedcosting .= '<tr style="font-size:11px;">
                            <td width="40%;" align="left">'.get_functionality_area($fmvalue['int_fun_area']).'</td>
                            <td width="20%;" align="center">'.$fmvalue['int_area'].'</td>
                            <td width="20%;" align="center">'.app_format_money($fmvalue['int_rate'], $base_currency).'</td>
                            <td width="20%;" align="center">'.app_format_money($fm_amount, $base_currency).'</td>
                        </tr>';
                    }
                }
                $detailedcosting .= '<tr style="font-size:11px; font-weight:bold;">
                    <td width="40%;" align="left">Total</td>
                    <td width="20%;" align="center"></td>
                    <td width="20%;" align="center">'.app_format_money($fm_total_rate, $base_currency).'</td>
                    <td width="20%;" align="center">'.app_format_money($fm_total_amount, $base_currency).'</td>
                </tr>';
                $detailedcosting .= '</tbody>';
                $detailedcosting .= '</table>';

                if(!empty($filtered_master_area)) {
                    foreach ($filtered_master_area as $fmkey => $fmvalue) {
                        $parent_id = $fmvalue['id'];
                        $filtered_multilevel_items = array_values(array_filter($sub_multilevel_items, function($item) use ($parent_id) {
                            return $item['parent_id'] == $parent_id;
                        }));
                        if(!empty($filtered_multilevel_items)) {
                            $detailedcosting .= '<p style="font-weight:bold; font-size:11px;">' . toRoman($fmkey+1). '. ' . get_functionality_area($fmvalue['int_fun_area']) . '</p>';
                            $detailedcosting .= '<table width="100%" cellspacing="0" cellpadding="3" border="1">';
                            $detailedcosting .= '
                            <thead>
                              <tr bgcolor="#323a45" style="color:#ffffff; font-size:12px;">
                                 <th width="19%;" align="center">'._l('estimate_table_item_heading').'</th>
                                 <th width="22%;" align="center">'._l('estimate_table_item_description').'</th>
                                 <th width="10%;" align="center">'._l('unit').'</th>
                                 <th width="12%;" align="center">'._l('area').' (sqft)</th>
                                 <th width="11%;" align="center">'._l('estimate_table_quantity_heading').'</th>
                                 <th width="13%;" align="center">'._l('estimate_table_rate_heading').' (INR)</th>
                                 <th width="13%;" align="center">'._l('estimate_table_amount_heading').' (INR)</th>
                              </tr>
                            </thead>';
                            $detailedcosting .= '<tbody>';
                            $filtered_multilevel_qty = 0;
                            $filtered_multilevel_rate = 0;
                            $filtered_multilevel_amount = 0;
                            foreach ($filtered_multilevel_items as $fmlkey => $fmlvalue) {
                                $sub_amount = $fmlvalue['sub_rate'] * $fmlvalue['sub_qty'];
                                $filtered_multilevel_qty = $filtered_multilevel_qty + $fmlvalue['sub_qty'];
                                $filtered_multilevel_rate = $filtered_multilevel_rate + $fmlvalue['sub_rate'];
                                $filtered_multilevel_amount = $filtered_multilevel_amount + $sub_amount;
                                $purchase_unit_name = get_purchase_unit($fmlvalue['sub_unit_id']);
                                $purchase_unit_name = !empty($purchase_unit_name) ? ' '.$purchase_unit_name : '';
                                $detailedcosting .= '<tr style="font-size:11px;">
                                    <td width="19%;" align="left">'.get_purchase_items($fmlvalue['item_name']).'</td>
                                    <td width="22%;" align="left">'.clear_textarea_breaks($fmlvalue['sub_long_description']).'</td>
                                    <td width="10%;" align="center">'.get_purchase_unit($fmlvalue['sub_unit_id']).'</td>
                                    <td width="12%;" align="center">'.$fmlvalue['sub_budget_area'].'</td>
                                    <td width="11%;" align="center">'.number_format($fmlvalue['sub_qty'], 2).$purchase_unit_name.'</td>
                                    <td width="13%;" align="center">'.app_format_money($fmlvalue['sub_rate'], $base_currency).'</td>
                                    <td width="13%;" align="center">'.app_format_money($sub_amount, $base_currency).'</td>
                                </tr>';
                            }
                            $detailedcosting .= '<tr style="font-size:11px; font-weight:bold;">
                                <td width="19%;" align="left">Total</td>
                                <td width="22%;" align="center"></td>
                                <td width="10%;" align="center"></td>
                                <td width="12%;" align="center"></td>
                                <td width="11%;" align="center">'.app_format_money($filtered_multilevel_qty, $base_currency).'</td>
                                <td width="13%;" align="center">'.app_format_money($filtered_multilevel_rate, $base_currency).'</td>
                                <td width="13%;" align="center">'.app_format_money($filtered_multilevel_amount, $base_currency).'</td>
                            </tr>';
                            $detailedcosting .= '</tbody>';
                            $detailedcosting .= '</table>';
                        }
                    }
                }
            }
        }
    } else {
        $detailedcosting .= '<p style="font-weight:bold; font-size:13px; padding-bottom: 5px;">' . strtoupper($annexure['name']) . '</p>';
        $detailedcosting .= '<table width="100%" cellspacing="0" cellpadding="3" border="1">';
        $detailedcosting .= '
        <thead>
          <tr bgcolor="#323a45" style="color:#ffffff; font-size:12px;">
             <th width="19%;" align="center">'._l('estimate_table_item_heading').'</th>
             <th width="22%;" align="center">'._l('estimate_table_item_description').'</th>
             <th width="10%;" align="center">'._l('unit').'</th>
             <th width="12%;" align="center">'._l('area').' (sqft)</th>
             <th width="11%;" align="center">'._l('estimate_table_quantity_heading').'</th>
             <th width="13%;" align="center">'._l('estimate_table_rate_heading').' (INR)</th>
             <th width="13%;" align="center">'._l('estimate_table_amount_heading').' (INR)</th>
          </tr>
        </thead>';
        if(!empty($estimate_items)) {
            $detailedcosting .= '<tbody>';
            $total_rate = 0;
            $total_amount = 0;
            $overall_budget_area = '';
            foreach ($estimate_items as $item) {
                if($item['annexure'] == $annexure['id']) { 
                    $amount = $item['rate'] * $item['qty'];
                    $total_rate = $total_rate + $item['rate'];
                    $total_amount = $total_amount + $amount;
                    $purchase_unit_name = get_purchase_unit($item['unit_id']);
                    $purchase_unit_name = !empty($purchase_unit_name) ? ' '.$purchase_unit_name : '';
                    $detailedcosting .= '<tr style="font-size:11px;">
                        <td width="19%;" align="left">'.get_purchase_items($item['item_code']).'</td>
                        <td width="22%;" align="left">'.clear_textarea_breaks($item['long_description']).'</td>
                        <td width="10%;" align="center">'.get_purchase_unit($item['unit_id']).'</td>
                        <td width="12%;" align="center">'.$item['budget_area'].'</td>
                        <td width="11%;" align="center">'.number_format($item['qty'], 2).$purchase_unit_name.'</td>
                        <td width="13%;" align="center">'.app_format_money($item['rate'], $base_currency).'</td>
                        <td width="13%;" align="center">'.app_format_money($amount, $base_currency).'</td>
                    </tr>';
                }
            }
            if(!empty($cost_planning_details['budget_info'])) {
                foreach ($cost_planning_details['budget_info'] as $cpkey => $cpvalue) {
                    if($cpvalue['budget_id'] == $annexure['id']) {
                        $overall_budget_area = $cpvalue['overall_budget_area'];
                    }
                }
            }
            $detailedcosting .= '<tr style="font-size:11px; font-weight:bold;">
                <td width="19%;" align="left">Total</td>
                <td width="22%;" align="center"></td>
                <td width="10%;" align="center">sqft</td>
                <td width="12%;" align="center">'.$overall_budget_area.'</td>
                <td width="11%;" align="center"></td>
                <td width="13%;" align="center">'.app_format_money($total_rate, $base_currency).'</td>
                <td width="13%;" align="center">'.app_format_money($total_amount, $base_currency).'</td>
            </tr>';
            $detailedcosting .= '</tbody>';
        }
        $detailedcosting .= '</table>';
    }

    $detailed_costing_editor = '';
    if(!empty($cost_planning_details['budget_info'])) {
        foreach ($cost_planning_details['budget_info'] as $cpkey => $cpvalue) {
            if($cpvalue['budget_id'] == $annexure['id']) {
                $detailed_costing_editor = $cpvalue['detailed_costing'];
            }
        }
    }
    if(!empty($detailed_costing_editor)) {
        $detailedcosting .= '<table width="100%" cellspacing="0" cellpadding="3" border="0">';
        $detailedcosting .= '<tbody>
            <tr style="font-size:13px;">
                <td>'.$detailed_costing_editor.'</td>
            </tr>
        </tbody>';
        $detailedcosting .= '</table>';
    }
}

$pdf->AddPage();
$pdf->writeHTML($detailedcosting, true, false, false, false, '');

$projecttimelines = '';
$projecttimelines .= '<p style="font-weight:bold;">6. '.strtoupper(_l('project_timelines')).'</p>';
$projecttimelines .= '<table width="100%" cellspacing="0" cellpadding="3" border="0">';
$projecttimelines .= '<tbody>
    <tr style="font-size:13px;">
        <td>'.$cost_planning_details['estimate_detail']['project_timelines'].'</td>
    </tr>
</tbody>';
$projecttimelines .= '</table>';

$pdf->AddPage();
$pdf->writeHTML($projecttimelines, true, false, false, false, '');
