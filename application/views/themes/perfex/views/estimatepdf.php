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
                        <td align="right">'.$item['area'].'</td>
                    </tr>';
                }
            }
            $areasummary .= '
            <tr style="font-size:12px; font-weight:bold;">
                <td align="left">Total</td>
                <td align="right">'.$total_area_summary.'</td>
            </tr>';
        }
        $areasummary .= '</tbody>';
        $areasummary .= '</table>';
    }
}

$pdf->AddPage();
$pdf->writeHTML($areasummary, true, false, false, false, '');

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
    $costplansummary .= '<tr style="font-size:12px;">
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
